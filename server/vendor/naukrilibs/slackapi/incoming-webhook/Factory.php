<?php

namespace Naukri\SlackApi\IncomingWebhook;

class Factory
{
    private static $instance;
    private $managerInstance;

    private function __construct() {
        $this->managerInstance = null;
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getManager() {
        if (empty($this->managerInstance)) {
            $this->managerInstance = new Manager();
        }
        return $this->managerInstance;
    }
}
