<?php

class HeatmapCoordinateApiDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getAllCoordiante($coordinfo) {
        $st = $this->db->prepare("SELECT x,y,sum(count)as count from data where page_id=:pageid and date BETWEEN :startdate and :enddate group by x,y;");
        $st->bindValue(":pageid",$coordinfo->getPageid());
        $st->bindValue(":startdate",$coordinfo->getStartdate());
        $st->bindValue(":enddate",$coordinfo->getEnddate());
        $st->execute();
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $result;
    }
}
