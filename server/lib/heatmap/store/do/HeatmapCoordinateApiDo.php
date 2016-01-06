<?php

class HeatmapCoordinateApiDo
{
    private $pageid;
    private $startdate;
    private $enddate;
    private $responsevarname;

    public function getPageid() {
        return $this->pageid;
    }

    public function setPageid($pageid) {
        $this->pageid = $pageid;
    }

    public function getStartdate() {
        return $this->startdate;
    }

    public function setStartdate($startdate) {
        $this->startdate = $startdate;
    }

    public function getEnddate() {
        return $this->enddate;
    }

    public function setEnddate($enddate) {
        $this->enddate = $enddate;
    }

    public function getResponsevarname() {
        return $this->responsevarname;
    }

    public function setResponsevarname($responsevarname) {
        $this->responsevarname = $responsevarname;
    }
}
