<?php

class HeatmapUrlApiDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getAllUrl($urlinfo) {
        if (!$urlinfo->getQuery()) {
            $st = $this->db->prepare("SELECT SQL_CALC_FOUND_ROWS distinct page.page_id,page.url_tag,urltag.url from page left outer join urltag on urltag.page_id=page.page_id inner join data on data.page_id=page.page_id where page.app_id=:appid and data.date between :startdate and :enddate group by urltag.page_id limit :limit offset :offset");
            $st->bindValue(":startdate",$urlinfo->getStartdate());
            $st->bindValue(":enddate",$urlinfo->getEnddate());
            $st->bindValue("appid",$urlinfo->getAppid());
            $st->bindValue("limit",$urlinfo->getcount());
            $st->bindValue("offset",$urlinfo->getOffset());
            $st->execute();
            $resulturl = $st->fetchAll(PDO::FETCH_ASSOC);
            $stFoundRows = $this->db->prepare("select found_rows()");
            $stFoundRows->execute();
            $resultcount = $stFoundRows->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();
            $stFoundRows->closeCursor();
        } else {
            $st = $this->db->prepare("SELECT SQL_CALC_FOUND_ROWS distinct page.page_id,page.url_tag,urltag.url from page left outer join urltag on urltag.page_id=page.page_id inner join data on data.page_id=page.page_id where page.app_id=:appid and page.url_tag like :query and data.date between :startdate and :enddate group by urltag.page_id limit :limit offset :offset");
            $st->bindValue(":startdate",$urlinfo->getStartdate());
            $st->bindValue(":enddate",$urlinfo->getEnddate());
            $st->bindValue("appid",$urlinfo->getAppid());
            $st->bindValue(":query", '%'.$urlinfo->getQuery().'%');
            $st->bindValue("limit",$urlinfo->getcount());
            $st->bindValue("offset",$urlinfo->getOffset());
            $st->execute();
            $resulturl = $st->fetchAll(PDO::FETCH_ASSOC);
            $stFoundRows = $this->db->prepare("SELECT found_rows()");
            $stFoundRows->execute();
            $resultcount=$stFoundRows->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();
            $stFoundRows->closeCursor();
        }

        foreach ($resulturl as $key=>$value) {
            if (!isset($value['url'])) {
                $urls[$key] = array('urlId'=>$value["page_id"],'url'=>$value["url_tag"]);
            } else {
                $urls[$key] = array('urlId'=>$value["page_id"],'tag'=>$value["url_tag"],'url'=>$value['url']);
            }
        }
        foreach ($resultcount as $key=>$value) {
            $totalUrls[$key] = array('totalurls'=>$value["found_rows()"]);
        }

        return ["urls"=>$urls,'totalUrls'=>$totalUrls[0]["totalurls"]];
    }
}
