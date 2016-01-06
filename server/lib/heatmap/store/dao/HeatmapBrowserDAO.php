<?php

class HeatmapBrowserDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getBrowserInfo($browserDO) {
        $st = $this->db->prepare("SELECT browser_id from browser where name=:browsername");
        $st->bindValue(":browsername",$browserDO->getBname());
        $st->execute();
        $browserInfo = $st->fetch(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $browserInfo;
    }

    public function insertBrowserLog($browserDO) {
        $check = $this->getBrowserInfo($browserDO);
        if ($check == NULL) {
            $st = $this->db->prepare('INSERT IGNORE into browser(name)values(:browsername)');
            $st->bindValue(':browsername',$browserDO->getBname());
            $st->execute();
            $browserId = $this->db->lastInsertId();
            $st->closeCursor();
            if (!$browserId) {
                $browserDO = $this->getBrowserInfo($browserDO);
                $browserId = $browserDO['browser_id'];
            }
        } else {
            $browserId = $check['browser_id'];
        }
        return $browserId;
    }
}
