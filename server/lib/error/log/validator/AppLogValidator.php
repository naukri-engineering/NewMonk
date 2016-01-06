<?php

namespace NewMonk\lib\error\log\validator;

class AppLogValidator extends AbstractValidator
{
    public function __construct() {
    }

    protected function isValidBasicData($errorLogs) {
        return !empty($errorLogs)
            && isset($errorLogs['source']) && $errorLogs['source'] == 'app'
            && isset($errorLogs['appId']) && is_numeric($errorLogs['appId'])
            && isset($errorLogs['environment']['os']['name'])
            && isset($errorLogs['environment']['os']['version'])
            && !isset($errorLogs['environment']['browser']['name'])
            && !isset($errorLogs['environment']['browser']['version'])
            && isset($errorLogs['environment']['app']['version'])
            && isset($errorLogs['environment']['device']['name'])
            && !isset($errorLogs['environment']['display']['width'])
            && !isset($errorLogs['environment']['display']['height']);
    }

    protected function isValidException($exception) {
        $timestampTwoDaysAgo = time() - 86400*2;

        return isset($exception['tag'])
            && (!isset($exception['count']) || $exception['count'] > 0)
            && isset($exception['timestamp']) && $exception['timestamp'] >= $timestampTwoDaysAgo
            && isset($exception['type'])
            && isset($exception['stackTrace']);
    }
}
