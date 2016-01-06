<?php
class ncRandomDbSelector implements ncDbSelector {
  private $dbIds;

  public function __construct($dbIds) {
    $this->dbIds = $dbIds;
  }

  public function getDbId() {
    return $this->dbIds[rand(0, count($this->dbIds) - 1)];
  }

  public function getDbs() {
    return $this->dbIds;
  }
}
?>
