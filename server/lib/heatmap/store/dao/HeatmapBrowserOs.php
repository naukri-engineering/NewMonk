<?php

class HeatmapBrowserOs
{
    private $db;
    private $resolutionId;

    public function __construct($db, $resolutionId) {
        $this->db = $db;
        $this->resolutionId = $resolutionId;
    }

    public function getData($startDate, $endDate, $appId) {
        $st = $this->db->prepare("SELECT distinct browser.browser_id as id,name from browser, env, data, page where env.browser_id = browser.browser_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate and env.resolution_id in (".$this->resolutionId.") ");
        $st->bindValue("appid", $appId);
        $st->bindValue("startdate", $startDate);
        $st->bindValue("enddate", $endDate);
        $st->execute();
        $browser = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();

        $stOs = $this->db->prepare("SELECT distinct os.os_id as id,name from os, env, data, page where env.os_id = os.os_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate and env.resolution_id in (".$this->resolutionId.")");
        $stOs->bindValue("appid", $appId);
        $stOs->bindValue("startdate", $startDate);
        $stOs->bindValue("enddate", $endDate);
        $stOs->execute();
        $osname = $stOs->fetchAll(PDO::FETCH_ASSOC);
        $stOs->closeCursor();

        return ["Browser"=>$browser,"Os"=>$osname];
    }
}
