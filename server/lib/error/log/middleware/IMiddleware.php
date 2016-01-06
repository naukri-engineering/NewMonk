<?php

namespace NewMonk\lib\error\log\middleware;

interface IMiddleware
{
    public function shouldRun($errorLogs);
    public function run($errorLogs);
}
