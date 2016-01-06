<?php

namespace NewMonk\lib\error\log\validator;

class BrowserLogValidator extends AbstractValidator
{
    public function __construct() {
    }

    protected function isValidBasicData($errorLogs) {
        return !empty($errorLogs)
            && isset($errorLogs['source']) && $errorLogs['source'] == 'browser'
            && isset($errorLogs['appId']) && is_numeric($errorLogs['appId'])
            && isset($errorLogs['environment']['os']['name'])
            && isset($errorLogs['environment']['os']['version'])
            && isset($errorLogs['environment']['browser']['name'])
            && isset($errorLogs['environment']['browser']['version'])
            && !isset($errorLogs['environment']['app']['version'])
            && !isset($errorLogs['environment']['device']['name'])
            && isset($errorLogs['environment']['display']['width'])
            && is_numeric($errorLogs['environment']['display']['width'])
            && isset($errorLogs['environment']['display']['height'])
            && is_numeric($errorLogs['environment']['display']['width']);
    }

    protected function isValidException($exception) {
        return isset($exception['tag'])
            && (!isset($exception['count']) || $exception['count'] > 0)
            && !isset($exception['timestamp'])
            && isset($exception['type']);
    }
}
