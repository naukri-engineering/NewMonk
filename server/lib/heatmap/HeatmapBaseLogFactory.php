<?php

class HeatmapBaseLogFactory
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

    public function getInsertLogManager() {
        $browser = HeatmapBrowserDAOFactory::getInstance()->createDAO("nLogger_heatmap");
        $os = HeatmapOsDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        $resolution = HeatmapResolutionDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        $env = HeatmapEnvDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        $pageinfo = HeatmapPageDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        $coordinates = HeatmapDataDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        $taginfo = HeatmapTagInfoDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        return new HeatmapInsertLogManager($browser, $os, $resolution, $env, $pageinfo, $coordinates, $taginfo);
    }

    public function getUrlLog($browserId, $osId, $resolutionId) {
        if ($browserId || $osId || $resolutionId) {
            $url = HeatmapFilterDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        } else {
            $url = HeatmapUrlApiDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        }
        return new HeatmapUrlLogManager($url);
    }

    public function getCoordinateLog($browserId, $osId, $resolutionId) {
        if ($browserId || $osId || $resolutionId) {
            $coord = HeatmapFilterDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        } else {
            $coord = HeatmapCoordinateApiDAOFactory::getInstance()->createDAO('nLogger_heatmap');
        }
        return new HeatmapCoordinateLogManager($coord);
    }

    public function getFilterLogManager($browserId, $osId, $resolutionId) {
        $db = ncDatabaseManager::getInstance()->getDatabase('nLogger_heatmap')->getConnection();
        $dao = HeatmapFilterDAOFactory::getInstance()->getDAO($db, $browserId, $osId, $resolutionId);
        return new HeatmapFilterLogManager($dao);
    }
}
