<?php

namespace NewMonk\web;

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$db = \ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

$appId = $_REQUEST['appId'];
$queryOffset = (int)$_REQUEST['offset'];
$queryCount = (int)$_REQUEST['count'];
if ($queryCount > 50) {
    $queryCount = 50;
}

$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
if (!$_REQUEST['startDate'] || !$_REQUEST['endDate']) {
    $startDate = $endDate = date('Y-m-d', time()-86400);
}
$startTime = $startDate.' 00:00:00';
$endTime = $endDate.' 23:59:59';

$response = [
    'totalDeployments' => 0,
    'deployments' => []
];
$sql = 'SELECT SQL_CALC_FOUND_ROWS app_id, deployed_at, tag, deployed_by, comment '
    .'FROM newmonk_common.deployment '
    .'WHERE app_id = :app_id '
    .'AND deployed_at >= :start_deployed_at '
    .'AND deployed_at <= :end_deployed_at '
    .'ORDER BY deployed_at '
    .'LIMIT :offset, :count';
$st = $db->prepare($sql);
$st->bindValue(':app_id', $appId, \PDO::PARAM_INT);
$st->bindValue(':start_deployed_at', $startTime, \PDO::PARAM_STR);
$st->bindValue(':end_deployed_at', $endTime, \PDO::PARAM_STR);
$st->bindValue(':offset', $queryOffset, \PDO::PARAM_INT);
$st->bindValue(':count', $queryCount, \PDO::PARAM_INT);
$st->execute();
$deployments = $st->fetchAll(\PDO::FETCH_ASSOC);
$st->closeCursor();
foreach ($deployments as $deployment) {
    $response['deployments'][$deployment['deployed_at']] = [
        'appId' => $deployment['app_id'],
        'tag' => $deployment['tag'],
        'deployedBy' => $deployment['deployed_by'],
        'comment' => $deployment['comment']
    ];
}

$sql = 'SELECT FOUND_ROWS() AS totalDeployments';
$st = $db->prepare($sql);
$st->execute();
$row = $st->fetch(\PDO::FETCH_ASSOC);
$response['totalDeployments'] = $row['totalDeployments'];
$st->closeCursor();

header('Content-Type: text/javascript');
echo $_REQUEST['callback'].'('.json_encode($response, true).');';
