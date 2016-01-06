<?php
namespace nccache;

use nccache\CacheInterface;
use nccache\PHPRedisAdaptorClient;
use nccache\PHPRedisClient;
use nccache\MemCacheClient;
use nccache\NoCacheClient;
use nccache\CacheClientException;
use nccache\CacheException;
use DnsResolver;
use ncYaml;

/**
 * Description of CacheClientFactory
 * @author rajan
 */
class CacheClientFactory
{
    private static $instance;
    private $dnsResolver;
    private $config = array();

    /**
     * Retrieve the singleton instance of this class.
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    /**
     * Initializes this CacheClientFactory object
     */
    protected function __construct() {
        $this->dnsResolver = new DnsResolver();

        $config = array();
        $configDir = NAUKRI_CACHE_CONFIG;
        $configFile = $configDir . '/nc_caches.yml';
        $config = array_merge($config, ncYaml::load($configFile));

        if (!$config || count($config) == 0) {
            throw new CacheException('Cache configuration not found: ' . NAUKRI_CACHE_CONFIG);
        }

        $this->config = $config['parameters'];
    }


    public function getCacheClient($configNode) {
        $config = $this->config[$configNode];
        $type = $config['cacheEngine'];
        $prefix = $config['prefix'];
        $name = $config['name'];
        $createNew = (array_key_exists('createNew', $config))?$config['createNew']:false;
        $customParams = $config['custom.params'];
        $dataType = $config['dataType'];
        $ttl = $config['ttl'];
        $automatic_serialization = $config['automatic_serialization'];

        $clientClass = 'nccache\\'.$type . 'Client';
        if (!class_exists($clientClass)) {
            throw new CacheException('CacheClient definition not found: ' .$name);
        }

        $client = new $clientClass($prefix, $customParams, $automatic_serialization, $createNew, $dataType, $ttl);
        if ($type == 'PHPRedis') {
            return new PHPRedisAdaptorClient($client);
        }
        else {
            return $client;
        }
    }

    private function resolveHost($backendServers) {
        $pattern = "/\{([^\}]*)\}/";

        foreach ($backendServers as $key => $backendServer) {
            $host = $backendServer['host'];
            preg_match_all($pattern, $host, $matches);

            if (count($matches) > 0) {
                foreach ($matches[1] as $match) {
                    $hostInfo = $this->dnsResolver->resolveDNS("_cache._tcp.".$match.".");

                    $backendServers[$key]['host'] = $hostInfo['ip'];
                    $backendServers[$key]['port'] = $hostInfo['port'];
                }
            }

            unset($matches);
        }

        return $backendServers;
    }
}

