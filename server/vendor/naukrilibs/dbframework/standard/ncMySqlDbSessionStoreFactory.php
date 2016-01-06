<?php
class ncMySqlDbSessionStoreFactory implements ncDbSessionStoreFactory {
  public function createDbSessionStore($params) {
    $params['db_nodes'] = array($params['database']);
    return new ncMySqlDbSessionStore($params);
  }
}
?>
