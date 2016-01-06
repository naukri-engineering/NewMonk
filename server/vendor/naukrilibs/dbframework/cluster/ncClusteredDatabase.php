<?php
class ncClusteredDatabase extends ncDatabase {
  private $dbSelector;

  private $database;

  private $selectedDbId = 0;

  public function __construct(ncDbSelector $dbSelector) {
    $this->dbSelector = $dbSelector;
  }

  public function connect() {
    $dbId = $this->dbSelector->getDbId();
	$this->selectedDbId = $dbId;
    $this->database = ncDatabaseManager::getInstance()->getDatabase($dbId);
    $this->connection = $this->database->getConnection();
    //$this->resource = $this->database->getResource();
  }

  public function shutdown() {
    if($this->database != null) {
      $this->database->shutdown();
    }
    $this->connection = null;
  }

  public function getDbs() {
    return $this->dbSelector->getDbs();
  }

  public function getSelectedDbId() {
	if($this->selectedDbId)
		return $this->selectedDbId;
	else
    	return $this->dbSelector->getDbId();
  }


}
?>
