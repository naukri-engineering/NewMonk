<?php

class HeatmapBrowserResolution
{
    private $db;
    private $osId;

    public function __construct($db, $osId) {
        $this->db = $db;
        $this->osId = $osId;
    }

    public function getData($startDate, $endDate, $appId) {
        $st = $this->db->prepare("SELECT distinct browser.browser_id as id,name from browser, env, data, page where env.browser_id = browser.browser_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate and env.os_id in (".$this->osId.")");
        $st->bindValue("appid",  $appId);
        $st->bindValue("startdate",  $startDate);
        $st->bindValue("enddate",  $endDate);
        $st->execute();
        $browser = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();

        $stResolution = $this->db->prepare("SELECT distinct resolution.resolution_id as id,resolution from resolution, env, data, page where env.resolution_id = resolution.resolution_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate and env.os_id in (".$this->osId.")");
        $stResolution->bindValue("appid",$appId);
        $stResolution->bindValue("startdate",$startDate);
        $stResolution->bindValue("enddate",$endDate);
        $stResolution->execute();
        $resolution = $stResolution->fetchAll(PDO::FETCH_ASSOC);
        $stResolution->closeCursor();
        return ["Browser"=>$browser,"Resolution"=>$resolution];
    }
}
