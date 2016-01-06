<?php

namespace NewMonk\lib\common\error;

use NewMonk\lib\util\ILogger;

class LoggerAndDisplayer implements \Psr\Log\LoggerInterface
{
    private $logger;

    public function __construct(ILogger $logger) {
        $this->logger = $logger;
    }

    public function emergency($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function alert($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function critical($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function error($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function warning($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function notice($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function info($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function debug($message, array $context = array()) {
        $this->log(null, $message, $context);
    }

    public function log($level, $message, array $context = array()) {
        echo $this->getErrorPage();
        $this->logger->log($message);
    }

    private function getErrorPage() {
        return '';
    }
}
