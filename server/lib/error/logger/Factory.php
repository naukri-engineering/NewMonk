<?php

namespace NewMonk\lib\error\logger;

use nccache\CacheClientFactory;

class Factory
{
    private static $instance;
    private $errorLoggers;

    private function __construct() {
        $this->errorLoggers = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getErrorLogger($type) {
        if (!isset($this->errorLoggers[$type])) {
            if ($type == 'queue') {
                $cache = CacheClientFactory::getInstance()->getCacheClient('error_queue');
                $this->errorLoggers[$type] = new Queue($cache);
            }
        }
        return $this->errorLoggers[$type];
    }
}
