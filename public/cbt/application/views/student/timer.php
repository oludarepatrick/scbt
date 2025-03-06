<?php
    $loginid = $_SESSION['users']['uid'];

    $class=$_SESSION['users']['class'];
    $termID=$_SESSION['users']['termID'];
    $term=$_SESSION['users']['term'];
    $sesID=$_SESSION['users']['sesID'];
    $session=$_SESSION['users']['session'];

    $qtypID=base64_decode($_GET['id']);
    $sub=$_SESSION['sub'];
    $typ=$_SESSION['type'];
    $subID=$_SESSION['subID'];
    $clID=$_SESSION['classID'];
    
    if($typ=="Examination")
    {
        $typCBT="exam";
    }else{
        $typCBT=($typ=='First CA'?'ca1':($typ=='Second CA'?'ca2':'ca3'));
    }

    $marks=$_SESSION['marks'];
    $expectedtime=$_SESSION['timInsec'];
    $duration=$_SESSION['duration'];
        
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $se = $pdo->query ("SELECT `id`, `time_start`, `time_spend`, `subj_id`, `term_id`, `session`  FROM `timer` WHERE `stud_id`=$loginid AND `subj_id`='$subID' AND `term_id`='$termID' AND `class_id`='$clID' AND `session`='$session' AND `exam_type`='$typCBT' ");

    //echo "SELECT `id`, `time_start`, `time_spend`, `subj_id`, `term_id`, `session`  FROM `timer` WHERE `stud_id`=$loginid AND `subj_id`='$subID' AND `term_id`='$termID' AND `class_id`='$clID' AND `session`='$session' AND `exam_type`='$typCBT' ";

    $rowse = $se->fetch(PDO::FETCH_ASSOC);
    
    //$sT=strtotime($rowse['time_start']);
    
    //echo $se->rowCount();
    echo '<p style="color:forestgreen"><strong>Time started :'. date('h:i:s a') .'</strong></p>';
   
    echo '<h5 style="color:royalblue">You are expected to stop at exactly: <b>'.$_SESSION['duration'].'</b></h5>';
		//Real Date
        //echo "time start".$tim;
		$start=$rowse['time_start'];
		$timspend=$rowse['time_spend'];

        //$expectedtime=20;
		
		//Current Date
		$date=date('Y-m-d H:i:s');
		
			
		sscanf($start,"%d:%d:%d",$hs,$ms,$ss);
		sscanf($date,"%d:%d:%d",$hc,$mc,$sc);
		sscanf($timspend,"%d:%d:%d",$hc1,$mc1,$sc1);
		
		$start=isset($ms)?$hs*3600+$ms*60+$ss:$hs*60+$ms;
		$now=isset($mc)?$hc*3600+$mc*60+$sc:$hc*60+$mc;
		$timspend=isset($mc1)?$hc1*3600+$mc1*60+$sc1:$hc1*60+$mc1;
		
        $eT=$timspend-$start;
        if($eT > 0)
        {
            $used=$eT;
        }
        else{ 
            $used=$now-$start; 
        }
        //echo "ex";
		$expectedtime=$expectedtime-$used;
		//echo "Started At: ".$start.'<br> Used: '.$used.'<br> Time Now:'.$now.'<br> Stoping Time: '.$expectedtime;	
		echo "
		<input type='hidden' value='$used' id='hiddentimeused'>
		<input type='hidden' value='$expectedtime' id='hiddentimetouse'>";
				
?>

<script src="../js/jquery-1.8.3.min.js"></script>


<script>

$(function(){
	$('#feedback2').ready(function(event)
    {
        var used=$('#hiddentimeused').val(),
        expected=$('#hiddentimetouse').val();			
        var timer=function()
        {
                used++;	
                var hour=parseInt(used/3600),
                minute=parseInt(used/60);

                var sec=parseInt((used)%60);

                if(minute>59){
                    minute-=60;					
                }

                if(parseInt(used)>(expected/2) && parseInt(used)<(expected-60)){
                    $('#feedback2').css({color:'rgba(69, 69, 255, 0.77)'});					
                }

                if((expected-used)<61){
                    $('#feedback2').css({color:'red'});
                }

                if(parseInt(used)>parseInt(expected)){
                    clearInterval(int);
                    window.location='close.php';
                }

                $('#feedback2').html(hour+' : '+minute+' : '+sec);
                $('#timeUsed').html(used);
                $('#timeExp').html(expected);
                    //timer();
                    //console.log(used);
        }

		if(parseInt(used)<parseInt(expected))
        {	
			timer;
			var int=setInterval(timer,1000);
		}else{
			console.log(used);
        }

	});
});	

</script>
<style type="text/css">
#feedback2{font-size:40px; font-family:tahoma; text-align: center;}

</style>
