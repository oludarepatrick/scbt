<?php
if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}

class Controller
{
    /**
     * @var null|PDO Primary Database Connection
     */
    public $db = null;

    /**
     * @var null|PDO Secondary Database Connection
     */
    public $db2 = null; // ✅ Explicitly declare the second database connection

    function __construct()
    {
        $this->openDatabaseConnection();
    }

    private function openDatabaseConnection()
    {
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, 
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ];

        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);

        $this->db2 = new PDO(DB_TYPE_2 . ':host=' . DB_HOST_2 . ';dbname=' . DB_NAME_2, DB_USER_2, DB_PASS_2, $options);
    }

    public function loadModel($model_name)
    {
        require 'application/models/' . strtolower($model_name) . '.php';
        return new $model_name($this->db);
    }

    public function loadModel2($model_name2)
    {
        require 'application/models/' . strtolower($model_name2) . '.php';
        return new $model_name2($this->db2);
    }
}
