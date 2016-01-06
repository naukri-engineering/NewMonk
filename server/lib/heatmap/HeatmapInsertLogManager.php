<?php

class HeatmapInsertLogManager
{
    private $bdbobj;
    private $osdbobj;
    private $rdbobj;
    private $envobj;
    private $pageobj;
    private $dataobj;
    private $tagobj;

    public function __construct($bdbobj,$osdbobj,$rdbobj,$envobj,$pageobj,$dataobj,$tagobj) {
        $this->bdbobj=$bdbobj;
        $this->osdbobj=$osdbobj;
        $this->rdbobj=$rdbobj;
        $this->envobj=$envobj;
        $this->pageobj=$pageobj;
        $this->dataobj=$dataobj;
        $this->tagobj=$tagobj;
    }

    public function insertHeatMapDataInfo($heatMapData) {
        $dataDo = new HeatmapDataDo();
        $dataDo->setEid($this->insertEnvLogInfo($heatMapData));
        $dataDo->setPageid($this->insertPageLogInfo($heatMapData));
        $dataDo->setDate(date('Y-m-d'));
        $this->dataobj->checkCount($dataDo,$heatMapData["coord"]);
    }

    public function insertBrowserInfo($heatMapData) {
        $browserDo = new HeatmapBrowserDo();
        $browserDo->setBname($heatMapData['browser']);
        return $this->bdbobj->insertBrowserLog($browserDo);
    }

    public function insertOsInfo($heatMapData) {
        $osDo = new HeatmapOsDo();
        $osDo->setOsname($heatMapData['os']);
        return $this->osdbobj->insertOsLog($osDo);
    }

    public function insertResolutionInfo($heatMapData) {
        $resolutionDo = new HeatmapResolutionDo();
        $resolutionDo->setResolution($heatMapData["screenRes"]);
        return $this->rdbobj->insertResolutionLog($resolutionDo);
    }

    public function insertEnvLogInfo($heatMapData) {
        $envDo = new HeatmapEnvDo();
        $envDo->setBid($this->insertBrowserInfo($heatMapData));
        $envDo->setOid($this->insertOsInfo($heatMapData));
        $envDo->setRid($this->insertResolutionInfo($heatMapData));
        return $this->envobj->insertEnvLog($envDo);

    }

    public function insertPageLogInfo($heatMapData) {
        $url = (explode('?', $heatMapData["url"]));
        $pageDo = new HeatmapPageDo();
        $tagDo=new HeatmapTagInfoDo();
        $pageDo->setAppid($heatMapData["appId"]);
        if (!$heatMapData["tag"]) {
            $pageDo->setType('URL');
            $pageDo->setUrlTag($url[0]);
            $pageid= $this->pageobj->insertPageLog($pageDo);
        } else {
            $pageDo->setType('TAG');
            $pageDo->setUrlTag($heatMapData["tag"]);
            $tagDo->setUrl($url[0]);
            $pageid= $this->pageobj->insertPageLog($pageDo);
            $tagDo->setPageid($pageid);
            $this->tagobj->insertTagInfo($tagDo);
        }
        return $pageid;
    }
}
