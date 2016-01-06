<?php

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$browserId=$_REQUEST['browserId'];
$osId=$_REQUEST['osId'];
$resolutionId=$_REQUEST['resolutionId'];
$startDate=strtotime($_REQUEST['startDate']);
$endDate=strtotime($_REQUEST['endDate']);
$daterange=new HeatmapValidator($startDate,$endDate);
$daterange->validateDate();
$urlInfo=HeatmapBaseLogFactory::getInstance()->getUrlLog($browserId,$osId,$resolutionId);
$url=$urlInfo->getUrlInfo($_REQUEST,$browserId,$osId,$resolutionId);
echo $_REQUEST["callBack"].'('.json_encode($url).')';
