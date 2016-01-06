<?php

class HeatmapBrowser
{
    private $db;
    private $resolutionId;
    private $osId;

    public function __construct($db, $osId, $resolutionId) {
        $this->db = $db;
        $this->resolutionId = $resolutionId;
        $this->osId = $osId;
    }

    public function getData($startDate, $endDate, $appId) {
        $st = $this->db->prepare("SELECT distinct browser.browser_id as id,name from browser, env, data, page where env.browser_id = browser.browser_id and env.env_id = data.env_id and data.page_id=page.page_id and page.app_id=:appid and data.date between :startdate and :enddate and env.os_id in (".$this->osId.") and env.resolution_id in (".$this->resolutionId.")");
        $st->bindValue("appid",  $appId);
        $st->bindValue("startdate",$startDate);
        $st->bindValue("enddate",$endDate);
        $st->execute();
        $browser = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return array("Browser"=>$browser);
    }
}
