<?php

namespace NewMonk\lib\error\logger;

class Queue
{
    private $cache;

    public function __construct($cache) {
        $this->cache = $cache;
    }

    public function saveLogs($errorLogs) {
        $this->cache->rPush('error_queue', $errorLogs);
    }

    public function getNextLog() {
        return $this->cache->lPop('error_queue');
    }
}
