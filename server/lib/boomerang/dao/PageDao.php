<?php

namespace NewMonk\lib\boomerang\dao;

class PageDao
{
    public function __construct($db, $dbUtilClassName) {
        $this->db = $db;
        $this->dbUtilClassName = $dbUtilClassName;
    }

    public function getByTag($appId, $tag) {
        return $this->getByChecksum($appId, md5($tag));
    }

    public function getByChecksum($appId, $checksum) {
        $dbUtilClassName = $this->dbUtilClassName;
        $dbNames = $dbUtilClassName::getDbNames($appId);
        $sqlGetPage = 'SELECT page_id FROM '.$dbNames['summary'].'.page WHERE checksum = :checksum';
        $statementGetPage = $this->db->prepare($sqlGetPage);
        $statementGetPage->bindValue(':checksum', $checksum, \PDO::PARAM_STR);
        $statementGetPage->execute();
        $pageId = 0;
        if ($rowGetPage = $statementGetPage->fetch(\PDO::FETCH_ASSOC)) {
            $pageId = $rowGetPage['page_id'];
        }
        $statementGetPage->closeCursor();
        return $pageId;
    }
}
