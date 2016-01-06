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
$interval = (strtotime($endTime) - strtotime($startTime) <= 86400*7) ? 'hourly' : 'daily';
$response = array('interval' => $interval, 'loadTimes' => array(), 'pageViews' => array());
try {
    for ($currentHoursElapsedSinceLastEvenYear=$startHoursElapsedSinceLastEvenYear; $currentHoursElapsedSinceLastEvenYear<=$endHoursElapsedSinceLastEvenYear; $currentHoursElapsedSinceLastEvenYear+=24) {
        $currentStartHoursElapsedSinceLastEvenYear = $currentHoursElapsedSinceLastEvenYear;
        $currentEndHoursElapsedSinceLastEvenYear = $currentHoursElapsedSinceLastEvenYear+23;
        if ($interval == 'hourly') {
            $sql = 'SELECT page_summary.hours_elapsed_since_last_even_year, page_summary.page_views AS pageViews, page_summary.avg_load_time AS loadTime, load_time_summary.avg_backend_time AS ttfb
                FROM '.$dbNames['summary'].'.page_summary LEFT JOIN '.$dbNames['summary'].'.load_time_summary
                ON (page_summary.hours_elapsed_since_last_even_year = load_time_summary.hours_elapsed_since_last_even_year
                    AND page_summary.page_id = load_time_summary.page_id
                )
                WHERE page_summary.hours_elapsed_since_last_even_year >= :start_hours_elapsed_since_last_even_year
                AND page_summary.hours_elapsed_since_last_even_year <= :end_hours_elapsed_since_last_even_year
                AND page_summary.page_id = :page_id';
        } else {
            $sql = 'SELECT SUM(page_summary.page_views) AS pageViews, ROUND(SUM(page_summary.avg_load_time*page_summary.page_views)/SUM(page_summary.page_views), 2) AS loadTime, ROUND(SUM(load_time_summary.avg_backend_time*page_summary.page_views)/SUM(page_summary.page_views), 2) AS ttfb
                FROM '.$dbNames['summary'].'.page_summary LEFT JOIN '.$dbNames['summary'].'.load_time_summary
                ON (page_summary.hours_elapsed_since_last_even_year = load_time_summary.hours_elapsed_since_last_even_year
                    AND page_summary.page_id = load_time_summary.page_id
                )
                WHERE page_summary.hours_elapsed_since_last_even_year >= :start_hours_elapsed_since_last_even_year
                AND page_summary.hours_elapsed_since_last_even_year <= :end_hours_elapsed_since_last_even_year
                AND page_summary.page_id = :page_id
                AND load_time_summary.page_id = :page_id';
        }
        $st = $db->prepare($sql);
        $st->bindValue(':start_hours_elapsed_since_last_even_year', $currentStartHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
        $st->bindValue(':end_hours_elapsed_since_last_even_year', $currentEndHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
        $st->bindValue(':page_id', $urlId, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            if ($interval == 'hourly') {
                $hour = $row['hours_elapsed_since_last_even_year'] % 24;
                if ($hour < 10) {
                    $hour = '0'.$hour;
                }
                $timestamp = date('Y-m-d', $lastEvenYearTimestamp + $currentHoursElapsedSinceLastEvenYear*3600).' '.$hour.':00:00';
            } else {
                $timestamp = date('Y-m-d', $lastEvenYearTimestamp + $currentHoursElapsedSinceLastEvenYear*3600);
            }
            $response['loadTimes'][$timestamp] = $row['loadTime'];
            $response['pageViews'][$timestamp] = $row['pageViews'];
            $response['ttfb'][$timestamp] = $row['ttfb'];
        }
    }
    $st->closeCursor();
} catch (Exception $e) {
    die('Error: '.$e->getMessage()."<br />\n");
}

header("Content-Type: text/javascript");
echo 'var '.$_REQUEST['responseVarName'].' = '.json_encode($response, true);
