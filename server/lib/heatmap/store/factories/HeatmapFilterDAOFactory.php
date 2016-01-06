<?php

class HeatmapFilterDAOFactory extends HeatmapDAOFactory
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
        return new HeatmapFilterDAO($conn);
    }

    public function getDAO($db, $browserId, $osId, $resolutionId) {
        if ($browserId) {
            if ($osId) {
                if (!$resolutionId) {
                    return new HeatmapResolution($db, $browserId, $osId);
                }
            } else {
                if ($resolutionId) {
                    return new HeatmapOs($db, $browserId, $resolutionId);
                } else {
                    return new HeatmapOsResolution($db, $browserId);
                }
            }
        } else {
            if ($osId) {
                if ($resolutionId) {
                    return new HeatmapBrowser($db, $osId, $resolutionId);
                } else {
                    return new HeatmapBrowserResolution($db, $osId);
                }
            } else {
                if ($resolutionId) {
                    return new HeatmapBrowserOs($db, $resolutionId);
                } else {
                    return new HeatmapBrowserOsResolution($db);
                }
            }
        }
    }
}
