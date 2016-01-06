<?php
class ncModClusteredDatabaseFactory extends ncClusteredDatabaseFactory {
  protected function createDbSelector($params) {
    $sessionIdSource = $this->createSessionIdSource($params);
	      return new ncModDbSelector($params['nodes'], $sessionIdSource);
  }

  protected function createSessionIdSource($params) {
    $sessionIdSourceClass = $params['session_id_source'];
    return $sessionIdSource = new $sessionIdSourceClass();
  }
}
?>
