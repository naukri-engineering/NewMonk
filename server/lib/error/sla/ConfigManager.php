<?php

namespace NewMonk\lib\error\sla;

class ConfigManager
{
    private $slaConfig;

    public function __construct($errorConfigManager) {
        $this->slaConfig = $errorConfigManager->getConfigFor('sla');
    }

    public function getConfig($appId) {
        return $this->slaConfig[$appId];
    }

    public function getGlobalConfig() {
        return $this->slaConfig['global'];
    }
}
