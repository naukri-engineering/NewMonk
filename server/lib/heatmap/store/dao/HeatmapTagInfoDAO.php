<?php

class HeatmapTagInfoDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function insertTagInfo($tagDo) {
        $st = $this->db->prepare("INSERT IGNORE into urltag(page_id,url)values(:pageId,:url)");
        $st->bindValue(":pageId",$tagDo->getPageid());
        $st->bindValue(":url",$tagDo->getUrl());
        $st->execute();
        $st->closeCursor();
    }
}
