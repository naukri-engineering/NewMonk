<?php

class ncApcCache implements ncCache
{
    public function get($key, $default = null) {
        return $this->has($key) ? apc_fetch($key) : $default;
    }

    public function has($key) {
        return apc_exists($key);
    }

    public function set($key, $data) {
        apc_store($key, $data);
    }

    public function remove($key) {
        apc_delete($key);
    }
}

