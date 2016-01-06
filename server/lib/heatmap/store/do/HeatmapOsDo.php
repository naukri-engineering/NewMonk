<?php

class HeatmapOsDo
{
    private $osname;
    private $oid;

    public function getOsname() {
        return $this->osname;
    }

    public function setOsname($osName) {
        $this->osname = $osName;
    }

    public function getOid() {
        return $this->oid;
    }

    public function setOid($oId) {
        $this->oid = $oId;
    }
}
