<?php

class DeviceDetectorFactory {
    private static $instance;
    private $deviceDetectors;

    private function __construct() {
        $this->deviceDetectors = [];
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    public function getDeviceDetector($deviceDetetorServerPath, $propertiesToDetect = null) {
        require_once $deviceDetetorServerPath.'/autoload.php';

        if (empty($propertiesToDetect)) {
            $propertiesToDetect = ['type', 'browserDetails', 'osDetails', 'marketingName', 'brandName'];
        }

        $propertiesToDetectJson = json_encode($propertiesToDetect);
        if (in_array('type', $propertiesToDetect) || in_array('brandName', $propertiesToDetect)) {
            if (!isset($this->deviceDetectors['full'][$propertiesToDetectJson])) {
                $this->deviceDetectors['full'][$propertiesToDetectJson] = new DeviceDetector(
                    $propertiesToDetect,
                    \UAParser\Parser::create(),
                    new MobileDetect()
                );
            }
            return $this->deviceDetectors['full'][$propertiesToDetectJson];
        }
        else {
            if (!isset($this->deviceDetectors['partial'][$propertiesToDetectJson])) {
                $this->deviceDetectors['partial'][$propertiesToDetectJson] = new DeviceDetector(
                    $propertiesToDetect,
                    \UAParser\Parser::create(),
                    new NullMobileDetect()
                );
            }
            return $this->deviceDetectors['partial'][$propertiesToDetectJson];
        }
    }
}
