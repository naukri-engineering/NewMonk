<?php

namespace NewMonk\lib\error\log\validator;

class Factory
{
    private static $instance;
    private $validators;

    private function __construct() {
        $this->validators = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getValidator($errorLogs) {
        $source = $errorLogs['source'];
        if (empty($this->validators[$source])) {
            if ($source == 'app') {
                $this->validators[$source] = new AppLogValidator();
            } else if ($source == 'browser') {
                $this->validators[$source] = new BrowserLogValidator();
            } else if ($source == 'server') {
                $this->validators[$source] = new ServerLogValidator();
            }
        }
        return $this->validators[$source];
    }
}
