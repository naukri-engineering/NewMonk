<?php

class ncNullCache implements ncCache
{
    public function get($key, $default = null) {
        return $default;
    }

    public function has($key) {
        return false;
    }

    public function set($key, $data) {

    }

    public function remove($key) {

    }
}

