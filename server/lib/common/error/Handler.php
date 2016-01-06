<?php

namespace NewMonk\lib\common\error;

class Handler
{
    private $runner;

    public function __construct(\League\BooBoo\Runner $runner) {
        $this->runner = $runner;
    }

    public function register() {
        $this->runner->silenceAllErrors(true);
        $this->runner->register();
    }
}
