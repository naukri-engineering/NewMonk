<?php

namespace NewMonk\lib\boomerang\sla;

use Naukri\SlackApi\IncomingWebhook\Factory as SlackApiIncomingWebhookFactory;
use NewMonk\lib\boomerang\dao\PageDaoFactory;
use NewMonk\lib\boomerang\dao\LoadTimeSummaryDaoFactory;
use NewMonk\lib\common\LoggingConfigManagerFactory;
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

    public function getBreachManager($slaConfigFilePath, $loggingConfigFilePath, $logDirPath) {
        $slaConfigManager = new ConfigManager($slaConfigFilePath);
        $loggingConfigManager = LoggingConfigManagerFactory::getInstance()
            ->getLoggingConfigManager($loggingConfigFilePath);
        $pageDao = PageDaoFactory::getInstance()->getPageDao();
        $loadTimeSummaryDao = LoadTimeSummaryDaoFactory::getInstance()->getLoadTimeSummaryDao();
        $slackApiIncomingWebhookManager = SlackApiIncomingWebhookFactory::getInstance()->getManager();
        $activityLogger = LoggerFactory::getInstance()->getFileLogger('BoomSlaBreachManager', $logDirPath);

        return new BreachManager(
            $slaConfigManager,
            $loggingConfigManager,
            $pageDao,
            $loadTimeSummaryDao,
            $slackApiIncomingWebhookManager,
            $activityLogger
        );
    }
}
