<?php
class ncMySqlDatabase extends ncDatabase
{
  private $host;
  private $database;
  private $username;
  private $password;
  private $persistent;

  public function __construct($database, $host, $username, $password, $persistent=false)
  {
    $this->database = $database;
    $ncDnsResolver = new ncDnsResolver();
    $host = $ncDnsResolver->resolveDNS($host, false);
    $this->host = trim($host, ";");
    $this->username = $username;
    $this->password = $password;
    $this->persistent = $persistent;
  }

  public function connect()
  {
    $database = $this->database;
    $host     = $this->host;
    $password = $this->password;
    $username = $this->username;

    if($this->persistent)
    {
      $this->connection = mysql_pconnect($host, $username, $password);
    }
    else
    {
      $this->connection = mysql_connect($host, $username, $password, true);
    }

    if ($this->connection === false)
    {
      $error = 'Failed to create a MySqlDatabase connection to ' . $host . ' : ' . mysql_error();
      throw new ncDatabaseException($error);
    }

    if ($database != null && !mysql_select_db($database, $this->connection))
    {
      $error = 'Failed to select MySqlDatabase "%s": ' . mysql_error();
      $error = sprintf($error, $database);
      throw new ncDatabaseException($error);
    }

    $this->resource = $this->connection;
  }

  public function shutdown()
  {
    if ($this->connection != null)
    {
      mysql_close($this->connection);
    }
  }
}
