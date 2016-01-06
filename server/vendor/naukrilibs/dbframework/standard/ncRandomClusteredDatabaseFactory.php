<?php
class ncRandomClusteredDatabaseFactory extends ncClusteredDatabaseFactory {
  protected function createDbSelector($params) {
    return new ncRandomDbSelector($params['nodes']);
  }
}
?>
