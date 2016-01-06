<?php

namespace NewMonk\lib\error\log\middleware;

class Chain
{
    private $middlewares;

    public function __construct(array $middlewares) {
        $this->middlewares = $middlewares;
    }

    public function run($errorLogs) {
        foreach ($this->middlewares as $middleware) {
            if ($middleware->shouldRun($errorLogs)) {
                $errorLogs = $middleware->run($errorLogs);
            }
        }
        return $errorLogs;
    }
}
