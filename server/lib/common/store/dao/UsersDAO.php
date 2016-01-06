<?php

class UsersDAO extends NLoggerBaseDAO
{
    private $tableName;
    private $username;
    protected static $instance;

    public function __construct($db) {
        parent::__construct($db);
        $this->tableName = 'users';
    }

    public function doAuthentication($usersDO) {
        if ($username = $this->getUsername($usersDO)) {
            $this->username = $username;
            return $this->username;
        }
        return '';
    }

    public function getUsername($usersDO) {
        if (isset($this->username)) {
            return $this->username;
        } else {
            $sql="SELECT username FROM users WHERE username= :username and password= :password";
            $res = $this->db->prepare($sql);
            $res->bindValue(":username", $usersDO->getUsername(), PDO::PARAM_STR);
            $res->bindValue(":password", $usersDO->getPassword(), PDO::PARAM_STR);
            $res->execute();
            $row = $res->fetch(PDO::FETCH_ASSOC);
            $res->closeCursor();
            return $row["username"];
        }
    }
}
