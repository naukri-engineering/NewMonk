<?php
class ncPdoDatabase extends ncDatabase
{
  private $dsn;
  private $username;
  private $password;
  private $debug;
  private $reconnect;

  public function __construct($dsn, $username, $password, $reconnect = false, $debug=false)
  {
    $this->dsn = $dsn;
    $this->username = $username;
    $this->password = $password;
    $this->debug = $debug;
    $this->reconnect = $reconnect;
  }

  protected function connect()
  {
    try
    {
      $ncDnsResolver = new ncDnsResolver();
      $this->dsn = $ncDnsResolver->resolveDNS($this->dsn);
      
      $this->connection = new PDO($this->dsn, $this->username, $this->password);
      if($this->reconnect)
      {
        $this->connection = new ncPdoPersistent($this->connection, $this->dsn, $this->username, $this->password);
      }
      if($this->debug)
      {
        $this->connection = new ncPdoDebug($this->connection);
      }
    }
    catch(PDOException $e)
    {
      throw new ncDatabaseException($e->getMessage() . '[dsn: ' . $this->dsn . ']');
    }

    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  public function shutdown()
  {
    $this->connection = null;
  }
}
