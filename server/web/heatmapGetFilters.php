<?php

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$startDate=strtotime($_REQUEST['startDate']);
$endDate=strtotime($_REQUEST['endDate']);
$daterange=new HeatmapValidator($startDate,$endDate);
$daterange->validateDate();
$browserId= implode("," , $_REQUEST['browserId']);
$osId= implode("," , $_REQUEST['osId']);
$resolutionId= implode("," , $_REQUEST['resolutionId']);
$result=HeatmapBaseLogFactory::getInstance()->getFilterLogManager($browserId,$osId,$resolutionId);
$filter=$result->getData($_REQUEST['startDate'],$_REQUEST['endDate'],$_REQUEST['appId']);
echo $_REQUEST["callBack"].'('.json_encode($filter).')';
