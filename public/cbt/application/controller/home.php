<?php
class Home extends Controller
{
    public function __call($name, $arguments) {
        echo "Method '$name' does not exist!";
        exit();
    }

    public function login($email="")
    {
        echo "okay ".base64_decode($email);
        //require 'application/views/home/index.php';
        //header("location: ".qLink); exit();
    }

    public function index($email="")
    {
        if(empty($email) || !isset($email))
        {
            echo "<script type='text/javascript'>";
            echo "alert('Access Denied!');";
            echo "window.location='".URL."home/index'";
            echo "</script>";

            echo "<h3>Access Denied! <a href='" . URL . "home/index'>click here to continue</a></h3>";
            exit();
        }
        
        $email=base64_decode($email);
        $login_model = $this->loadModel('LoginModel');
        $logins = $login_model->login($email);
        
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
            $login_model->updateLogin(1,$email);
            
            $_SESSION['logged_id'] = array(
                "userId"=>$logins->id,
                "role"=>$logins->is_admin,
                "email"=>$email,
                "fullname"=>$logins->name,
                "stud_id"=>$logins->stud_id,
                "occupation"=>$logins->occupation,
                "class"=>"SSS ONE",
                "status"=>$logins->status
            );
            //echo $logins->stud_id;
            //echo $_SESSION['logged_id']['stud_id']; exit();
            //header("location: ".URL."dashboard/index");
            header("location: ".URL."dashboard/index?url=dashboard/index/");
        }
        else{
            //echo "<h1>Invalid Login</h1>";
            header("location: ".qLink);
        }
    }

    
}
