<?php

require_once __DIR__.'/../config/config.php';

$page = TnMBaseLogFactory::getInstance()->getPageList();
$pagelist = $page->getPageList($_REQUEST);

header('Content-Type: text/javascript');
echo $_REQUEST["responseVarName"].'('.json_encode($pagelist).')';
