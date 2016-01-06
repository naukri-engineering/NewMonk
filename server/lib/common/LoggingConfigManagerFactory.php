<?php

namespace NewMonk\lib\common;

class LoggingConfigManagerFactory
{
    private static $instance;
    private $loggingConfigManagers;

    private function __construct() {
        $this->loggingConfigManagers = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getLoggingConfigManager($loggingConfigFilePath) {
        if (empty($this->loggingConfigManagers[$loggingConfigFilePath])) {
            $nLoggerAppsDao = \NLoggerAppsDAOFactory::getInstance()->createDAO('nLogger');
            $this->loggingConfigManagers[$loggingConfigFilePath] = new LoggingConfigManager(
                $loggingConfigFilePath,
                $nLoggerAppsDao
            );
        }
        return $this->loggingConfigManagers[$loggingConfigFilePath];
    }
}
