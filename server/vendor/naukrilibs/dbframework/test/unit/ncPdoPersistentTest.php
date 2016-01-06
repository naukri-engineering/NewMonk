<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'db/ncDnsResolver.php';
require_once 'db/ncDatabase.php';
require_once 'db/ncPdoPersistent.php';
require_once 'db/ncPdoDatabase.php';

class ncPdoPersistentTest extends PHPUnit_Framework_TestCase
{
  private $conn;
  private $waitTimeout;

  public function setUp()
  {
    $db = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
    $this->conn = new ncTestPdoPersistent($db, 'mysql:host=localhost;dbname=test', 'root', 'root', true, false);
    $conn = $this->conn;
	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $conn->exec('drop table if exists pdodb_test');
    $conn->exec('create table pdodb_test (id int primary key auto_increment, data varchar(5))');
    $conn->exec("insert into pdodb_test (data) values ('foo')");

    $conn->exec('set global wait_timeout = 1');
    $this->waitTimeout = 1.2 * 1000000;
//    $stmt = $conn->query("show global variables like 'wait_timeout'")->fetch();
//    $this->waitTimeout = $stmt['Value'];
  }

  public function testMultipleTimeouts()
  {
    usleep($this->waitTimeout);
    $stmt = $this->conn->prepare("select * from pdodb_test");
    $this->assertNotNull($stmt);

    usleep($this->waitTimeout);
    $stmt = $this->conn->prepare("select * from pdodb_test");
    $this->assertNotNull($stmt);
  }

  public function testReconnectOnStatementExecute()
  {
    $stmt = $this->conn->prepare("select * from pdodb_test");
    usleep($this->waitTimeout);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $this->assertEquals(1, count($result));
  }

  /**
   * @expectedException PDOException
   */
  public function testExceptionOnReconnectFailureOnStatementExecute()
  {
    $stmt = $this->conn->prepare("select * from pdodb_test");
    usleep($this->waitTimeout);
    $this->conn->reconnect = false;
    $stmt->execute();
  }
  
  public function testReconnectOnStatementPrepare()
  {
    usleep($this->waitTimeout);
    $stmt = $this->conn->prepare("select * from pdodb_test");
    $this->assertNotNull($stmt);
  }

  /**
   * @expectedException PDOException
   */
  public function testExceptionOnReconnectFailureOnPrepare()
  {
    usleep($this->waitTimeout);
    $this->conn->reconnect = false;
    $stmt = $this->conn->prepare("select * from pdodb_test");
  }

  /**
   * @expectedException PDOException
   */
  public function testExceptionOnReconnectFailureOnQuery()
  {
    usleep($this->waitTimeout);
    $this->conn->reconnect = false;
    $stmt = $this->conn->query("select * from pdodb_test");
  }
  
  /**
   * @expectedException PDOException
   */
  public function testExceptionOnReconnectFailureOnBeginTransaction()
  {
    usleep($this->waitTimeout);
    $this->conn->reconnect = false;
    $stmt = $this->conn->beginTransaction();
  }
  
  public function testReconnectOnQuery()
  {
    usleep($this->waitTimeout);
    $stmt = $this->conn->query("select * from pdodb_test");
    $result = $stmt->fetchAll();
    $this->assertEquals(1, count($result));
  }

  public function testReconnectOnBeginTransaction()
  {
    usleep($this->waitTimeout);
    $this->conn->beginTransaction();
    $stmt = $this->conn->query("select * from pdodb_test");
    $result = $stmt->fetchAll();
    $this->conn->commit();
    $this->assertEquals(1, count($result));
  }

  /**
   * @expectedException PDOException
   */
  public function testDontReconnectInsideTransaction()
  {
    $this->conn->beginTransaction();
    usleep($this->waitTimeout);
    $stmt = $this->conn->query("select * from pdodb_test");
  }

  /**
   * @expectedException PDOException
   */
  public function testDontReconnectStatementInsideTransaction()
  {
    $this->conn->beginTransaction();
    $stmt = $this->conn->prepare("select * from pdodb_test");
    usleep($this->waitTimeout);
    $result = $stmt->execute();
  }

  public function testReconnectAfterCommit()
  {
    $this->conn->beginTransaction();
    $this->conn->query("select * from pdodb_test");
    $this->conn->commit();

    usleep($this->waitTimeout);

    $stmt = $this->conn->query("select * from pdodb_test");
    $result = $stmt->fetchAll();
    $this->assertEquals(1, count($result));
  }

  public function testReconnectAfterRollback()
  {
    $this->conn->beginTransaction();
    $this->conn->query("select * from pdodb_test");
    $this->conn->rollBack();

    usleep($this->waitTimeout);

    $stmt = $this->conn->query("select * from pdodb_test");
    $result = $stmt->fetchAll();
    $this->assertEquals(1, count($result));
  }

  public function testConnectionFromStatementReusedByConnection()
  {
    $stmt = $this->conn->prepare("select * from pdodb_test");
    usleep($this->waitTimeout);
    $stmt->execute();
    $res = $this->conn->query("select * from pdodb_test");
    $this->assertEquals(1, $this->conn->reconnectionCount);
  }

  public function testConnectionFromConnectionReusedByStatement()
  {
    $stmt = $this->conn->prepare("select * from pdodb_test");
    usleep($this->waitTimeout);
    $res = $this->conn->query("select * from pdodb_test");
    $stmt->execute();
    $this->assertEquals(1, $this->conn->reconnectionCount);
  }

  public function tearDown()
  {
    $this->conn = null;
  }
}

class ncTestPdoPersistent extends ncPdoPersistent
{
  public $reconnect = true;
  public $reconnectionCount = 0;

  protected function reconnect()
  {
    $this->reconnectionCount++;

    if($this->reconnect)
    {
      parent::reconnect();
    }
    else
    {
      throw new PDOException("Forced exception");
    }
  }
}

