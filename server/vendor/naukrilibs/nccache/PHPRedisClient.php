<?php
namespace nccache;
use Redis;
use RedisArray;
use nccache\CacheInterface;

/**
 * Description of PHPRedisClient
 * @author rajan
 */
class PHPRedisClient implements CacheInterface
{
    protected $keyPrefix;
    protected $dataType;
    protected $ttl;
    protected $automatic_serialization;
    protected $hosts;
    protected $redis;

    public function __construct($keyPrefix, $customParams, $automatic_serialization, $createNewConnection = false, $dataType, $ttl = null) {
        $this->keyPrefix = $keyPrefix;
        $this->dataType = $dataType;
        $this->ttl = $ttl;
        $this->automatic_serialization = $automatic_serialization;

        $this->hosts = array();
        foreach($customParams['servers'] as $server) {
            if (array_key_exists('port', $server) && array_key_exists('host', $server)) {
                $this->hosts[] =  $server['host'] . ":" . $server['port'];
            }
        }

        $this->connectToRedis($this->hosts);
    }

    public function reconnect() {
        $this->redis = null;
        $this->connectToRedis($this->hosts);
    }

    public function add($key, $value) {
        $key = $this->keyPrefix . $key;

        if ($this->dataType == "set") {
            $result = $this->redis->sAdd($key, $value);
        }
        elseif ($this->datatype == "hash") {
            $result = $this->redis->hSet($key, $value);
        }
        else {
            $result = $this->redis->setnx($key, $value);
        }

        if ($this->ttl) {
            $this->redis->setTimeout($key, $this->ttl);
        }

        return $result;
    }

    public function addUpdate($key, $value) {
        $key = $this->keyPrefix . $key;

        if ($this->dataType == "set") {
            $result = $this->redis->sAdd($key, $value);
        }
        elseif ($this->datatype == "hash") {
            $result = $this->redis->hSet($key, $value);
        }
        else {
            $result = $this->redis->set($key, $value);
        }

        if ($this->ttl) {
            $this->redis->setTimeout($key, $this->ttl);
        }

        return $result;
    }

    public function addUpdateMulti($items) {
        $result = $this->redis->mset($items);

        if ($this->ttl) {
            foreach ($items as $key => $value) {
                $key = $this->keyPrefix . $key;
                $this->redis->setTimeout($key, $this->ttl);
            }
        }

        return $result;
    }

    public function get($key) {
        try {
            $key = $this->keyPrefix . $key;

/*
            if (!$this->redis->exists($key)) {
                return null;
            }
 */

            $result = $this->redis->get($key);
            return ($result) ? $result : null;
        }
        catch(Exception $e){
            $key = $this->keyPrefix . $key;
            throw new CacheException("PHPRedis Error while getting key : $key Error : " . $e->getMessage());
        }
    }

    public function delete($key) {
        $key = $this->keyPrefix . $key;
        return $this->redis->delete($key);
    }

    public function __call($name, $arguments) {
        try {
            $arguments[0] = $this->keyPrefix . $arguments[0];
            return call_user_func_array(array($this->redis, $name), $arguments);
        }
        catch (\Exception $e) {
            throw new CacheException("PHPRedis Error while calling: $name Error : " . json_encode($arguments));
        }
    }

    private function connectToRedis($hosts) {
        $this->redis = new RedisArray($hosts);
    }
}

