<?php

namespace NewMonk\lib\boomerang\dao;

class PageDaoFactory extends DaoFactory
{
    private static $instance;
    private $pageDaoInstance;

    private function __construct() {
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getPageDao() {
        if (!isset($this->pageDaoInstance)) {
            $newmonkSummaryDb = $this->getNewMonkSummaryDbConnection();
            $this->pageDaoInstance = new PageDao($newmonkSummaryDb, '\DbUtil');
        }
        return $this->pageDaoInstance;
    }
}
