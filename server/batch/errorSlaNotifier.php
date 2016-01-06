<?php

namespace NewMonk\batch;

echo 'Cron started @ '.date('Y-m-d H:i:s')."\n";
require_once __DIR__.'/../config/config.php';

use NewMonk\lib\error\sla\BreachManagerFactory;

$slaBreachManager = BreachManagerFactory::getInstance()->getBreachManager(
    $errorConfigFilePath,
    __DIR__.'/../config/logging.yml',
    $elasticSearchConfigFilePath,
    LOG_DIR
);
$appIdsWithBreaches = $slaBreachManager->notify(time());
if (!empty($appIdsWithBreaches)) {
    echo "\nSLA Breached in: ".implode(', ', $appIdsWithBreaches)."\n\n";
}

echo 'Cron ended @ '.date('Y-m-d H:i:s')."\n";
