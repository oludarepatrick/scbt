<?php

class dashboard extends Controller
{
    public function index()
    {
        if(empty($_SESSION['logged_id']['stud_id']) || !isset($_SESSION['logged_id']['stud_id']))
        {
            echo "<script type='text/javascript'>";
            echo "alert('Access Denied!');";
            echo "window.location='".URL."home/index'";
            echo "</script>";

            echo "<h3>Access Denied! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }
        $studId=$_SESSION['logged_id']['stud_id'];

        $activeTermSession = $this->loadModel2('SessionModel');
        $getTmSess=$activeTermSession->activeTermSession();
        
        $active_session=$getTmSess->session;
        $active_term=$getTmSess->term;
        
        $quiz_model = $this->loadModel('QuizModel');
        
        $isExamAssigned = $quiz_model->isExamAssigned($studId,$active_term,$active_session);
        
        //var_dump($quiz_model->getUU($studId));
        $assignedQuiz=array();
        foreach($isExamAssigned as $val)
        {
            $quizId = $val->quiz_id;
            $quizName = $val->name;
            $subject_id = $val->subject_id;
            $arm = $val->arm;
            $class_id = $val->class_id;
            $minutes = $val->minutes;
            $description = $val->description;
            
            $isAtempt=$quiz_model->isStudAtemptAny($quizId,$studId);

            $totalQue=$quiz_model->questionCount($quizId);

            $fdgTm=$minutes*60*1000;
            $quiz_model->timerSaver($studId,$fdgTm,$quizId);

            $timerDetail=$quiz_model->geTimer($studId,$quizId);
            
            $get_cbtResult=$quiz_model->getCbtResult($studId,$quizId);
            
            $totalScores=0;
            if($get_cbtResult->totalScores > 0 and $totalQue > 0)
            {
                $totalScores=round((($get_cbtResult->totalScores*100)/$totalQue),2);
            }
            
            
            //echo "<br/>".."--".$totalQue;

            $assignedQuiz[$quizId]=array(
                "quizId"=>$quizId,
                "quizName"=>$quizName,
                "subject_id"=>$subject_id,
                "arm"=>$arm,
                "class_id"=>$class_id,
                "minutes"=>$minutes,
                "description"=>$description,
                "totalQue"=>$totalQue,
                "isAtempt"=>$isAtempt,
                "remeningTime"=>$timerDetail->t_timer,
                "time_status"=>$timerDetail->status,
                "totalScores"=>$totalScores,
                "mark"=>$get_cbtResult->totalScores
            );
            

        }
        
        krsort($assignedQuiz);


        
        require 'application/views/_templates/header.php';
        require 'application/views/student/index.php';
        require 'application/views/_templates/footer.php';
    }

    public function start($details)
    {
        if(empty($details))
        {
            echo "<script type='text/javascript'>";
            echo "alert('Access Denied!');";
            echo "window.location='".URL."home/index'";
            echo "</script>";

            echo "<h3>Access Denied! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }
        
        $didS=base64_decode($details);
        list($quizId,$studId,$remeningTime)=explode('-', $didS);
        if(empty($quizId) || empty($studId))
        {
            echo "<h3>Access Denied! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }
        $quiz_model = $this->loadModel('QuizModel');
        $quizAct=$quiz_model->getQuizTime($quizId);

        $quizTime=$quizAct->minutes;

        $timerDetail=$quiz_model->geTimer($studId,$quizId);

        //echo $fgd=($timerDetail->t_timer)/60000;



        if($remeningTime > 0)
        {
            require 'application/views/_templates/header.php';
            require 'application/views/student/start.php';
            require 'application/views/_templates/footer.php';
        }
    }

    public function questions($val,$page)
    {
        if(empty($val) || !isset($val))
        {
            echo "<script type='text/javascript'>";
            echo "alert('Access Denied!');";
            echo "window.location='".URL."home/index'";
            echo "</script>";

            echo "<h3>Access Denied! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }
        
        list($quizId,$studId,$remeningTime)=explode('-', $val);
        
        //echo $studId.', '.$quizId;
        
        if(empty($quizId) || empty($studId))
        {
            echo "<h3>Access Denied! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }
        
        //require('Pagination.php');
        $page = (int) (!isset($page) ? 1 : $page);
        $limit = 1;
        $startpoint = ($page * $limit) - $limit; 
	

        $quiz_model = $this->loadModel('QuizModel');
        $questions = $quiz_model->getStudQuestion($quizId,$startpoint,$limit);
        
        $questionCount = $quiz_model->countQuestion($quizId);
        
        $atmCount = $quiz_model->countAtmp($quizId,$studId);
        
        $timerDetail=$quiz_model->geTimer($studId,$quizId);
        $myTnId=$timerDetail->id;

        $queses=array(); $couFF=0;
        foreach($questions as $qu)
        {
            $queId=$qu->id;
            $question=$qu->question;
            $mfile_ext=$qu->mfile_ext;

            $optses = $quiz_model->getOption($queId);
            $prev_tt = $quiz_model->getPrev($studId,$quizId,$queId);

            $chkd=(!empty($prev_tt->answer_id)?($chkd=$prev_tt->answer_id):0);
            
            if(!empty($prev_tt->answer_id))
            {
                $couFF++;
            }
            $gf=0;
            foreach($optses as $opt)
            {
                if($chkd==$opt->id){ $checked1="checked='checked'"; }else{ $checked1="";}

                $options[$gf]=array(
                    "answerId"=>$opt->id,
                    "option_desc"=>$opt->answer,
                    "is_correct"=>$opt->is_correct,
                    "checken"=>$checked1
                );
                $gf++;
            }
            $queses[$queId]=array(
                "queId"=>$queId,
                "mfile_ext"=>$mfile_ext,
                "question"=>$question,
                "options"=>$options
            );
            //var_dump($queses);
        }
        
        
        $total=$quiz_model->getCount($quizId);
        require 'application/views/student/display_quiz.php';
        echo $this->pagination($val,$queId,$chkd,$total,$limit,$page);
        
        //echo $questionCount.'--'.$atmCount;
        $ubmitBtn="";
        if($atmCount > $questionCount)
        {
            $atmCount=$questionCount;
        }
        if($questionCount==$atmCount || ($questionCount==($atmCount+1)  ) )
        {
            $ubmitBtn="<a href='".URL."dashboard/submit/".$myTnId."' onClick='return confirm(\"Are you sure you want submit\");' class='btn btn-sm btn-info'>Finish and Submit</a>";
        }
        echo "<div align='' style='margin-top:2px; color:brown'>
            <b>Student Atempt Question(s): ".$atmCount." out of ".$questionCount."</b>&nbsp;".$ubmitBtn."
        </div>";

    }

    /***pagination */
    public function pagination($baseUrl,$quizId,$chkd,$que_total,$per_page=1,$page=1)
    { 
        //echo "<h1>".$chkd."</h1>";
        $url = $baseUrl;
    	$total = $que_total;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
        //$current=($chkd!=0?'current':'nil');
        $current=($chkd!=0?'':'nil');

        //echo $page."~".$current."~".$chkd;

    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'><b>Question $page of $lastpage</b></li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a class='dbtn' href='{$url}/1'>1</a></li>";
    				$pagination.= "<li><a class='dbtn' href='{$url}/2'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a class='dbtn {$current}' href='{$url}/1'>1</a></li>";
    				$pagination.= "<li><a class='dbtn {$current}' href='{$url}/2'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a class='dbtn {$current}' href='{$url}/$next'>Next</a></li>";
                $pagination.= "<li><a class='dbtn {$current}' href='{$url}/$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
        return $pagination;
    }
    /****pagination */

    public function save()
    {
        list($quizId,$studId,$remeningTime,$queId,$answerId)=explode("-",$_POST['c']);
        $quiz_model = $this->loadModel('QuizModel');
        $quiz_model->add($studId,$queId,$quizId,$answerId);
    }
    
    public function updateTimer($id)
    {
        $quiz_model = $this->loadModel('QuizModel');
        $quiz_model->updateTimer($id,1000);
    }

    public function finish($timerId)
    {
        $quiz_model = $this->loadModel('QuizModel');
        $quiz_model->updateTimer($timerId,0,1);

        header("location: ".URL."dashboard/index");
    }
    
    public function submit($myTnId)
    {
        $quiz_model = $this->loadModel('QuizModel');
        $staDD=$quiz_model->submitQuiz($myTnId);
        
        
        $timerDetail=$quiz_model->geTimer(99,1);
        
        //echo "<h1>".$timerDetail->status."</h1>";
        //if($staDD==1)
        //{
            header("location: ".URL."dashboard/index");
        //}
    }
    
    public function viewResult($ddd)
    {
        $quiz_model = $this->loadModel('QuizModel');
        
        list($quizId,$studId,$title,$std_name)=explode("-",base64_decode($ddd));
        $questions11=$quiz_model->getQuestions($quizId);
        
        
        //echo $title;
        $queses11=array(); $couFF=0;
        foreach($questions11 as $qu)
        {
            $queId=$qu->id;
            $question=$qu->question;
            $mfile_ext=$qu->mfile_ext;

            $optses = $quiz_model->getOption($queId);
            $prev_tt = $quiz_model->getPrev($studId,$quizId,$queId);

            $chkd=(!empty($prev_tt->answer_id)?($chkd=$prev_tt->answer_id):0);
            
            if(!empty($prev_tt->answer_id))
            {
                $couFF++;
            }
            $gf=0;
            foreach($optses as $opt)
            {
                if($chkd==$opt->id){ $checked1=$opt->answer; }else{ $checked1="";}

                $options[$gf]=array(
                    "answerId"=>$opt->id,
                    "option_desc"=>$opt->answer,
                    "is_correct"=>$opt->is_correct,
                    "checken"=>$checked1
                );
                $gf++;
            }
            $queses11[$queId]=array(
                "queId"=>$queId,
                "mfile_ext"=>$mfile_ext,
                "question"=>$question,
                "options"=>$options
            );

        }
        
        require 'application/views/student/view-result.php';
    }
}
