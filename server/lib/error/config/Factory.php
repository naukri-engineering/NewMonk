<?php

namespace NewMonk\lib\error\config;

class Factory
{
    private static $instance;
    private $configManagers;

    private function __construct() {
        $this->configManagers = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getManager($configFilePath) {
        if (empty($this->configManagers[$configFilePath])) {
            $this->configManagers[$configFilePath] = new Manager($configFilePath);
        }
        return $this->configManagers[$configFilePath];
    }
}
