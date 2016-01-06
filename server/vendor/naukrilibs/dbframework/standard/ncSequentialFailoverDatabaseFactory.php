<?php
class ncSequentialFailoverDatabaseFactory extends ncClusteredDatabaseFactory {
  protected function createDbSelector($params) {
    return new ncSequentialFailoverDbSelector($params['nodes']);
  }
}
?>
