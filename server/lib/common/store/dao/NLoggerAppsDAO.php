<?php

class NLoggerAppsDAO extends NLoggerBaseDAO
{
    private $tableName;

    public function __construct($db) {
        parent::__construct($db);
        $this->tableName = 'apps';
    }

    public function getDropdown($domainId) {
        return $this->getAppsListByDomainId($domainId);
    }

    public function getAppsListByDomainId($domainId) {
        $sql = 'SELECT SQL_CACHE app_id, app_name FROM '.$this->tableName.' WHERE domain_id = :domain_id';
        $st = $this->db->prepare($sql);
        $st->bindValue(':domain_id', $domainId);
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $apps[$row['app_id']]['app_name'] = $row["app_name"];
        }
        $st->closeCursor();
        return $apps;
    }

    public function getAppsAndDomains() {
        $sql = 'SELECT domains.domain_id, domains.domain_name, apps.app_id, apps.app_name'
        .' FROM domains, apps'
        .' WHERE domains.domain_id = apps.domain_id'
        .' ORDER BY domains.domain_id, apps.app_name';
        $st = $this->db->prepare($sql);
        $st->execute();
        $apps = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $apps;
    }
}
