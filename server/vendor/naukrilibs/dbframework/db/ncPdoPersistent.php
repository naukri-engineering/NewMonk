<?php
class ncPdoPersistent
{
    public $delegate;
    private $dsn;
    private $username;
    private $password;
    private $debug;
    private $reconnect;
    private $conAttributes;
    private $transactionInProgress = false;

    public function __construct($pdo, $dsn, $username, $password)
    {
        $this->delegate = $pdo;
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->conAttributes = array();
    }

    public function prepare($statement, array $driver_options=array())
    {
        try
        {
            return new ncPdoStatementPersistent($this->realPrepare($statement, $driver_options), $statement, $driver_options, $this);
        }
        catch(PDOException $e)
        {
            $this->maybeReconnect($this->delegate, $e);
            return new ncPdoStatementPersistent($this->realPrepare($statement, $driver_options), $statement, $driver_options, $this);
        }
    }

    public function realPrepare($statement, $driver_options)
    {
        return $this->delegate->prepare($statement, $driver_options);
    }

    public function maybeReconnect($pdoOrPdoStatement, $e)
    {
        $error = $pdoOrPdoStatement->errorInfo();
        if(($error[1] == 2006 || $error[1] == 2013) && !$this->transactionInProgress)
        {
            try
            {
                error_log('ncDatabase PDOPersistent error: [dsn: ' . $this->dsn . '] [Error message: ' . $error[2] . ']');
                $this->reconnect();
            }
            catch(PDOException $e)
            {
                throw $e;
            }
        }
        else
        {
            throw $e;
        }
    }

    protected function reconnect()
    {
        $this->delegate = new PDO($this->dsn, $this->username, $this->password);
        $this->setAttributes();
    }

    public function beginTransaction()
    {
        try
        {
            $this->delegate->beginTransaction();
        }
        catch(PDOException $e)
        {
            $this->maybeReconnect($this->delegate, $e);
            $this->delegate->beginTransaction();
        }
        $this->transactionInProgress = true;
    }

    public function __call($method, $args) {
        if($method === 'exec' || $method === 'query')
        {
            try
            {
                return call_user_func_array(array($this->delegate , $method), $args);
            }
            catch (PDOException $e)
            {
                $this->maybeReconnect($this->delegate, $e);
            }
        }
        else if($method == 'commit' || $method == 'rollBack')
        {
            $this->transactionInProgress = false;
        }
        else if ($method === 'setAttribute')
        {
            $this->conAttributes[$args[0]] = $args[1];
        }

        return call_user_func_array(array($this->delegate , $method), $args);
    }

    private function setAttributes()
    {
        foreach($this->conAttributes as $key=>$val)
        {
            $this->delegate->setAttribute($key, $val);
        }
    }
}

class ncPdoStatementPersistent
{
    private $delegate;
    private $statement;
    private $driver_options;
    private $pdo;

    private $bindedValues = array();
    private $bindedParams = array();
    private $bindedColumns = array();
    private $stmtAttributes = array();
    private $fetchmode = '';

    public function __construct($pdoStatement, $statement, $driver_options, $pdo)
    {
        $this->delegate = $pdoStatement;
        $this->statement = $statement;
        $this->driver_options = $driver_options;
        $this->pdo = $pdo;
    }

    public function execute(array $input_parameters=null)
    {
        try
        {
            return $this->delegate->execute($input_parameters);
        }
        catch(PDOException $e)
        {
            $this->pdo->maybeReconnect($this->delegate, $e);
            $this->delegate = $this->pdo->realPrepare($this->statement, $this->driver_options);
            $this->setAttributes();
            $this->setFetchMode();
            $this->boundValues();
            $this->boundParams();
            $this->boundColumns();
            return $this->delegate->execute($input_parameters);
        }
    }

    public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR , $length= "", $driver_options = "")
    {
        $this->bindedParams[$args[0]] = array($parameter, $variable, $data_type, $length,$driver_options);
        //return parent::bindParam($parameter, $variable, $data_type, $length ,$driver_options);
        return $this->delegate->bindParam($parameter, $variable, $data_type, $length ,$driver_options);
    }

    public function __call($method, $args)
    {
    /*
     if($method === 'bindParam')
    {
      $this->bindedParams[$args[0]] = array($args[1], $args[2], $args[3], $args[4]);
    }
     else
     */
        if($method === 'bindColumn')
        {
            $this->bindedColumns[$args[0]] = array($args[1], $args[2], $args[3], $args[4]);
        }
        elseif($method === 'bindValue')
        {
            if(isset($args[2]))
                $this->bindedValues[$args[0]] = array($args[1], $args[2]);
            else
                $this->bindedValues[$args[0]] = array($args[1]);
        }
        elseif($method === 'setAttribute')
        {
            $this->stmtAttributes[$args[0]] = $args[1];
        }
        elseif($method === 'setFetchMode')
        {
            $this->fetchmode = $args[0];
        }
        return call_user_func_array(array($this->delegate , $method), $args);
    }

    private function boundParams()
    {
        foreach($this->bindedParams as $k => $v)
        {
            if($v[4])
                $this->delegate->bindParam($k, $v[0], $v[1], $v[2], $v[3], $v[4]);
            elseif($v[3])
                $this->delegate->bindParam($k, $v[0], $v[1], $v[2], $v[3]);
            elseif($v[2])
                $this->delegate->bindParam($k, $v[0], $v[1], $v[2]);
            elseif($v[1])
                $this->delegate->bindParam($k, $v[0], $v[1]);
            else
                $this->delegate->bindParam($k, $v[0]);
        }
    }

    private function boundColumns()
    {
        foreach($this->bindedColumns as $k => $v)
        {
            if($v[3])
                $this->delegate->bindColumn($k, $v[0], $v[1], $v[2], $v[3]);
            elseif($v[2])
                $this->delegate->bindColumn($k, $v[0], $v[1], $v[2]);
            elseif($v[1])
                $this->delegate->bindColumn($k, $v[0], $v[1]);
            else
                $this->delegate->bindColumn($k, $v[0]);
        }
    }

    private function boundValues()
    {
        foreach($this->bindedValues as $k => $v)
        {
            if($v[1])
                $this->delegate->bindValue($k, $v[0], $v[1]);
            else
                $this->delegate->bindValue($k, $v[0]);
        }
    }

    private function setAttributes()
    {
        foreach($this->stmtAttributes as $k => $v)
        {
            $this->delegate->setAttribute($k, $v);
        }
    }

    private function setFetchMode()
    {
        if($this->fetchmode)
            $this->delegate->setFetchMode($this->fetchmode);
    }
}
