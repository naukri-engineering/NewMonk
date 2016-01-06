<?php

interface ncCache
{
    public function get($key, $default = null);

    public function has($key);

    public function set($key, $data);

    public function remove($key);
}

