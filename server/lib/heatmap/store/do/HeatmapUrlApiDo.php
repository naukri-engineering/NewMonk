<?php

class HeatmapUrlApiDo
{
    private $appid;
    private $startdate;
    private $enddate;
    private $count;
    private $offset;
    private $query;
    private $responseVarName;

    public function getQuery() {
        return $this->query;
    }

    public function setQuery($query) {
        $this->query = $query;
    }


    public function getAppid() {
        return $this->appid;
    }

    public function setAppid($appid) {
        $this->appid = $appid;
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

    public function getCount() {
        return $this->count;
    }

    public function setCount($count) {
        $this->count = $count;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function setOffset($offset) {
        $this->offset = $offset;
    }

    public function getResponseVarName() {
        return $this->responseVarName;
    }

    public function setResponseVarName($responseVarName) {
        $this->responseVarName = $responseVarName;
    }
}
