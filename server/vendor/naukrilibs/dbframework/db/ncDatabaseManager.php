<?php

/**
 * ncDatabaseManager allows you to setup your database connectivity before the
 * request is handled. This eliminates the need for a filter to manage database
 * connections.
 */
class ncDatabaseManager {
  protected $databases = array();

  private static $instance;

  /**
   * Retrieve the singleton instance of this class.
   */
  public static function getInstance() {
    if (!isset(self::$instance)) {
      $class = __CLASS__;
      self::$instance = new $class();
    }

    return self::$instance;
  }

  /**
   * Initializes this ncDatabaseManager object
   */
  private function __construct() {
    $configDirs = explode(PATH_SEPARATOR, NC_DB_CONFIG);
    $config = array();
    foreach($configDirs as $configDir) {
      $configFile = $configDir . '/nc_databases.yml';
      $config = array_merge($config, ncYaml::load($configFile));
    }
    if(!$config || count($config) == 0) {
      throw new ncDatabaseException('Database configuration not found: ' . NC_DB_CONFIG);
    }
    foreach($config as $dbName => $dbParams) {
      $factoryName = $dbParams['factory'] . 'Factory';
      $factory = new $factoryName();
      $params = $dbParams['param'];
      $database = $factory->createDatabase($params);
      $database->initialize($params);
      $this->databases[$dbName] = $database;
    }
  }

  /**
   * Retrieves the database connection associated with this ncDatabase implementation.
   *
   * @param string A database name
   *
   * @return mixed A Database instance
   *
   * @throws <b>ncDatabaseException</b> If the requested database name does not exist
   */
  public function getDatabase($name = 'default') {
    if (isset($this->databases[$name])) {
      return $this->databases[$name];
    }

    // nonexistent database name
    $error = 'Database "%s" does not exist';
    $error = sprintf($error, $name);

    throw new ncDatabaseException($error);
  }

  /**
   * Executes the shutdown procedure
   *
   * @return void
   *
   * @throws <b>ncDatabaseException</b> If an error occurs while shutting down this DatabaseManager
   */
  public function shutdown() {
    // loop through databases and shutdown connections
    foreach ($this->databases as $database) {
      $database->shutdown();
    }
  }
}
?>
