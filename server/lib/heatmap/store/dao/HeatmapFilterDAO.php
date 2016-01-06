<?php

class HeatmapFilterDAO extends HeatmapBaseDAO
{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getAllUrl($urlinfo) {
        if (($urlinfo['browserId'])) {
            $browser = implode("," , $urlinfo['browserId']);
            if ($urlinfo['osId']) {
                if ($urlinfo['resolutionId']) {
                    $resolution = implode("," , $urlinfo['resolutionId']);
                    $osnames = implode("," , $urlinfo['osId']);
                    $st = $this->db->prepare("SELECT distinct page.page_id,page.url_tag,urltag.url from page inner join data on page.page_id in (SELECT distinct  page_id from data INNER JOIN env on data.env_id in(SELECT env_id from env INNER JOIN (os,browser,resolution) on (env.os_id in (".$osnames.") and env.browser_id in (".$browser.") and env.resolution_id in (".$resolution."))) and date between :startdate and :enddate) left outer join urltag on urltag.page_id=page.page_id where page.app_id =:appid group by urltag.page_id limit :limit offset :offset;");
                    $st->bindValue(":startdate",$urlinfo['startDate']);
                    $st->bindValue(":enddate",$urlinfo['endDate']);
                    $st->bindValue(":appid",$urlinfo['appId']);
                    $st->bindValue(":limit",$urlinfo['count']);
                    $st->bindValue(":offset",$urlinfo['offset']);
                    $st->execute();
                } else {
                    $osnames = implode("," , $urlinfo['osId']);
                    $st = $this->db->prepare("SELECT distinct page.page_id,page.url_tag,urltag.url from page inner join data on page.page_id in (SELECT distinct  page_id from data INNER JOIN env on data.env_id in(SELECT env_id from env INNER JOIN (os,browser) on (env.os_id in (".$osnames.") and env.browser_id in (".$browser."))) and date between :startdate and :enddate) left outer join urltag on urltag.page_id=page.page_id where page.app_id =:appid group by urltag.page_id limit :limit offset :offset;");
                    $st->bindValue(":startdate",$urlinfo['startDate']);
                    $st->bindValue(":enddate",$urlinfo['endDate']);
                    $st->bindValue(":appid",$urlinfo['appId']);
                    $st->bindValue(":limit",$urlinfo['count']);
                    $st->bindValue(":offset",$urlinfo['offset']);
                    $st->execute();
                }
            } else {
                if ($urlinfo['resolutionId']) {
                    $resolution=implode("," , $urlinfo['resolutionId']);
                    $st=$this->db->prepare("SELECT distinct page.page_id,page.url_tag,urltag.url from page inner join data on page.page_id in (SELECT distinct  page_id from data INNER JOIN env on data.env_id in(SELECT env_id from env INNER JOIN (browser,resolution) on (env.browser_id in (".$browser.") and env.resolution_id in (".$resolution."))) and date between :startdate and :enddate) left outer join urltag on urltag.page_id=page.page_id where page.app_id =:appid group by urltag.page_id limit :limit offset :offset;");
                    $st->bindValue(":startdate",$urlinfo['startDate']);
                    $st->bindValue(":enddate",$urlinfo['endDate']);
                    $st->bindValue(":appid",$urlinfo['appId']);
                    $st->bindValue(":limit",$urlinfo['count']);
                    $st->bindValue(":offset",$urlinfo['offset']);
                    $st->execute();
                } else {
                    $st = $this->db->prepare("SELECT distinct page.page_id,page.url_tag,urltag.url from page inner join data on page.page_id in (SELECT distinct  page_id from data INNER JOIN env on data.env_id in(SELECT env_id from env INNER JOIN (browser) on (env.browser_id in (".$browser."))) and date between :startdate and :enddate) left outer join urltag on urltag.page_id=page.page_id where page.app_id =:appid group by urltag.page_id limit :limit offset :offset;");
                    $st->bindValue(":startdate",$urlinfo['startDate']);
                    $st->bindValue(":enddate",$urlinfo['endDate']);
                    $st->bindValue(":appid",$urlinfo['appId']);
                    $st->bindValue(":limit",$urlinfo['count']);
                    $st->bindValue(":offset",$urlinfo['offset']);
                    $st->execute();
                }
            }
        } else {
            if ($urlinfo['osId']) {
                if ($urlinfo['resolutionId']) {
                    $resolution = implode("," , $urlinfo['resolutionId']);
                    $osnames = implode("," , $urlinfo['osId']);
                    $st = $this->db->prepare("SELECT distinct page.page_id,page.url_tag,urltag.url from page inner join data on page.page_id in (SELECT distinct  page_id from data INNER JOIN env on data.env_id in(SELECT env_id from env INNER JOIN (os,resolution) on (env.os_id in (".$osnames.") and env.resolution_id in (".$resolution."))) and date between :startdate and :enddate) left outer join urltag on urltag.page_id=page.page_id where page.app_id =:appid group by urltag.page_id limit :limit offset :offset;");
                    $st->bindValue(":startdate",$urlinfo['startDate']);
                    $st->bindValue(":enddate",$urlinfo['endDate']);
                    $st->bindValue(":appid",$urlinfo['appId']);
                    $st->bindValue(":limit",$urlinfo['count']);
                    $st->bindValue(":offset",$urlinfo['offset']);
                    $st->execute();
                } else {
                    $osnames = implode("," , $urlinfo['osId']);
                    $st = $this->db->prepare("SELECT distinct page.page_id,page.url_tag,urltag.url from page inner join data on page.page_id in (SELECT distinct  page_id from data INNER JOIN env on data.env_id in(SELECT env_id from env INNER JOIN (os) on (env.os_id in (".$osnames."))) and date between :startdate and :enddate) left outer join urltag on urltag.page_id=page.page_id where page.app_id =:appid group by urltag.page_id limit :limit offset :offset;");
                    $st->bindValue(":startdate",$urlinfo['startDate']);
                    $st->bindValue(":enddate",$urlinfo['endDate']);
                    $st->bindValue(":appid",$urlinfo['appId']);
                    $st->bindValue(":limit",$urlinfo['count']);
                    $st->bindValue(":offset",$urlinfo['offset']);
                    $st->execute();
                }
            } else {
                if ($urlinfo['resolutionId']) {
                    $resolution = implode("," , $urlinfo['resolutionId']);
                    $st = $this->db->prepare("SELECT distinct page.page_id,page.url_tag,urltag.url from page inner join data on page.page_id in (SELECT distinct  page_id from data INNER JOIN env on data.env_id in(SELECT env_id from env INNER JOIN (resolution) on (env.resolution_id in (".$resolution."))) and date between :startdate and :enddate) left outer join urltag on urltag.page_id=page.page_id where page.app_id =:appid group by urltag.page_id limit :limit offset :offset;");
                    $st->bindValue(":startdate",$urlinfo['startDate']);
                    $st->bindValue(":enddate",$urlinfo['endDate']);
                    $st->bindValue(":appid",$urlinfo['appId']);
                    $st->bindValue(":limit",$urlinfo['count']);
                    $st->bindValue(":offset",$urlinfo['offset']);
                    $st->execute();
                }

            }
        }
        $resulturl = $st->fetchAll(PDO::FETCH_ASSOC);
        $stFoundRows = $this->db->prepare("SELECT found_rows() as totalurls");
        $stFoundRows->execute();
        $totalUrls = $stFoundRows->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resulturl as $key=>$value) {
            if (!isset($value['url'])) {
                $urls[$key]=array('urlId'=>$value["page_id"],'url'=>$value["url_tag"]);
            } else {
                $urls[$key]=array('urlId'=>$value["page_id"],'tag'=>$value["url_tag"],'url'=>$value['url']);
            }
        }
        return ["urls"=>$urls,'totalUrls'=>$totalUrls[0]["totalurls"]];
    }

    public function getAllCoordiante($coordinfo) {
        if (($coordinfo['browserId'])) {
            $browser = implode("," , $coordinfo['browserId']);
            if ($coordinfo['osId']) {
                if ($coordinfo['resolutionId']) {
                    $resolution=implode("," , $coordinfo['resolutionId']);
                    $osnames=implode("," , $coordinfo['osId']);
                    $st=$this->db->prepare("SELECT x,y,sum(count)as count from data where page_id =:page_id and env_id in (select env_id from env where browser_id in(".$browser.") and os_id in (".$osnames.")and resolution_id in (".$resolution.")) and date BETWEEN :startdate and :enddate group by x,y;");
                    $st->bindValue(":page_id",$coordinfo['urlId']);
                    $st->bindValue(":startdate",$coordinfo['startDate']);
                    $st->bindValue(":enddate",$coordinfo['endDate']);
                    $st->execute();
                } else {
                    $osnames = implode("," , $coordinfo['osId']);
                    $st = $this->db->prepare("SELECT x,y,sum(count)as count from data where page_id =:page_id and env_id in (select env_id from env where browser_id in(".$browser.") and os_id in (".$osnames.")) and date BETWEEN :startdate and :enddate group by x,y;");
                    $st->bindValue(":page_id",$coordinfo['urlId']);
                    $st->bindValue(":startdate",$coordinfo['startDate']);
                    $st->bindValue(":enddate",$coordinfo['endDate']);
                    $st->execute();
                }
            } else {
                if ($coordinfo['resolutionId']) {
                    $resolution=implode("," , $coordinfo['resolutionId']);
                    $st=$this->db->prepare("SELECT x,y,sum(count)as count from data where page_id =:page_id and env_id in (select env_id from env where browser_id in(".$browser.") and resolution_id in (".$resolution.")) and date BETWEEN :startdate and :enddate group by x,y;");
                    $st->bindValue(":page_id",$coordinfo['urlId']);
                    $st->bindValue(":startdate",$coordinfo['startDate']);
                    $st->bindValue(":enddate",$coordinfo['endDate']);
                    $st->execute();
                } else {
                    $st = $this->db->prepare("SELECT x,y,sum(count)as count from data where page_id =:page_id and env_id in (select env_id from env where browser_id in(".$browser.")) and date BETWEEN :startdate and :enddate group by x,y;");
                    $st->bindValue(":page_id",$coordinfo['urlId']);
                    $st->bindValue(":startdate",$coordinfo['startDate']);
                    $st->bindValue(":enddate",$coordinfo['endDate']);
                    $st->execute();
                }
            }
        } else {
            if ($coordinfo['osId']) {
                if ($coordinfo['resolutionId']) {
                    $resolution = implode("," , $coordinfo['resolutionId']);
                    $osnames = implode("," , $coordinfo['osId']);
                    $st = $this->db->prepare("SELECT x,y,sum(count)as count from data where page_id =:page_id and env_id in (select env_id from env where  os_id in (".$osnames.")and resolution_id in (".$resolution.")) and date BETWEEN :startdate and :enddate group by x,y;");
                    $st->bindValue(":page_id",$coordinfo['urlId']);
                    $st->bindValue(":startdate",$coordinfo['startDate']);
                    $st->bindValue(":enddate",$coordinfo['endDate']);
                    $st->execute();
                } else {
                    $osnames = implode("," , $coordinfo['osId']);
                    $st = $this->db->prepare("SELECT x,y,sum(count)as count from data where page_id =:page_id and env_id in (select env_id from env where os_id in (".$osnames.")) and date BETWEEN :startdate and :enddate group by x,y;");
                    $st->bindValue(":page_id",$coordinfo['urlId']);
                    $st->bindValue(":startdate",$coordinfo['startDate']);
                    $st->bindValue(":enddate",$coordinfo['endDate']);
                    $st->execute();
                }
            } else {
                if ($coordinfo['resolutionId']) {
                    $resolution = implode("," , $coordinfo['resolutionId']);
                    $st=$this->db->prepare("SELECT x,y,sum(count)as count from data where page_id =:page_id and env_id in (select env_id from env where resolution_id in (".$resolution.")) and date BETWEEN :startdate and :enddate group by x,y;");
                    $st->bindValue(":page_id",$coordinfo['urlId']);
                    $st->bindValue(":startdate",$coordinfo['startDate']);
                    $st->bindValue(":enddate",$coordinfo['endDate']);
                    $st->execute();
                }
            }
        }
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $result;
    }
}
