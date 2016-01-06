<?php
class ncSequentialFailoverDbSelector implements ncDbSelector {
  private $dbIds;

  public function __construct($dbIds) {
    $this->dbIds = $dbIds;
  }

  public function getDbId() {
    foreach($this->dbIds as $dbId) {
      try {
        ncDatabaseManager::getInstance()->getDatabase($dbId)->getConnection();
        return $dbId;
      } catch(Exception $e) {
        // Do Nothing, Try next id
      }
    }
    return $dbId;
  }

  public function getDbs() {
    return $this->dbIds;
  }
}
