<?php

class QuizModel
{
    /**
     * Every model needs a database connection, passed to the model
     * @param object $db A PDO database connection
     */
    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }
    
    public function isExamAssigned($studId,$active_term,$active_session)
    {
        $studId=strip_tags($studId);
        $active_term=strip_tags($active_term);
        $active_session=strip_tags($active_session);
        
        $sql = "SELECT a.`id`, a.`quiz_id`,b.`description`,b.`minutes`,b.`class_id`,b.`arm`,b.`subject_id`, a.`status`,b.`name` FROM `quiz_user` AS a,`quizzes` AS b WHERE a.`quiz_id`=b.`id` AND a.`user_id`=? AND b.`terms`=? AND b.`sessions`=? ORDER BY a.`id` DESC";
        $query = $this->db->prepare($sql);
        $query->execute(array($studId,$active_term,$active_session));

        return $query->fetchAll();
    }
    public function getQuizTime($quizId)
    {
        $quizId=strip_tags($quizId);
        $sql="SELECT `minutes`,`name` FROM `quizzes` WHERE `id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($quizId));

        return $query->fetch();
    }
    public function isStudAtemptAny($quizId,$studId)
    {
        $studId=strip_tags($studId);
        $quizId=strip_tags($quizId);

        $sql = "SELECT `id`, `user_id`, `question_id`, `quiz_id`, `answer_id` FROM `results` WHERE `quiz_id`=? AND `user_id`=? LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute(array($quizId,$studId));

        return $query->rowCount();
    }

    public function questionCount($quizId)
    {
        $quizId=strip_tags($quizId); 
        $sql = "SELECT * FROM `questions` WHERE `quiz_id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($quizId));

        return $query->rowCount();
    }
    public function getStudQuestion($quizId,$startpoint,$limit)
    {
        $quizId=strip_tags($quizId); 
        $sql = "SELECT `id`, `question`, `created_at`,`mfile_ext`, `updated_at` FROM `questions` WHERE `quiz_id`=? LIMIT {$startpoint} , {$limit}";
        $query = $this->db->prepare($sql);
        $query->execute(array($quizId));

        return $query->fetchAll();
    }

    public function getOption($queId)
    {
        $queId=strip_tags($queId); 
        $sql="SELECT `id`,`answer`, `is_correct` FROM `answers` WHERE `question_id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($queId));

        return $query->fetchAll();
    }
    public function getCount($queId)
    {
        $queId=strip_tags($queId); 
        $sql = "SELECT COUNT(*) as `num` FROM  `questions` WHERE `quiz_id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($queId));

        return $query->fetch()->num;
    }

    public function getPrev($userId,$quizId,$queId)
    {
        $queId=strip_tags($queId); 
        $quizId=strip_tags($quizId); 
        $userId=strip_tags($userId); 
        
        $sql="SELECT `answer_id` FROM `results` WHERE `user_id`=? AND `question_id`=? AND `quiz_id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($userId,$queId,$quizId));

        return $query->fetch();
    }

    public function add($userId,$queId,$quizId,$option)
    {
        $queId=strip_tags($queId); 
        $quizId=strip_tags($quizId); 
        $userId=strip_tags($userId);
        $option=strip_tags($option); 
        
        $chec="SELECT id FROM `results` WHERE `user_id`=? AND `question_id`=? AND `quiz_id`=?";
        $btfDD = $this->db->prepare($chec);
        $btfDD->execute(array($userId,$queId,$quizId));

        if($btfDD->rowCount()==0)
        {
            $sql="INSERT INTO `results`(`user_id`, `question_id`, `quiz_id`, `answer_id`) VALUES (?,?,?,?)";
            $query = $this->db->prepare($sql);
            $query->execute(array($userId,$queId,$quizId,$option));
        }
        else{
            $sql="UPDATE `results`  SET `answer_id`=? WHERE `user_id`=? AND `question_id`=? AND `quiz_id`=?";
            $query = $this->db->prepare($sql);
            $query->execute(array($option,$userId,$queId,$quizId));
        }

        return $query->rowCount();
    }

    public function timerSaver($userId,$t_mer,$quizId)
    {
        $userId=strip_tags($userId);
        $quizId=strip_tags($quizId); 
        $t_mer=strip_tags($t_mer);
        
        $cghcgg=$this->db->prepare("SELECT `id` FROM `user_timer` WHERE `userId`=? AND  `quizId`=? ");
        $cghcgg->execute(array($userId,$quizId));

        if($cghcgg->rowCount()==0)
        {
            $sql="INSERT INTO `user_timer`(`userId`, `t_timer`, `quizId`) VALUES (?,?,?)";
            $query = $this->db->prepare($sql);
            $query->execute(array($userId,$t_mer,$quizId));
            return $query->rowCount();
        }
    }

    public function updateTimer($id,$count,$status="")
    {
        $id=strip_tags($id);
        $count=strip_tags($count); 
        
        $status=!empty($status)?1:0;
        if($status==1){
            $sql="UPDATE `user_timer` SET `t_timer`=`t_timer`-?,`status`=? WHERE `id`=?";
            $query = $this->db->prepare($sql);
            $query->execute(array($count,$status,$id));
        }
        else{
            $sql="UPDATE `user_timer` SET `t_timer`=`t_timer`-? WHERE `id`=?";
            $query = $this->db->prepare($sql);
            $query->execute(array($count,$id));
        }
        return $query->rowCount();
    }

    public function geTimer($userId,$quizId)
    {
        $userId=strip_tags($userId);
        $quizId=strip_tags($quizId);
        
        $query=$this->db->prepare("SELECT `id`,`t_timer`,`status` FROM `user_timer` WHERE `userId`=? AND  `quizId`=? ");
        $query->execute(array($userId,$quizId));
        return $query->fetch();
    }
    
    public function countQuestion($quizId)
    {
        $quizId=strip_tags($quizId);
        
        $sql = "SELECT `id` FROM `questions` WHERE `quiz_id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($quizId));

        return $query->rowCount();
    }
    public function countAtmp($quizId,$userId)
    {
        $quizId=strip_tags($quizId);
        $userId=strip_tags($userId);
        
        $sql = "SELECT DISTINCT(`question_id`) FROM `results` WHERE `quiz_id`=? AND `user_id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($quizId,$userId));

        return $query->rowCount();
    }
    
    public function submitQuiz($myTnId)
    {
        $myTnId=strip_tags($myTnId);
        //$studId=strip_tags($studId);
        
        //echo "UPDATE `user_timer` SET `status`=1 WHERE `userId`=$studId AND `quizId`=$quizId";

        $sql="UPDATE `user_timer` SET `status`=? WHERE `id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array(1,$myTnId));
        
        return $query->rowCount();
    }
    
    public function getCbtResult($studId,$quizId)
    {
        $studId=strip_tags($studId);
        $quizId=strip_tags($quizId);
        
        $sql="SELECT COUNT(a.`user_id`) AS totalScores FROM `results` AS a,`answers` AS b WHERE a.`answer_id`=b.`id` AND a.`user_id`=? AND a.quiz_id=? AND b.`is_correct`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($studId,$quizId,1));

        return $query->fetch();
    }
    
    public function getQuestions($quizId)
    {
        $quizId=strip_tags($quizId); 
        $sql = "SELECT `id`, `question`, `created_at`,`mfile_ext`, `updated_at` FROM `questions` WHERE `quiz_id`=?";
        $query = $this->db->prepare($sql);
        $query->execute(array($quizId));

        return $query->fetchAll();
    }
}
