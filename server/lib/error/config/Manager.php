<?php

namespace NewMonk\lib\error\config;

class Manager
{
    private $config;

    public function __construct($configFilePath) {
        $this->config = \ncYaml::load($configFilePath);
    }

    public function getConfigFor($type) {
        return $this->config[$type];
    }
}
