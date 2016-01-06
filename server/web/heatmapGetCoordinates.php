<?php

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

ini_set('memory_limit', '1024M');

$browserId=$_REQUEST['browserId'];
$osId=$_REQUEST['osId'];
$resolutionId=$_REQUEST['resolutionId'];
$startDate=strtotime($_REQUEST['startDate']);
$endDate=strtotime($_REQUEST['endDate']);
$daterange=new HeatmapValidator($startDate,$endDate);
$daterange->validateDate();
$coordinfo=HeatmapBaseLogFactory::getInstance()->getCoordinateLog($browserId,$osId,$resolutionId);
$coordinates=$coordinfo->getCoordinateInfo($_REQUEST,$browserId,$osId,$resolutionId);
echo $_REQUEST["callBack"].'('.json_encode($coordinates).')';
