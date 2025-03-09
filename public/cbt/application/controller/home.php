<?php
class Home extends Controller
{
    public function index()
    {
        //require 'application/views/home/index.php';
        header("location: hhttp://127.0.0.1:8000"); exit();
    }

    public function login($reg="")
    {
        if(empty($reg) || !isset($reg))
        {
            echo "<script type='text/javascript'>";
            echo "alert('Access Denied!');";
            echo "window.location='".URL."home/index'";
            echo "</script>";

            echo "<h3>Access Denied! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }
        
        $reg=base64_decode($reg);
        $login_model = $this->loadModel('LoginModel');
        $logins = $login_model->login($reg);
        
      /*  if($logins->status !=0)
        {
            echo "<script type='text/javascript'>";
            echo "alert('Access Denied, Student Already login in another device!');";
            echo "window.location='".URL."home/index'";
            echo "</script>";

            echo "<h3>Access Denied, Student Already login in another device! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }*/

        $_SESSION['logged_id']=array();
        if(!empty($logins))
        {
            $login_model->updateLogin(1,$reg);
            
            $_SESSION['logged_id'] = array(
                "userId"=>$logins->id,
                "role"=>$logins->is_admin,
                "email"=>$reg,
                "fullname"=>$logins->name,
                "stud_id"=>$logins->stud_id,
                "occupation"=>$logins->occupation,
                "class"=>"SSS ONE",
                "status"=>$logins->status
            );
            //echo $logins->stud_id;
            //echo $_SESSION['logged_id']['stud_id']; exit();
            header("location: ".URL."dashboard/index");
        }
        else{
            //echo "<h1>Invalid Login</h1>";
            header("location: http://127.0.0.1:8000");
        }
    }

    
}
