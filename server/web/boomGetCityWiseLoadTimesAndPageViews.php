<?php

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$db = ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

$appId = $_REQUEST['appId'];
$urlId = $_REQUEST['urlId'];
$countryId = $_REQUEST['countryId'];

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
    $sql = 'SELECT country.country_id as id, country.code as country_code, country.name AS country_label, city.name AS city_label, SUM(city_summary.page_views) AS pageViews, ROUND(SUM(city_summary.avg_load_time*city_summary.page_views)/SUM(city_summary.page_views), 2) AS loadTime
        FROM '.$dbNames['summary'].'.city_summary, newmonk_common.country, newmonk_common.city
        WHERE hours_elapsed_since_last_even_year >= :start_hours_elapsed_since_last_even_year
        AND hours_elapsed_since_last_even_year <= :end_hours_elapsed_since_last_even_year
        AND page_id = :page_id
        AND country.country_id = :country_id
        AND city_summary.city_id = city.city_id
        AND city.country_id = country.country_id
        GROUP BY city.city_id';
    $st = $db->prepare($sql);
    $st->bindValue(':start_hours_elapsed_since_last_even_year', $startHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
    $st->bindValue(':end_hours_elapsed_since_last_even_year', $endHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
    $st->bindValue(':page_id', $urlId, PDO::PARAM_INT);
    $st->bindValue(':country_id', $countryId, PDO::PARAM_INT);
    $st->execute();
    $response = $st->fetchAll(PDO::FETCH_ASSOC);
    $st->closeCursor();
} catch (Exception $e) {
    die('Error: '.$e->getMessage()."<br />\n");
}

$jsonSerializer = new JsonSerializer();
header("Content-Type: text/javascript");
echo 'var '.$_REQUEST['responseVarName'].' = '.$jsonSerializer->serialize($response);
