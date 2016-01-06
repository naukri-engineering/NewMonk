<?php

namespace NewMonk\lib\error\log\validator;

abstract class AbstractValidator implements IValidator
{
    abstract protected function isValidBasicData($errorLogs);
    abstract protected function isValidException($exception);

    public function filter($errorLogs) {
        $filteredErrorLogs = $errorLogs;
        unset($filteredErrorLogs['exceptions']);

        if ($this->isValidBasicData($errorLogs)) {
            foreach ($errorLogs['exceptions'] as $exception) {
                if ($this->isValidException($exception)) {
                    $filteredErrorLogs['exceptions'][] = $exception;
                }
            }
        }

        if (empty($filteredErrorLogs['exceptions'])) {
            $filteredErrorLogs = [];
        }
        return $filteredErrorLogs;
    }
}
