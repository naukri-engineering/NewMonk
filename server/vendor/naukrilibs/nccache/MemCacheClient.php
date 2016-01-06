<?php
namespace nccache;

use Memcached;
use nccache\CacheInterface;
use nccache\CacheException;

/**
 * Description of MemcacheClient
 *
 * @author rajan
 */
class MemCacheClient implements CacheInterface
{
    private $ttl;
    private $dataType;
    protected $memcached;

    public function __construct($keyPrefix, $customParams, $automatic_serialization, $createNewConnection = false, $dataType, $ttl = null) {

            $this->keyPrefix = $keyPrefix;
            $this->dataType = $dataType;
            $this->ttl = $ttl;
            $this->memcached = new Memcached();

            if ($createNewConnection) {
                $this->memcached->resetServerList();
            }
            $this->automatic_serialization = $automatic_serialization;

            $serverList = $this->memcached->getServerList();
            if (empty($serverList)) {
                $this->memcached->setOption(Memcached::OPT_RECV_TIMEOUT, 1000);
                $this->memcached->setOption(Memcached::OPT_SEND_TIMEOUT, 1000);
                $this->memcached->setOption(Memcached::OPT_TCP_NODELAY, true);
                $this->memcached->setOption(Memcached::OPT_SERVER_FAILURE_LIMIT, 50);
                $this->memcached->setOption(Memcached::OPT_RETRY_TIMEOUT, 1);
                $this->memcached->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
                $this->memcached->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
                if($this->automatic_serialization) {
                    $this->memcached->setOption(Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_PHP);
                }
                if ($keyPrefix != NULL) {
                    $this->memcached->setOption(Memcached::OPT_PREFIX_KEY, $keyPrefix);
                }

                foreach ($customParams['servers'] as $server) {
                    if (array_key_exists('port', $server) && array_key_exists('host', $server)) {
                        $host = $server['host'];
                        $port = $server['port'];
                        $weight = array_key_exists('weight', $server) ? $server['weight'] : 0;

                        $this->addServer($host, $port, $weight);
                    }
                }
            }
        }

    private function addServer($host, $port, $weight = 0) {
        try {
            return $this->memcached->addServer($host, $port, $weight);
        }
        catch(Exception $e) {
            throw new CacheException(" Memcache Connection could not be established with ".$host." on ".$port);
        }
    }

    public function add($key, $value) {
        if ($this->dataType == 'default') {
            $result = $this->memcached->add($key, $value, $this->ttl);
            $this->printResultCodeWithMessage($result);
            return $result;
        }
        else {
            throw new CacheException($this->dataType."is not a supported data Type");
        }
    }

    public function addUpdate($key, $value) {
        if ($this->dataType == 'default') {
            $result = $this->memcached->set($key, $value,$this->ttl);
            $this->printResultCodeWithMessage($result);

            return $result;
        }
        else {
            throw new CacheException($this->dataType."is not a supported data Type");
        }
    }

    public function addUpdateMulti($items, $ttl = null) {
        if ($this->dataType == 'default') {
            $result = $this->memcached->setMulti($items, $ttl);
            $this->printResultCodeWithMessage($result);

            return $result;
        }
        else {
            throw new CacheException($this->dataType."is not a supported data Type");
        }

    }

    public function get($key) {
        if ($this->dataType == 'default') {
            try {
                $result = $this->memcached->get($key);
                $this->printResultCodeWithMessage($result);

                return ($result) ? $result : null;
            }
            catch(\Exception $e) {
                throw new CacheException("Memcached Error while getting key : $key Error : " . $e->getMessage());
            }

            return null;
        }
        else {
            throw new CacheException($this->dataType."is not a supported data Type");
        }

    }

    public function delete($key) {
        if ($this->dataType == 'default') {
            $result = $this->memcached->delete($key);
            $this->printResultCodeWithMessage($result);

            return $result;
        }
        else {
            throw new CacheException($this->dataType."is not a supported data Type");
        }
    }

    private function printResultCodeWithMessage($result) {
        if (!$result) {
            $resultCode = $this->memcached->getResultCode();

            if ($resultCode != Memcached::RES_NOTFOUND) {
                $resultMessage = $this->memcached->getResultMessage();
                throw new CacheException("Memcached Error : " . $resultCode . "=>". $resultMessage);
            }
        }
    }
}

