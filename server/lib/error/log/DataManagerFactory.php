<?php

namespace NewMonk\lib\error\log;

use NewMonk\lib\error\dao\ElasticSearchDaoFactory;

class DataManagerFactory
{
    private static $instance;
    private $dataManagers;

    private function __construct() {
        $this->dataManagers = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getDataManager($elasticSearchConfigFilePath) {
        if (empty($this->dataManagers[$elasticSearchConfigFilePath])) {
            $elasticSearchDao = ElasticSearchDaoFactory::getInstance()
                ->getElasticSearchDao($elasticSearchConfigFilePath, 'newmonk_error');

            $this->dataManagers[$elasticSearchConfigFilePath] = new DataManager($elasticSearchDao);
        }
        return $this->dataManagers[$elasticSearchConfigFilePath];
    }
}
