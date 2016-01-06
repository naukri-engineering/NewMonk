<?php

/**
 * Provides support for session storage using a MySQL brand database.
 *
 * <b>Required parameters:</b>
 *
 * # <b>db_nodes</b> - [none] - The names of databases for session storage,
                                in order of their preference (master, slave1, ...)
 * # <b>db_table</b> - [none] - The database table in which session data will be
 *                              stored.
 *
 * <b>Optional parameters:</b>
 *
 * # <b>db_id_col</b>    - [sess_id]   - The database column in which the
 *                                       session id will be stored.
 * # <b>db_data_col</b>  - [sess_data] - The database column in which the
 *                                       session data will be stored.
 * # <b>db_time_col</b>  - [sess_time] - The database column in which the
 *                                       session timestamp will be stored.
 * # <b>db_id_prefix</b> - [none]      - The string to be prefixed to database id.
 */
class ncPdoDbSessionStore implements ncDbSessionStore {
  protected $connection = null;
  protected $parameters;

  public function __construct($parameters) {
    $this->parameters = $parameters;
  }

  /**
   * Reads a session.
   *
   * @param string A session ID
   *
   * @return value if id is found, null if id is not found, boolean false on error
   */
  public function get($id) {
    if(!$this->openConnection()) {
      return false;
    }
    // get table/column
    $db_table    = $this->getParameter('db_table');
    $db_data_col = $this->getParameter('db_data_col', 'sess_data');
    $db_id_col   = $this->getParameter('db_id_col', 'sess_id');
    $db_time_col = $this->getParameter('db_time_col', 'sess_time');

    // cleanup the session id, just in case
    $id = mysql_real_escape_string($id);

    // delete the record associated with this id
    $sql = 'SELECT '.$db_data_col.' ' .
           'FROM '.$db_table.' ' .
           'WHERE '.$db_id_col.' = \''.$id.'\'';

    $result = $this->connection->query($sql);

    if($result) {
      $numRows = $result->rowCount();
      switch($numRows) {
        case 0:
          return null;
        case 1:
          $data = $result->fetch();
          return $this->getParameter('db_prefix', '') . $data[0];
        default:
          return false;
      }
    } else {
      return false;
    }
  }

  /**
   * Writes session data.
   *
   * @param string A session ID
   * @param string session data
   *
   * @return boolean true, if the session was written, otherwise false
   *
   */
  public function set($id, $data, $expire=0) {
    if(!$this->openConnection()) {
      return false;
    }
    // get table/column
    $db_table    = $this->getParameter('db_table');
    $db_data_col = $this->getParameter('db_data_col', 'sess_data');
    $db_id_col   = $this->getParameter('db_id_col', 'sess_id');
    $db_time_col = $this->getParameter('db_time_col', 'sess_time');

    // cleanup the session id and data, just in case
    $id   = mysql_real_escape_string($id);
    $data = mysql_real_escape_string($data);

    // delete the record associated with this id
    $sql = "INSERT INTO $db_table ($db_id_col, $db_data_col, $db_time_col) " .
           "VALUES ('$id', '$data', NOW()) " .
           "ON DUPLICATE KEY UPDATE $db_time_col = NOW()";

    if($this->connection->query($sql)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Opens a connection.
   *
   * @throws <b>ncDatabaseException</b> If a connection with the database does not exist or cannot be created
   */
  protected function openConnection() {
    $dbNodes = $this->parameters['db_nodes'];
    foreach($dbNodes as $dbNode) {
      try {
        $this->connection = $this->getDatabaseManager()->getDatabase($dbNode)->getConnection();
        if($this->connection !== null) {
          return true;
        }
      } catch(Exception $e) {
        // TODO report error
      }
    }
    return false;
  }

  protected function getParameter($key, $default=null) {
    $params = $this->parameters;
    return array_key_exists($key, $params) ? $params[$key] : $default;
  }

  protected function getDatabaseManager() {
    return ncDatabaseManager::getInstance();
  }

}
?>
