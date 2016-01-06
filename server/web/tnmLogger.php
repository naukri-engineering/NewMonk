<?php

require_once __DIR__.'/../config/config.php';

header('HTTP/1.0 204 No Content');
header('Content-Type: text/javascript');

$tnmLogs = json_decode(
    isset($_REQUEST['data']) ? $_REQUEST['data'] : file_get_contents('php://input'),
    true
);
$tnmLogManager =  TnMBaseLogFactory::getInstance()->getInsertLogManager();
$tnmLogManager->insertData($tnmLogs);
