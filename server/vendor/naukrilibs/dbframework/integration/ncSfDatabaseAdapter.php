<?php
class ncSfDatabaseAdapter extends sfDatabase {
  private $delegate;

  public function initialize($parameters = array()) {
    parent::initialize($parameters);
    $factoryParam = $this->getParameter('factory');
    $factoryClass = $factoryParam['class'] . 'Factory';
    $factory = new $factoryClass();
    $params = $factoryParam['param'];
    $this->delegate = $factory->createDatabase($params);
  }

  public function connect() {
    try {
      $this->connection = $this->delegate->getConnection();
    } catch(ncDatabaseException $e) {
      throw new sfDatabaseException($e->getMessage(), $e->getCode());
    }
  }

  public function shutdown() {
    try {
      $this->delegate->shutdown();
    } catch(ncDatabaseException $e) {
      throw new sfDatabaseException($e->getMessage(), $e->getCode());
    }
  }
}
?>
