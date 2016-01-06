<?php

class HeatmapBrowserDo
{
    private $bname;
    private $bid;

    public function getBname() {
        return $this->bname;
    }

    public function setBname($bname) {
        $this->bname = $bname;
    }

    public function getBid() {
        return $this->bid;
    }

    public function setBid($bid) {
        $this->bid = $bid;
    }
}
