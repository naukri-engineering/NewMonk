<?php

namespace NewMonk\lib\boomerang\sla;

class ConfigManager
{
    public function __construct($slaConfigFilePath) {
        $this->slaConfig = \ncYaml::load($slaConfigFilePath);
    }

    public function getConfig($appId) {
        return $this->slaConfig[$appId];
    }

    public function getGlobalConfig() {
        return $this->slaConfig['global'];
    }
}
