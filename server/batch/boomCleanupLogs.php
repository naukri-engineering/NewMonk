<?php

echo 'Cron started @ '.date('Y-m-d H:i:s')."\n";
try {
    require_once __DIR__.'/../config/config.php';

    $tomorrowsDate = date('Y-m-d', time()+86400);

    $db = ncDatabaseManager::getInstance()->getDatabase('nLogger_cleanup')->getConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

    $loggingConfig = ncYaml::load(__DIR__.'/../config/logging.yml');
    $boomWhitelistedAppIds = $loggingConfig['boomerang'];

    foreach ($boomWhitelistedAppIds['appIds'] as $appId) {
        $dbNames = DbUtil::getDbNames($appId, $tomorrowsDate);
        echo 'Processing '.implode(', ', $dbNames).' for '.$tomorrowsDate."\n";
        cleanupMainDatabase($db, $dbNames);
        echo "\n----------------\n";
    }
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."<br />\n";
    echo 'Trace: ';print_r($e->getTrace());echo "<br />\n";
    exit($e->getCode());
}
echo 'Cron ended @ '.date('Y-m-d H:i:s')."\n";



function cleanupMainDatabase($db, $dbNames) {
    $tablesToTruncate = array(
        'bandwidth_latency',
        'custom_time',
        'env',
        'load_time',
        'main',
        'url',
        'url_dynamic',
        'url_static',
    );

    foreach ($tablesToTruncate as $tableToTruncate) {
        echo 'Truncating '.$dbNames['main'].'.'.$tableToTruncate."\n";
        $sql = 'TRUNCATE TABLE '.$dbNames['main'].'.'.$tableToTruncate;
        $st = $db->prepare($sql);
        $st->execute();
    }
    $st->closeCursor();
}
