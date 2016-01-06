<?php

class HeatmapResolution
{
    private $db;
    private $browserId;
    private $osId;

    public function __construct($db, $browserId, $osId) {
        $this->db=$db;
        $this->browserId=$browserId;
        $this->osId=$osId;
    }

    public function getData($startDate, $endDate, $appId) {
        $st=$this->db->prepare("SELECT distinct resolution.resolution_id as id,resolution from resolution INNER JOIN(env) on env.resolution_id = resolution.resolution_id and env.env_id IN(select env_id from data INNER JOIN (page) on data.page_id=page.page_id and app_id=:appid and date between :startdate and :enddate) and env.os_id in (".$this->osId.") and env.browser_id in (".$this->browserId.");");
        $st->bindValue("appid",$appId);
        $st->bindValue("startdate",$startDate);
        $st->bindValue("enddate",$endDate);
        $st->execute();
        $resolution = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return array("Resolution"=>$resolution);
    }
}
