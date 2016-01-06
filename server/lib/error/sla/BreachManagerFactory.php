<?php

namespace NewMonk\lib\error\sla;

use Naukri\SlackApi\IncomingWebhook\Factory as SlackApiIncomingWebhookFactory;
use NewMonk\lib\common\LoggingConfigManagerFactory;
use NewMonk\lib\error\config\Factory as ErrorConfigManagerFactory;
use NewMonk\lib\error\dao\ElasticSearchDaoFactory;
use NewMonk\lib\util\LoggerFactory;

class BreachManagerFactory
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

    public function getBreachManager(
        $errorConfigFilePath,
        $loggingConfigFilePath,
        $elasticSearchConfigFilePath,
        $logDirPath
    ) {
        $errorConfigManager = ErrorConfigManagerFactory::getInstance()->getManager($errorConfigFilePath);
        $slaConfigManager = new ConfigManager($errorConfigManager);

        $loggingConfigManager = LoggingConfigManagerFactory::getInstance()
            ->getLoggingConfigManager($loggingConfigFilePath);
        $elasticSearchDao = ElasticSearchDaoFactory::getInstance()
            ->getElasticSearchDao($elasticSearchConfigFilePath, 'newmonk_error');
        $slackApiIncomingWebhookManager = SlackApiIncomingWebhookFactory::getInstance()->getManager();
        $activityLogger = LoggerFactory::getInstance()->getFileLogger('ErrorSlaBreachManager', $logDirPath);

        return new BreachManager(
            $slaConfigManager,
            $loggingConfigManager,
            $elasticSearchDao,
            $slackApiIncomingWebhookManager,
            $activityLogger
        );
    }
}
