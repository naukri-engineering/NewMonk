<?php
class ncSfDatabaseManager {
  public static function getInstance() {
    return sfContext::getInstance()->getDatabaseManager();
  }
}
?>
