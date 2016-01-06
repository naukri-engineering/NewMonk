<?php

class ncXCache implements ncCache
{
    public function get($key, $default = null) {
        return $this->has($key) ? xcache_get($key) : $default;
    }

    public function has($key) {
        return xcache_isset($key);
    }

    public function set($key, $data) {
        //string type casting for avoiding memory leak
        xcache_set($key, (string) $data);
    }

    public function remove($key) {
        xcache_unset($key);
    }
}

