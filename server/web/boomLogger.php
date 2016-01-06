<?php

namespace NewMonk\web;

require_once __DIR__.'/../config/config.php';

use NewMonk\lib\boomerang\logger\Logger;

header('HTTP/1.0 204 No Content');
header('Content-Type: text/javascript');

$boomData = json_decode($_REQUEST['data'], true);
$loggingConfig = \ncYaml::load(__DIR__.'/../config/logging.yml');
$boomWhitelistedAppIds = $loggingConfig['boomerang'];
if (in_array($boomData['appId'], $boomWhitelistedAppIds['appIds'])) {
    $deviceDetector = \DeviceDetectorFactory::getInstance()->getDeviceDetector(
        $deviceDetetorServerPath,
        ['browserDetails', 'osDetails', 'type']
    );
    $boomLogger = new Logger($deviceDetector);
    if ($boomLogger->logBoomr($boomData)) {
        if (!$boomData['t_done']) {
            $boomData['t_done'] = 0;
        }
    }
}
