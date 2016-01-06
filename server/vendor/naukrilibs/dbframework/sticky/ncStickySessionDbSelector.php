<?php
class ncStickySessionDbSelector implements ncDbSelector {
  private $dbSessionIdSource;
  private $dbSessionStore;
  private $newSessionDbSelector;
  private $sessionLifetime;

  public function __construct(ncDbSessionIdSource $dbSessionIdSource, ncDbSessionStore $dbSessionStore, ncDbSelector $newSessionDbSelector, $sessionLifetime=0) {
    $this->dbSessionIdSource = $dbSessionIdSource;
    $this->dbSessionStore = $dbSessionStore;
    $this->newSessionDbSelector = $newSessionDbSelector;
    $this->sessionLifetime = $sessionLifetime;
  }

  public function getDbId() {
    $dbSessionId = $this->dbSessionIdSource->getSessionId();
    if($dbSessionId === null || trim($dbSessionId) === '') {
      throw new ncDatabaseException('Could not get database session id');
    } else {
      $dbId = $this->dbSessionStore->get($dbSessionId);
      if($dbId === false) {
        throw new ncDatabaseException('Could not get database id for session: ' . $dbSessionId);
      } elseif($dbId === null) {
        $dbId = $this->newSessionDbSelector->getDbId();
      }

      if($this->sessionLifetime >= 0) {
        $sessionStored = $this->dbSessionStore->set($dbSessionId, $dbId, $this->sessionLifetime);
        if(!$sessionStored) {
          throw new ncDatabaseException("Could not create database session: $dbSessionId -> $dbId");
        }
      }
      return $dbId;
    }
  }

  public function getDbs() {
    return $this->newSessionDbSelector->getDbs();
  }

}
?>
