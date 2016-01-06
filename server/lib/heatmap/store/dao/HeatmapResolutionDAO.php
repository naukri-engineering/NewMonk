<?php

class HeatmapResolutionDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getResolutionInfo($resolutionDO) {
        $st = $this->db->prepare("SELECT resolution_id from resolution where resolution=:resolution");
        $st->bindValue(":resolution",$resolutionDO->getResolution());
        $st->execute();
        $resolutionInfo = $st->fetch(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $resolutionInfo;
    }

    public function insertResolutionLog($resolutionDO) {
        $check = $this->getResolutionInfo($resolutionDO);
        if ($check == NULL) {
            $st = $this->db->prepare('INSERT IGNORE into resolution(resolution)values(:Resolution)');
            $st->bindValue(':Resolution',$resolutionDO->getResolution());
            $st->execute();
            $resolutionId = $this->db->lastInsertId();
            $st->closeCursor();
            if (!$resolutionId) {
                $resolutionDO = $this->getResolutionInfo($resolutionDO);
                $resolutionId = $resolutionDO['resolution_id'];
            }
        } else {
            $resolutionId = $check['resolution_id'];
        }
        return $resolutionId;
    }
}
