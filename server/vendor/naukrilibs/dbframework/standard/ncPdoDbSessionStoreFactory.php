<?php
class ncPdoDbSessionStoreFactory implements ncDbSessionStoreFactory {
  public function createDbSessionStore($params) {
    $params['db_nodes'] = array($params['database']);
    return new ncPdoDbSessionStore($params);
  }
}
?>
