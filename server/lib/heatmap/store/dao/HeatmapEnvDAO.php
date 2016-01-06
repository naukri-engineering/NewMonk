<?php

class HeatmapEnvDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getEnvInfo($envDo) {
            $st = $this->db->prepare("SELECT env_id from env where browser_id=:browser_id and resolution_id=:resolution_id and os_id=:os_id");
            $st->bindValue(":browser_id",$envDo->getBid());
            $st->bindValue(":resolution_id",$envDo->getRid());
            $st->bindValue(":os_id",$envDo->getOid());
            $st->execute();
            $envInfo = $st->fetch(PDO::FETCH_ASSOC);
            $st->closeCursor();
            return $envInfo;
    }

    public function insertEnvLog($envDo) {
        $check = $this->getEnvInfo($envDo);
        if ($check == NULL) {
            $st = $this->db->prepare("INSERT IGNORE into env(browser_id,resolution_id,os_id) values(:browser_id,:resolution_id,:os_id)");
            $st->bindValue(":browser_id",$envDo->getBid());
            $st->bindValue(":resolution_id",$envDo->getRid());
            $st->bindValue(":os_id",$envDo->getOid());
            $st->execute();
            $envId = $this->db->lastInsertId();
            $st->closeCursor();
            if (!$envId) {
                $envDO = $this->getEnvInfo($envDO);
                $envId = $envDO['env_id'];
            }
        } else {
            $envId = $check['env_id'];
        }
        return $envId;
    }
}
