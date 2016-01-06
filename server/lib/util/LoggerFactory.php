<?php

namespace NewMonk\lib\util;

class LoggerFactory
{
    private static $instance;

    private function __construct() {

    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getFileLogger($name, $logDirPath, $options = []) {
        return new FileLogger($name, $logDirPath, $options);
    }
}
