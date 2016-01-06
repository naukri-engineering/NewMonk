<?php
abstract class ncClusteredDatabaseFactory implements ncDatabaseFactory {
  public function createDatabase($params) {
    return new ncClusteredDatabase($this->createDbSelector($params));
  }

  protected abstract function createDbSelector($params);
}
?>
