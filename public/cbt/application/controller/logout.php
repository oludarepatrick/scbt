<?php
class Logout extends Controller
{ 
    public function index()
    {
        $login_model = $this->loadModel('LoginModel');
        $login_model->updateLogin(0, $_SESSION['logged_id']['email']);

        // Call Laravel logout endpoint
        $this->callLaravelLogout();

        // Destroy session
        unset($_SESSION['logged_id']);
        unset($_SESSION['stud_pic']);
        session_destroy();

        // Redirect to home
        header("location: " . qLink."/login");
        exit;
    }

    private function callLaravelLogout()
    {
        $laravelLogoutUrl = qLink."/api/apilogout";
        $token = $_SESSION['auth_token'] ?? ''; 
        $ch = curl_init($laravelLogoutUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token", // Include token if necessary
            "Accept: application/json"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
