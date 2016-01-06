<?php

class HeatmapOs
{
    private $db;
    private $browserId;
    private $resolutionId;

    public function __construct($db, $browserId, $resolutionId) {
        $this->db = $db;
        $this->browserId = $browserId;
        $this->resolutionId = $resolutionId;

    }

    public function getData($startDate, $endDate, $appId) {
        $st = $this->db->prepare("SELECT distinct os.os_id as id,name from os INNER JOIN(env) on env.os_id = os.os_id and env.env_id IN(select env_id from data INNER JOIN (page) on data.page_id=page.page_id and app_id=:appid and date between :startdate and :enddate) and env.resolution_id in (".$this->resolutionId.") and env.browser_id in (".$this->browserId.");");
        $st->bindValue("appid",$appId);
        $st->bindValue("startdate",$startDate);
        $st->bindValue("enddate",$endDate);
        $st->execute();
        $osname = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return ["Os"=>$osname];
    }
}
