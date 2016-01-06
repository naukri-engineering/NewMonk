<?php

class HeatmapPageDo
{
    private $urlTag;
    private $pageid;
    private $type;
    private $appid;
    private $utCs;

    public function getUrlTag() {
        return $this->urlTag;
    }

    public function setUrlTag($urlTag) {
        $this->urlTag = $urlTag;
    }

    public function getPageid() {
        return $this->pageid;
    }

    public function setPageid($pageId) {
        $this->pageid = $pageId;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getAppid() {
        return $this->appid;
    }

    public function setAppid($appId) {
        $this->appid = $appId;
    }

    public function getUtCs() {
        return $this->utCs;
    }

    public function setUtCs($utCs) {
        $this->utCs = $utCs;
    }
}
