<?php

namespace NewMonk\lib\common\error;

use NewMonk\lib\util\LoggerFactory;

class HandlerFactory
{
    private static $instance;
    private $errorHandlers;

    private function __construct() {
        $this->errorHandlers = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getErrorHandler($logDirPath) {
        if (empty($this->errorHandlers[$logDirPath])) {
            $formatter = new \League\BooBoo\Formatter\NullFormatter();
            $logSaver = LoggerFactory::getInstance()->getFileLogger('Error', $logDirPath);
            $handler = new \League\BooBoo\Handler\LogHandler(new LoggerAndDisplayer($logSaver));

            $this->errorHandlers[$logDirPath] = new Handler(
                new \League\BooBoo\Runner([$formatter], [$handler])
            );
        }
        return $this->errorHandlers[$logDirPath];
    }
}
