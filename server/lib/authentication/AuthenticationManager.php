<?php

class AuthenticationManager
{
    private $cookieName;
    private $cookieExpireTime;

    public function __construct($cookieName, $cookieExpireTime) {
        $this->cookieName = $cookieName;
        $this->cookieExpireTime = $cookieExpireTime;
    }

    public function doAuthentication($request) {
        $objUsersDO = new UsersDO();
        $objUsersDO->setUsername($request['username']);
        $objUsersDO->setPassword($this->hashPassword($request['password'], $request['username']));
        $objUsersDAOFactory = UsersDAOFactory::getInstance()->createDAO();
        if ($username = $objUsersDAOFactory->doAuthentication($objUsersDO)) {
            $connection = Crypto::encrypt(implode("|Z|1|Z|", array($username, time())), _Key_New);
            $this->setAuthenticated($connection);
            return true;
        } else {
            return false;
        }
    }

    private function hashPassword($password, $salt) {
        return hash('sha256', $password.$salt);
    }

    public function isAuthenticated($request) {
        $currentTime = time();
        if (isset($request[$this->cookieName])) {
            $connection = $request[$this->cookieName]['CON'];
            $timestamp  = $request[$this->cookieName]['TM'];

            if ($connection && $timestamp) {
                if ($currentTime - $timestamp < $this->cookieExpireTime) {
                    $temp = Crypto::decrypt($connection, _Key_New);
                    list($username) = explode("|Z|1|Z|", $temp);
                    if ($username) {
                        $connection = Crypto::encrypt(implode("|Z|1|Z|", array($username, time())), _Key_New);
                        $this->setAuthenticated($connection);
                        return true;
                    }
                } else {// Timed-out
                    return false;
                }
            } else {// Not Authenticated
                return false;
            }
        }
    }

    public function clearAuthentication() {
        $this->unsetAuthenticate();
    }

    private function unsetAuthenticate() {
        setcookie($this->cookieName."[CON]", null, -1, "/");
        setcookie($this->cookieName."[TM]", null, -1, "/");
    }

    private function setAuthenticated($connection) {
        $currentTime = time();
        $_time = ($currentTime + $this->cookieExpireTime);
        setcookie($this->cookieName."[CON]", $connection, $_time, "/");
        setcookie($this->cookieName."[TM]", $currentTime, $_time, "/");
    }
}
