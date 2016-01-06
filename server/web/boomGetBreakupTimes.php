<?php

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$db = ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

$appId = $_REQUEST['appId'];
$urlId = $_REQUEST['urlId'];

$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
if (!$_REQUEST['startDate'] || !$_REQUEST['endDate']) {
    $startDate = $endDate = date('Y-m-d', time()-86400);
}
$startTime = $startDate.' 00:00:00';
$endTime = $endDate.' 23:59:59';
$lastEvenYear = (date('Y') % 2 === 0) ? date('Y') : date('Y') - 1;
$lastEvenYearTimestamp = mktime(0, 0, 0, 1, 1, $lastEvenYear);
$startHoursElapsedSinceLastEvenYear = floor((strtotime($startTime)-$lastEvenYearTimestamp) / 3600);
$endHoursElapsedSinceLastEvenYear = floor((strtotime($endTime)-$lastEvenYearTimestamp) / 3600);

$dbNames = DbUtil::getDbNames($appId);
$response = array();
try {
    $sql = 'SELECT ROUND(AVG(avg_network_time), 2) AS networkTime, ROUND(AVG(avg_backend_time), 2) AS backendTime, ROUND(AVG(avg_frontend_time), 2) AS frontendTime, ROUND(AVG(avg_dom_ready_time), 2) AS domReadyTime, ROUND(AVG(avg_done_time), 2) AS doneTime
        FROM '.$dbNames['summary'].'.load_time_summary
        WHERE hours_elapsed_since_last_even_year >= :start_hours_elapsed_since_last_even_year
        AND hours_elapsed_since_last_even_year <= :end_hours_elapsed_since_last_even_year
        AND page_id = :page_id';
    $st = $db->prepare($sql);
    $st->bindValue(':start_hours_elapsed_since_last_even_year', $startHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
    $st->bindValue(':end_hours_elapsed_since_last_even_year', $endHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
    $st->bindValue(':page_id', $urlId, PDO::PARAM_INT);
    $st->execute();
    $row = $st->fetchAll(PDO::FETCH_ASSOC);
    $response = $row[0];
    $st->closeCursor();
} catch (Exception $e) {
    die('Error: '.$e->getMessage()."<br />\n");
}

header("Content-Type: text/javascript");
echo 'var '.$_REQUEST['responseVarName'].' = '.json_encode($response, true);
