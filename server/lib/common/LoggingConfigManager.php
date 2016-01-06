<?php

namespace NewMonk\lib\common;

class LoggingConfigManager
{
    public function __construct($loggingConfigFilePath, $nLoggerAppsDao) {
        $this->loggingConfig = \ncYaml::load($loggingConfigFilePath);
        $this->nLoggerAppsDao = $nLoggerAppsDao;
    }

    public function getAppAndDomainNames($what) {
        $whitelistedAppIds = $this->getAppIds($what);
        $appsAndDomains = $this->nLoggerAppsDao->getAppsAndDomains();

        $appAndDomainNames = [];
        foreach ($appsAndDomains as $appAndDomain) {
            $isWhitelistedApp = array_search($appAndDomain['app_id'], $whitelistedAppIds) !== false;
            if ($isWhitelistedApp) {
                $appAndDomainNames[$appAndDomain['app_id']] = [
                    'domainId' => $appAndDomain['domain_id'],
                    'domainName' => $appAndDomain['domain_name'],
                    'appId' => $appAndDomain['app_id'],
                    'appName' => $appAndDomain['app_name'],
                ];
            }
        }
        return $appAndDomainNames;
    }

    public function getAppIds($what) {
        return $this->loggingConfig[$what]['appIds'];
    }
}
