<?php

namespace NewMonk\lib\error\dao;

class ElasticSearchDaoFactory
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

    public function getElasticSearchDao($configFilePath, $nodeName) {
        $config = $this->readConfigFromFile($configFilePath, $nodeName);
        $elasticaClient = new \Elastica\Client(
            [
                'host' => $config['host'],
                'port' => $config['port']
            ]
        );
        $elasticaIndex = $elasticaClient->getIndex($config['index']);
        $elasticaType = $elasticaIndex->getType($config['type']);
        return new ElasticSearchDao($elasticaType);
    }

    private function readConfigFromFile($configFilePath, $nodeName) {
        $config = \ncYaml::load($configFilePath)[$nodeName];
        if (empty($config)) {
            throw new ElasticSearchDaoException("Could not load configuration for '$nodeName' from '$configFilePath'");
        }
        return $config;
    }
}
