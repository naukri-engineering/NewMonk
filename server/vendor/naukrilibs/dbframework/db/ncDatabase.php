<?php
abstract class ncDatabase {
  protected
    $connection      = null,
    $resource        = null;
  protected $parameters = array();

  public function initialize($parameters=array()) {
    $this->parameters = $parameters;
  }

  public function getParameter($name, $default=null) {
    return array_key_exists($name, $this->parameters) ? $this->parameters[$name] : $default;
  }

  /**
   * Connects to the database.
   *
   * @throws <b>ncDatabaseException</b> If a connection could not be created
   */
  protected abstract function connect();

  /**
   * Retrieves the database connection associated with this ncDatabase implementation.
   *
   * When this is executed on a Database implementation that isn't an
   * abstraction layer, a copy of the resource will be returned.
   *
   * @return mixed A database connection
   *
   * @throws <b>ncDatabaseException</b> If a connection could not be retrieved
   */
  public function getConnection($force = false) {
    if ($this->connection == null || $force) {
      $this->connect();
    }

    return $this->connection;
  }

  /**
   * Retrieves a raw database resource associated with this ncDatabase implementation.
   *
   * @return mixed A database resource
   *
   * @throws <b>ncDatabaseException</b> If a resource could not be retrieved
   */
  public function getResource() {
    if ($this->resource == null) {
      $this->connect();
    }

    return $this->resource;
  }

}
?>
