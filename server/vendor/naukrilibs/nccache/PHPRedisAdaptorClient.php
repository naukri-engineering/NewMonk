<?php
namespace nccache;
use Redis;
use RedisArray;
use nccache\CacheInterface;

/**
 * Description of PHPRedisAdaptorClient
 * @author rajan
 */
class PHPRedisAdaptorClient
{
    protected $redis;

    public function __construct($redis) {
        $this->redis = $redis;
    }

    public function __call($name, $arguments) {
        try {
            return call_user_func_array(array($this->redis, $name), $arguments);
        }
        catch (\Exception $e) {
            try {
                $this->redis->reconnect();
                return call_user_func_array(array($this->redis, $name), $arguments);
            }
            catch (\Exception $e) {
                throw new CacheException("PHPRedis Error while calling: $name Error : " . json_encode($arguments));
            }
        }
    }
}

