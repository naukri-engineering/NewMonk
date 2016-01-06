<?php

class HeatmapOsResolution
{
    private $db;
    private $browserId;

    public function __construct($db, $browserId) {
        $this->db = $db;
        $this->browserId = $browserId;
    }

    public function getData($startDate, $endDate, $appId) {
        $st = $this->db->prepare("SELECT distinct os.os_id as id,name from os INNER JOIN(env) on env.os_id = os.os_id and env.env_id IN(select env_id from data INNER JOIN (page) on data.page_id=page.page_id and app_id=:appid and date between :startdate and :enddate) and env.browser_id in (".$this->browserId.");");
        $st->bindValue("appid",$appId);
        $st->bindValue("startdate",$startDate);
        $st->bindValue("enddate",$endDate);
        $st->execute();
        $osname = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();

        $stResolution = $this->db->prepare("SELECT distinct resolution.resolution_id as id,resolution from resolution INNER JOIN(env) on env.resolution_id = resolution.resolution_id and env.env_id IN(select env_id from data INNER JOIN (page) on data.page_id=page.page_id and app_id=:appid and date between :startdate and :enddate) and env.browser_id in (".$this->browserId."); ");
        $stResolution->bindValue("appid",$appId);
        $stResolution->bindValue("startdate",$startDate);
        $stResolution->bindValue("enddate",$endDate);
        $stResolution->execute();
        $resolution = $stResolution->fetchAll(PDO::FETCH_ASSOC);
        $stResolution->closeCursor();

        return ["Os"=>$osname,"Resolution"=>$resolution];
    }
}
