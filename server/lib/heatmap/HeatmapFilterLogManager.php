<?php

class HeatmapFilterLogManager
{
    private $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    public function getData($startDate,$endDate,$appId) {
        $res = $this->dao->getData($startDate,$endDate,$appId);
        return $res;
    }
}
