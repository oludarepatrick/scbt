<?php

class SessionModel
{
    private $db;
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

    public function activeTermSession()
    {
        
        $sql = "SELECT `session`,`term` FROM `schinfo` WHERE 1";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetch();
    }
    
}
