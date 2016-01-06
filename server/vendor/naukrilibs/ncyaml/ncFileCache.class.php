<?php

class ncFileCache implements ncCache
{
    private $cacheDir;

    public function __construct($cacheDir) {
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0777);
        }

        $this->cacheDir = $cacheDir;
    }

    public function get($key, $default = null) {
        return $this->has($key) ? file_get_contents($this->getFileName($key)) : $default;
    }

    public function has($key) {
        $filename = $this->getFileName($key);
        return (file_exists($filename) && file_get_contents($filename) != '');
    }

    public function set($key, $data) {
        @file_put_contents($this->getFileName($key), $data, LOCK_EX);
    }

    public function remove($key) {
        @unlink($this->getFileName($key));
    }

    private function getFilename($key) {
        return $this->cacheDir."/$key.cache";
    }
}

