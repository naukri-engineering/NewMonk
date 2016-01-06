<?php
class ncAliasDatabaseFactory implements ncDatabaseFactory {
  public function createDatabase($params) {
    return new ncAliasDatabase($params['target']);
  }
}
?>
