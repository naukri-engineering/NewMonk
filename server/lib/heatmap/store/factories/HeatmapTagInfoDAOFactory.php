<?php

class HeatmapTagInfoDAOFactory extends HeatmapDAOFactory
{
    protected static $instance;

    protected function __construct() {
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    public function createDAO($dbName) {
        $conn = $this->createDatabaseConnection($dbName);
        return new HeatmapTagInfoDAO($conn);
    }
}
