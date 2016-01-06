<?php

namespace NewMonk\batch;

echo 'Cron started @ '.date('Y-m-d H:i:s')."\n";
require_once __DIR__.'/../config/config.php';

use NewMonk\lib\boomerang\sla\BreachManagerFactory;

$slaBreachManager = BreachManagerFactory::getInstance()->getBreachManager(
    $boomSlaConfigFilePath,
    __DIR__.'/../config/logging.yml',
    LOG_DIR
);
$appIdsWithBreaches = $slaBreachManager->notify(time()-86400);
if (!empty($appIdsWithBreaches)) {
    echo "\nSLA Breached in: ".implode(', ', $appIdsWithBreaches)."\n\n";
}

echo 'Cron ended @ '.date('Y-m-d H:i:s')."\n";
