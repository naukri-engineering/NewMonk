<?php

class HeatmapEnvDo
{
    private $bid;
    private $rid;
    private $oid;
    private $eid;

    public function getBid() {
        return $this->bid;
    }

    public function setBid($bId) {
        $this->bid = $bId;
    }

    public function getOid() {
        return $this->oid;
    }

    public function setOid($oId) {
        $this->oid = $oId;
    }

    public function getRid() {
        return $this->rid;
    }

    public function setRid($rId) {
        $this->rid = $rId;
    }

    public function getEid() {
        return $this->eid;
    }

    public function setEid($eId) {
        $this->eid = $eId;
    }
}
