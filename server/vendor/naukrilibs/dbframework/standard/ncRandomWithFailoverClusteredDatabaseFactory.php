<?php
class ncRandomWithFailoverClusteredDatabaseFactory extends ncClusteredDatabaseFactory {
  protected function createDbSelector($params) {
    return new ncRandomWithFailoverDbSelector($params['nodes']);
  }
}
?>
