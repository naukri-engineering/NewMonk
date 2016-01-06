<?php

class CDNHeaders
{
    private static $instance;

    /**
     * Retrieve the singleton instance of this class.
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {

    }

    /**
     * Retrieve the IP address of the client
     */
    public function getRemoteIP() {
        return getenv('HTTP_TRUE_CLIENT_IP') ? getenv('HTTP_TRUE_CLIENT_IP') : getenv('REMOTE_ADDR');
    }
}
