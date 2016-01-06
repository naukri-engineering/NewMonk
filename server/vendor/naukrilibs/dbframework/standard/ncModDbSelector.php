<?php
class ncModDbSelector implements ncDbSelector {
  private $dbIds;
  private $sessionIdSource;

  public function __construct($dbIds, $sessionIdSource) {
    $this->dbIds = $dbIds;
    $this->sessionIdSource = $sessionIdSource;
  }

  public function getDbId() {

 	$dbSessionId = $this->sessionIdSource->getSessionId();
    	if($dbSessionId === null || trim($dbSessionId) === '') {
      			throw new ncDatabaseException('Could not get database session id');
    	} 
	else{
		$n = count($this->dbIds);
 		$dbId = $this->dbIds[$dbSessionId%$n];
		ncDatabaseManager::getInstance()->getDatabase($dbId)->getConnection();
		return $dbId;
	}
  }

  public function getDbs() {
    return $this->dbIds;
  }
}
?>
