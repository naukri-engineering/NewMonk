<?php
class ncDbSessionStoreManager {
  protected $stores = array();

  private static $instance;

  public static function getInstance() {
    if (!isset(self::$instance)) {
      $class = __CLASS__;
      self::$instance = new $class();
    }
    return self::$instance;
  }

  private function __construct() {
    $configDirs = explode(PATH_SEPARATOR, NC_DB_CONFIG);
    $config = array();
    foreach($configDirs as $configDir) {
      $configFile = $configDir . '/nc_dbsessionstore.yml';
      $config = array_merge($config, ncYaml::load($configFile));
    }
    if(!$config || count($config) == 0) {
      throw new ncDatabaseException('DbSessionStore configuration not found: ' . $configFile);
    }
    foreach($config as $dbName => $dbParams) {
      $factoryName = $dbParams['factory'] . 'Factory';
      $factory = new $factoryName();
      $params = $dbParams['param'];
      $store = $factory->createDbSessionStore($params);
//      $store->initialize($params);
      $this->stores[$dbName] = $store;
    }
  }

  public function getDbSessionStore($name = 'default') {
    if (isset($this->stores[$name])) {
      return $this->stores[$name];
    }

    // nonexistent dbsessionstore name
    $error = 'DbSessionStore "%s" does not exist';
    $error = sprintf($error, $name);

    throw new ncDatabaseException($error);
  }

}
?>
