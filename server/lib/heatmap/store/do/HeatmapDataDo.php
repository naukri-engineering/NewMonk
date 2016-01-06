<?php

class HeatmapDataDo
{
    private $pageid;
    private $x;
    private $y;
    private $eid;
    private $count;
    private $date;

    public function getPageid() {
        return $this->pageid;
    }

    public function setPageid($pageId) {
        $this->pageid = $pageId;
    }

    public function getEid() {
        return $this->eid;
    }

    public function setEid($eId) {
        $this->eid = $eId;
    }

    public function getX() {
        return $this->x;
    }

    public function setX($x) {
        $this->x = $x;
    }

    public function getY() {
        return $this->y;
    }

    public function setY($y) {
        $this->y=$y;
    }

    public function getCount() {
        return $this->count;
    }

    public function setCount($count) {
        $this->count = $count;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}
