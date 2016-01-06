<?php
class ncPdoDebug
{
  private $delegate;

  public function __construct($pdo)
  {
    $this->delegate = $pdo;
  }

  public function prepare($statement, array $driver_options=array())
  {
    return new ncPdoStatementDebug($this->delegate->prepare($statement, $driver_options));
  }

  public function __call($method, $args)
  {
    if($method === 'exec' || $method === 'query')
    {
      try
      {
        return call_user_func_array(array($this->delegate , $method), $args);
      }
      catch (PDOException $e)
      {
        error_log("ncDatabase PDO error in  $method ('" . $args[0] . "', ...) [Error message: " . $e->getMessage() . "]");
        throw $e;
      }
    }
    return call_user_func_array(array($this->delegate , $method), $args);
  }
}

class ncPdoStatementDebug
{
  private $delegate;
  private $boundValues = array();

  public function __construct($pdoStatement)
  {
    $this->delegate = $pdoStatement;
  }

  public function execute(array $input_parameters=null)
  {
    try
    {
      return $this->delegate->execute($input_parameters);
    }
    catch(PDOException $e)
    {
      error_log('ncDatabase PDOStatement execute error: [Query: ' . $this->delegate->queryString . '] [Bound values: ' . $this->boundValues($input_parameters) . '] [Error message: ' . $e->getMessage() . ']');
      throw $e;
    }
  }

  private function boundValues($input_parameters)
  {
    $boundValues = count($input_parameters) ? $input_parameters : $this->boundValues;
    $result = '';
    foreach($boundValues as $key => $value)
    {
      $result .= "{{$key}: $value}";
    }
    return $result;
  }

  public function __call($method, $args)
  {
    if($method === 'bindParam' || $method === 'bindValue')
    {
      $this->boundValues[$args[0]] = $args[1];
    }
    return call_user_func_array(array($this->delegate , $method), $args);
  }
}
