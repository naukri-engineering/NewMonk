<?php

namespace NewMonk\web;

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$db = \ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

$response = [
    'domains' => [],
    'apps' => []
];

$sql = 'SELECT SQL_CACHE domain_id, domain_name '
    .'FROM nLogger.domains '
    .'WHERE domains.display = \'y\' '
    .'ORDER BY domain_name';
$st = $db->prepare($sql);
$st->execute();
$apps = $st->fetchAll(\PDO::FETCH_ASSOC);
$st->closeCursor();
foreach ($apps as $app) {
    $response['domains'][] = [
        'id' => $app['domain_id'],
        'name' => $app['domain_name']
    ];
}

$sql = 'SELECT SQL_CACHE apps.domain_id, app_id, app_name '
    .'FROM nLogger.domains, nLogger.apps '
    .'WHERE domains.domain_id = apps.domain_id '
    .'AND domains.display = \'y\' '
    .'AND apps.display = \'y\' '
    .'ORDER BY domain_name, app_name';
$st = $db->prepare($sql);
$st->execute();
$apps = $st->fetchAll(\PDO::FETCH_ASSOC);
$st->closeCursor();
foreach ($apps as $app) {
    $response['apps'][$app['domain_id']][] = [
        'id' => $app['app_id'],
        'name' => $app['app_name']
    ];
}

header('Content-Type: text/javascript');
echo $_REQUEST['callback'].'('.json_encode($response, true).');';
