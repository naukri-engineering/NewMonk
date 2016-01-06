<?php

namespace NewMonk\lib\error\log;

class DataManager
{
    private $elasticSearchDao;

    public function __construct($elasticSearchDao) {
        $this->elasticSearchDao = $elasticSearchDao;
    }

    public function save($errorLogs) {
        $this->elasticSearchDao->save($errorLogs);
    }
}
