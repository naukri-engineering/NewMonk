<?php

namespace NewMonk\lib\error\log;

class Builder
{
    public function build($errorLogs) {
        $expandedErrorLogs = [];
        $expandedErrorLogsCount = 0;

        if (!empty($errorLogs)) {
            $basicData = $this->buildBasicData($errorLogs);
            $environment = $this->buildEnvironment($errorLogs['environment']);
            $exceptions = $this->buildExceptions($errorLogs['exceptions']);
            list($expandedErrorLogs, $expandedErrorLogsCount) = $this->merge(
                $basicData,
                $environment,
                $exceptions
            );
        }
        return [$expandedErrorLogs, $expandedErrorLogsCount];
    }

    private function buildBasicData($errorLogs) {
        $basicData = [
            'source' => $errorLogs['source'],
            'appId' => $errorLogs['appId']
        ];

        if (!empty($errorLogs['uId'])) {
            $basicData['uId'] = $errorLogs['uId'];
        }

        return $basicData;
    }

    private function buildEnvironment($errorLogsEnvironment) {
        return array_merge(
            $this->buildOsEnvironment($errorLogsEnvironment),
            $this->buildBrowserEnvironment($errorLogsEnvironment),
            $this->buildAppEnvironment($errorLogsEnvironment),
            $this->buildDeviceEnvironment($errorLogsEnvironment),
            $this->buildDisplayEnvironment($errorLogsEnvironment)
        );
    }

    private function buildOsEnvironment($errorLogsEnvironment) {
        $environment = [];
        if (isset($errorLogsEnvironment['os'])) {
            $environment['os'] = [
                'name' => $errorLogsEnvironment['os']['name'],
                'version' => $errorLogsEnvironment['os']['version']
            ];
        }
        return $environment;
    }

    private function buildBrowserEnvironment($errorLogsEnvironment) {
        $environment = [];
        if (isset($errorLogsEnvironment['browser'])) {
            $environment['browser'] = [
                'name' => $errorLogsEnvironment['browser']['name'],
                'version' => $errorLogsEnvironment['browser']['version']
            ];
        }
        return $environment;
    }

    private function buildAppEnvironment($errorLogsEnvironment) {
        $environment = [];
        if (isset($errorLogsEnvironment['app'])) {
            $environment['app']['version'] = $errorLogsEnvironment['app']['version'];
        }
        return $environment;
    }

    private function buildDeviceEnvironment($errorLogsEnvironment) {
        $environment = [];
        if (isset($errorLogsEnvironment['device'])) {
            $environment['device']['name'] = $errorLogsEnvironment['device']['name'];
        }
        return $environment;
    }

    private function buildDisplayEnvironment($errorLogsEnvironment) {
        $environment = [];
        if (isset($errorLogsEnvironment['display'])) {
            $environment['display']['width'] = $errorLogsEnvironment['display']['width'];
            $environment['display']['height'] = $errorLogsEnvironment['display']['height'];
        }
        return $environment;
    }

    private function buildExceptions($exceptions) {
        $exceptionProperties = [
            'tag',
            'type',
            'message',
            'code',
            'file',
            'line',
            'stackTrace'
        ];

        $builtExceptions = [];
        foreach ($exceptions as $exception) {
            $builtExceptionsIndex = count($builtExceptions);
            foreach ($exceptionProperties as $exceptionProperty) {
                if (isset($exception[$exceptionProperty])) {
                    $builtExceptions[$builtExceptionsIndex][$exceptionProperty] = $exception[$exceptionProperty];
                }
            }
            $builtExceptions[$builtExceptionsIndex]['count'] = intval($exception['count']) > 0
                ? intval($exception['count'])
                : 1;
            $exceptionTimestamp = $exception['timestamp'] ? $exception['timestamp'] : time();
            $builtExceptions[$builtExceptionsIndex]['gmtTimestamp'] = gmdate('Y-m-d H:i:s', $exceptionTimestamp);
        }

        return $builtExceptions;
    }

    private function merge($basicData, $environment, $exceptions) {
        $expandedErrorLogs = [];
        $expandedErrorLogsCount = 0;

        $expandedErrorLogsPart = array_merge(
            $basicData,
            empty($environment) ? [] : ['environment' => $environment]
        );
        foreach ($exceptions as $exception) {
            $expandedErrorLogs[$expandedErrorLogsCount] = array_merge(
                $expandedErrorLogsPart,
                [
                    'exception' => $exception
                ]
            );
            ++$expandedErrorLogsCount;
        }

        return [$expandedErrorLogs, $expandedErrorLogsCount];
    }
}
