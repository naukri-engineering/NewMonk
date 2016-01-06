<?php

class HeatmapOsDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getOsInfo($osDO) {
        $st = $this->db->prepare("SELECT os_id from os where name=:name");
        $st->bindValue(":name",$osDO->getOsname());
        $st->execute();
        $osInfo = $st->fetch(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $osInfo;
    }

    public function insertOsLog($osDO) {
        $check = $this->getOsInfo($osDO);
        if ($check == NULL) {
            $st = $this->db->prepare('INSERT IGNORE into os(name)values(:name)');
            $st->bindValue(':name',$osDO->getOsname());
            $st->execute();
            $osId = $this->db->lastInsertId();
            $st->closeCursor();
            if (!$osId) {
                $osDO = $this->getOsInfo($osDO);
                $osId = $osDO['os_id'];
            }
        } else {
            $osId = $check['os_id'];
        }
        return $osId;
    }
}
