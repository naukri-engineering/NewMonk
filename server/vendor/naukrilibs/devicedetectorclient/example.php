<?php

$userAgent = 'Mozilla/5.0 (Linux; U; Android 4.1.2; en-us; GT-I8552 Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
$deviceDetector = DeviceDetectorFactory::getInstance()->getDeviceDetector(
    '/apps/DeviceDetector/current',
    ['browserDetails','osDetails','type','marketingName', 'brandName']
);
$deviceDetails = $deviceDetector->getDeviceDetails($userAgent);
print_r($deviceDetails);
