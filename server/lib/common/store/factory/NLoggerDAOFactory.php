<?php

abstract class NLoggerDAOFactory
{
    abstract public function createDAO($dbName);

    protected function createDatabaseConnection($dbName) {
        if ($dbName == null) {
            $dbName = 'nLogger';
        }

        $db = ncDatabaseManager::getInstance()->getDatabase($dbName)->getConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

        $sql = 'SET NAMES utf8';
        $st = $db->prepare($sql);
        $st->execute();
        $st->closeCursor();

        return $db;
    }
}
