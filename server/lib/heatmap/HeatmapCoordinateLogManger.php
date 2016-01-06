<?php

class HeatmapCoordinateLogManager
{
    private $coord;

    public function __construct($coord) {
        $this->coord = $coord;
    }

    public function getCoordinateInfo($formdata,$browserId,$osId,$resolutionId) {
        $coordinfo = new HeatmapCoordinateApiDo();
        $coordinfo->setPageid($formdata["urlId"]);
        $coordinfo->setEnddate($formdata["endDate"]);
        $coordinfo->setStartdate($formdata["startDate"]);
        $coordinfo->setResponseVarName($formdata["callBack"]);
        if ($browserId || $osId || $resolutionId) {
            $result = $this->coord->getAllCoordiante($formdata);
        } else {
            $result = $this->coord->getAllCoordiante($coordinfo);
        }
        return $result;
    }
}
