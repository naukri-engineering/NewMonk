<?php

namespace NewMonk\lib\error\log;

use NewMonk\lib\error\log\middleware\ChainFactory as MiddlewareChainFactory;
use NewMonk\lib\error\log\validator\Factory as ValidatorFactory;
use NewMonk\lib\error\logger\Factory as ErrorLoggerFactory;
use NewMonk\lib\util\LoggerFactory;

class QueueProcessorFactory
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

    public function getQueueProcessor($errorConfigFilePath, $elasticSearchConfigFilePath, $logDirPath) {
        $queueErrorLogger = ErrorLoggerFactory::getInstance()->getErrorLogger('queue');
        $validatorFactory = ValidatorFactory::getInstance();
        $middlewareChain = MiddlewareChainFactory::getInstance()->getChain($errorConfigFilePath);
        $logBuilder = new Builder();
        $dataManager = DataManagerFactory::getInstance()->getDataManager($elasticSearchConfigFilePath);
        $activityLogger = LoggerFactory::getInstance()->getFileLogger('ErrorLogProcessor', $logDirPath);

        return new QueueProcessor(
            $queueErrorLogger,
            $validatorFactory,
            $middlewareChain,
            $logBuilder,
            $dataManager,
            $activityLogger
        );
    }
}
