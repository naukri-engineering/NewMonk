<?php

class HeatmapUrlLogManager
{
    private $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public function getUrlInfo($formdata,$browserId,$osId,$resolutionId) {
        $urlinfo = new HeatmapUrlApiDo();
        $urlinfo->setAppid($formdata["appId"]);
        $urlinfo->setCount($formdata["count"]);
        $urlinfo->setEnddate($formdata["endDate"]);
        $urlinfo->setOffset($formdata["offset"]);
        $urlinfo->setStartdate($formdata["startDate"]);
        $urlinfo->setResponseVarName($formdata["callBack"]);
        $urlinfo->setQuery($formdata["query"]);
        if ($browserId || $osId || $resolutionId) {
            $result = $this->url->getAllUrl($formdata);
        } else {
            $result = $this->url->getAllUrl($urlinfo);
        }
        return $result;
    }
}
