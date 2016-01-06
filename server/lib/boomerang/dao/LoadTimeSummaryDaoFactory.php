<?php

namespace NewMonk\lib\boomerang\dao;

class LoadTimeSummaryDaoFactory extends DaoFactory
{
    private static $instance;
    private $loadTimeSummaryDaoInstance;

    private function __construct() {
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getLoadTimeSummaryDao() {
        if (!isset($this->loadTimeSummaryDaoInstance)) {
            $newmonkSummaryDb = $this->getNewMonkSummaryDbConnection();
            $this->loadTimeSummaryDaoInstance = new LoadTimeSummaryDao($newmonkSummaryDb, '\DbUtil');
        }
        return $this->loadTimeSummaryDaoInstance;
    }
}
