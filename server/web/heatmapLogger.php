<?php
require_once __DIR__.'/../config/config.php';

$deviceDetector = DeviceDetectorFactory::getInstance()->getDeviceDetector(
    $deviceDetetorServerPath,
    ['browserDetails', 'osDetails']
);
$deviceDetails = $deviceDetector->getDeviceDetails($ua);
$currentBrowser = $deviceDetails['browserDetails']["name"]." ".$deviceDetails['browserDetails']["version"];
$currentOs = $deviceDetails['osDetails']["name"]." ".$deviceDetails['osDetails']["version"];
$data = json_decode($_REQUEST["data"], true);
$browser = ["browser"=>$currentBrowser, "os"=>$currentOs];
$heatMapData = array_merge($data, $browser);
if ($heatMapData["screenRes"]&& $heatMapData["appId"]) {
    $objLogManager = HeatmapBaseLogFactory::getInstance()->getInsertLogManager();
    $objLogManager->insertHeatMapDataInfo($heatMapData);
}
