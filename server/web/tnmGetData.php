<?php

require_once __DIR__.'/../config/config.php';

$datalog = TnMBaseLogFactory::getInstance()->getDataLogManager();
$data = $datalog->getData($_REQUEST);

header('Content-Type: text/javascript');
echo $_REQUEST["responseVarName"].'('.json_encode($data).')';
