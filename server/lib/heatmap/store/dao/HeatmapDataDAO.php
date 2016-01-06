<?php

class HeatmapDataDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function checkCount($dataDo, $coordDo) {
        $l = count($coordDo);
        for ($i=0; $i<$l; $i++) {
            $st = $this->db->prepare("SELECT count from data where page_id=:pageid and x=:x and y=:y and env_id=:eid and date=:date");
            $st->bindValue(":pageid", $dataDo->getPageid());
            $st->bindValue(":x", $coordDo[$i]["x"]);
            $st->bindValue(":y", $coordDo[$i]["y"]);
            $st->bindValue(":eid", $dataDo->getEid());
            $st->bindValue(":date", $dataDo->getDate());
            $st->execute();
            $count = $st->fetchAll(PDO::FETCH_ASSOC);

            if ($count[0]["count"]) {
                $count = $count[0]["count"]+$coordDo[$i]["count"];
                $this->updateDataLog($dataDo,$coordDo,$count,$i);
            } else {
                $this->insertDataLog($dataDo,$coordDo,$i);
            }
        }
    }

    public function insertDataLog($dataDo, $coordDo, $i) {
        $st = $this->db->prepare("INSERT into data(page_id,x,y,env_id,count,date)values(:pageid,:x,:y,:eid,:count,:date)");
        $st->bindValue(":pageid", $dataDo->getPageid());
        $st->bindValue(":x", $coordDo[$i]["x"]);
        $st->bindValue(":y", $coordDo[$i]["y"]);
        $st->bindValue(":eid", $dataDo->getEid());
        $st->bindValue(":count", $coordDo[$i]["count"]);
        $st->bindValue(":date", $dataDo->getDate());
        $st->execute();
        $st->closeCursor();
    }

    public function updateDataLog($dataDo, $coordDo, $count, $i) {
        $st = $this->db->prepare("UPDATE data set count=:count  where page_id=:pageid and x=:x and y=:y and env_id=:eid and date=:date");
        $st->bindValue(":count", $count);
        $st->bindValue(":pageid", $dataDo->getPageid());
        $st->bindValue(":x", $coordDo[$i]["x"]);
        $st->bindValue(":y", $coordDo[$i]["y"]);
        $st->bindValue(":eid", $dataDo->getEid());
        $st->bindValue(":date", $dataDo->getDate());
        $st->execute();
        $st->closeCursor();
    }
}
