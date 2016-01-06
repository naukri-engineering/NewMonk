<?php

class HeatmapBrowserOsResolution
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getData($startDate, $endDate, $appId) {
        $stBrowser = $this->db->prepare("SELECT distinct browser.browser_id as id,name from browser, env, data, page where env.browser_id = browser.browser_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate");
        $stBrowser->bindValue("appid", $appId);
        $stBrowser->bindValue("startdate", $startDate);
        $stBrowser->bindValue("enddate", $endDate);
        $stBrowser->execute();
        $browser = $stBrowser->fetchAll(PDO::FETCH_ASSOC);
        $stBrowser->closeCursor();

        $stOs = $this->db->prepare("SELECT distinct os.os_id as id,name from os, env, data, page where env.os_id = os.os_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate");
        $stOs->bindValue("appid", $appId);
        $stOs->bindValue("startdate", $startDate);
        $stOs->bindValue("enddate", $endDate);
        $stOs->execute();
        $os = $stOs->fetchAll(PDO::FETCH_ASSOC);
        $stOs->closeCursor();

        $stResolution = $this->db->prepare("SELECT distinct resolution.resolution_id as id,resolution from resolution, env, data, page where env.resolution_id = resolution.resolution_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate");
        $stResolution->bindValue("appid", $appId);
        $stResolution->bindValue("startdate", $startDate);
        $stResolution->bindValue("enddate", $endDate);
        $stResolution->execute();
        $resolution = $stResolution->fetchAll(PDO::FETCH_ASSOC);
        $stResolution->closeCursor();

        return ["Browser"=>$browser,"Os"=>$os,"Resolution"=>$resolution];
    }
}
