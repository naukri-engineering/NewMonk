<?php
class ncAliasDatabase extends ncDatabase {
  private $target;

  public function __construct($target) {
    $this->target = $target;
  }

  public function connect() {
    $database = $this->getTargetDatabase();
    $this->connection = $database->getConnection();
    $this->resource = $database->getResource();
  }

  public function getParameter($name, $default=null) {
    $database = $this->getTargetDatabase();
    $value = parent::getParameter($name);
    return $value === null ? $this->getTargetDatabase()->getParameter($name, $default) : $value;
  }

  public function shutdown() {
    $this->connection = null;
    $this->getTargetDatabase()->shutdown();
  }

  protected function getTargetDatabase() {
    return $this->getDatabaseManager()->getDatabase($this->target);
  }

  protected function getDatabaseManager() {
    return ncDatabaseManager::getInstance();
  }
}
?>
