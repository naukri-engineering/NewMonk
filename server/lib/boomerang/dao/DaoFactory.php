<?php

namespace NewMonk\lib\boomerang\dao;

class DaoFactory
{
    protected function getNewMonkSummaryDbConnection() {
        $newmonkSummaryDb = \ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
        $newmonkSummaryDb->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $newmonkSummaryDb->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        return $newmonkSummaryDb;
    }
}
