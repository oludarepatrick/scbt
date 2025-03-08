<?php
class LoginModel
{
    private $db; // ✅ Explicitly declare the property

    /**
     * Every model needs a database connection, passed to the model
     * @param object $db A PDO database connection
     */
    public function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function login($email)
    {
        $email = strip_tags($email);
        $sql = "SELECT `id`, `name`, `email`, `email_verified_at`, `password`, `visible_password`, `occupation`, `address`, `phone`, `is_admin`, `remember_token`, `created_at`, `updated_at`, `stud_id`, `status` FROM `users` WHERE `email`=?";
        $query = $this->db->prepare($sql);
        $query->execute([$email]);

        return $query->fetch();
    }
    
    public function updateLogin($status, $email)
    {
        $email = strip_tags($email);
        $status = strip_tags($status);

        $sql = "UPDATE `users` SET `status`=? WHERE `email`=?";
        $query = $this->db->prepare($sql);
        $query->execute([$status, $email]);

        return $query->rowCount();
    }
}
