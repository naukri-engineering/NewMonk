<?php

class HeatmapPageDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getPageinfo($pageDo) {
        $st = $this->db->prepare("SELECT page_id from page where app_id=:appId and checksum=:checksum");
        $st->bindValue(":appId",$pageDo->getAppid());
        $st->bindValue(":checksum",$pageDo->getUtCs());
        $st->execute();
        $pageinfo = $st->fetch(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $pageinfo;
    }

    public function insertPageLog($pageDo) {
        $pageDo->setUtCs(md5($pageDo->getUrlTag()));
        $check = $this->getPageinfo($pageDo);
        if ($check == NULL) {
            $st = $this->db->prepare("INSERT IGNORE into page(app_id,url_tag,type,checksum)values(:appId,:url_tag,:type,:ut_cs)");
            $st->bindValue(":appId",$pageDo->getAppid());
            $st->bindValue(":url_tag",$pageDo->getUrlTag());
            $st->bindValue(":type",$pageDo->getType());
            $st->bindValue(":ut_cs",$pageDo->getUtCs());
            $st->execute();
            $pageid = $this->db->lastInsertId();
            $st->closeCursor();
            if (!$pageid) {
                $pageDo = $this->getPageinfo($pageDo);
                $pageid = $pageDo['page_id'];
            }
        } else {
            $pageid = $check['page_id'];
        }
        return $pageid;
    }
}
