<?php

namespace NewMonk\lib\error\log\middleware;

use NewMonk\lib\error\config\Factory as ConfigFactory;

class ChainFactory
{
    private static $instance;
    private $middlewareChainInstance;

    private function __construct() {
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function getChain($errorConfigFilePath) {
        if (empty($this->middlewareChainInstance)) {
            $middlewareConfig = ConfigFactory::getInstance()->getManager($errorConfigFilePath)
                ->getConfigFor('middlewares');

            $middlewares = [];
            foreach ($middlewareConfig as $middlewareConstructionParameters) {
                $reflector = new \ReflectionClass($middlewareConstructionParameters['class']);
                $middleware = $reflector->newInstanceArgs($middlewareConstructionParameters['parameters']);
                $middlewares[] = $middleware;
            }
            $this->middlewareChainInstance = new Chain($middlewares);
        }
        return $this->middlewareChainInstance;
    }
}
