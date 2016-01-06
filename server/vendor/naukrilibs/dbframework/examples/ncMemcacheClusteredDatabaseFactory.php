<?php
class ncMemcacheClusteredDatabaseFactory extends ncClusteredDatabaseFactory {
  protected function createDbSelector($params) {
    $dbSessionIdSource = new ncRequestDbSessionIdSource();
    $dbSessionStore = new ncMemcacheDbSessionStore($params['memcache_nodes']);
    $dbIds = $params['db_nodes'];
    $dbRequestDistributor = new ncRandomDbRequestDistributor($dbIds);
    return new ncStickySessionDbSelector($dbIds, $dbSessionIdSource, $dbSessionStore, $dbRequestDistributor, $params['session_lifetime']);
  }
}
?>
