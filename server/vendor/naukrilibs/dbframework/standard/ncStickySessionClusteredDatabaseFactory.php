<?php
class ncStickySessionClusteredDatabaseFactory extends ncClusteredDatabaseFactory {
  protected function createDbSelector($params) {
    $sessionIdSource = $this->createSessionIdSource($params);
    $sessionStore = $this->createSessionStore($params);
    $requestDistributor = $this->createNewSessionDbSelector($params);
    if(array_key_exists('session_lifetime', $params)) {
      return new ncStickySessionDbSelector($sessionIdSource, $sessionStore, $requestDistributor, $params['session_lifetime']);
    } else {
      return new ncStickySessionDbSelector($sessionIdSource, $sessionStore, $requestDistributor);
    }
  }

  protected function createSessionIdSource($params) {
    $sessionIdSourceClass = $params['session_id_source'];
    return $sessionIdSource = new $sessionIdSourceClass();
  }

  protected function createSessionStore($params) {
    return ncDbSessionStoreManager::getInstance()->getDbSessionStore($params['session_store']);
  }

  protected function createNewSessionDbSelector($params) {
    return new ncRandomDbSelector($params['nodes']);
  }
}
?>
