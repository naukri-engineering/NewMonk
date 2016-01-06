<?php
class ncRandomWithFailoverDbSelector implements ncDbSelector {
  private $dbIds;

  public function __construct($dbIds) {
    $this->dbIds = $dbIds;
  }

  public function getDbId() {

	  $NumKeys = count($this->dbIds);
	  $OriginalKey = rand(0,$NumKeys-1);
	  $key = $OriginalKey;
	  $dbId = "";

	  do {
		  $dbId = $this->dbIds[$key];	//Obtain the selected DbId
		  try {
			  ncDatabaseManager::getInstance()->getDatabase($dbId)->getConnection();
			  return $dbId;
		  } catch(Exception $e) {
			  // Do Nothing, Try next id
		  }
		  $key = ($key + 1) % $NumKeys;
	  }while($key != $OriginalKey);

	  return $dbId; //If all connection fails, send the last tested node.
  }

  public function getDbs() {
    return $this->dbIds;
  }
}
?>
