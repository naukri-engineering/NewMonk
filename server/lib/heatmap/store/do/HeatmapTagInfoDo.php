<?php

class HeatmapTagInfoDo
{
    private $pageid;
    private $url;

    public function getPageid() {
        return $this->pageid;
    }

    public function setPageid($pageid) {
        $this->pageid = $pageid;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }
}
