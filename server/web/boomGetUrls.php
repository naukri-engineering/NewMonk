<?php

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$db = ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

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
$lastEvenYear = (date('Y') % 2 === 0) ? date('Y') : date('Y') - 1;
$lastEvenYearTimestamp = mktime(0, 0, 0, 1, 1, $lastEvenYear);
$startHoursElapsedSinceLastEvenYear = floor((strtotime($startTime)-$lastEvenYearTimestamp) / 3600);
$endHoursElapsedSinceLastEvenYear = floor((strtotime($endTime)-$lastEvenYearTimestamp) / 3600);

$query = $_REQUEST['query'];
$shouldSearchByPageLabel = isset($query) && strlen($query) >= 3;

$dbNames = DbUtil::getDbNames($appId);
$response = array();
try {
    if ($shouldSearchByPageLabel) {
        // WARNING: I am intentionally NOT selecting page.name field in this query, since it is a big field, and not selecting it actually makes this query very faster.
        $sql = 'SELECT SQL_CALC_FOUND_ROWS page_summary.page_id AS urlId, SUM(page_views) AS pageViews, ROUND(SUM(avg_load_time*page_views)/SUM(page_views), 2) AS loadTime
            FROM '.$dbNames['summary'].'.page_summary, '.$dbNames['summary'].'.page
            WHERE hours_elapsed_since_last_even_year >= :start_hours_elapsed_since_last_even_year
            AND hours_elapsed_since_last_even_year <= :end_hours_elapsed_since_last_even_year
            AND page_summary.page_id = page.page_id
            AND page.name LIKE :query
            GROUP BY page_summary.page_id
            ORDER BY pageViews DESC
            LIMIT :offset, :count';
        $st = $db->prepare($sql);
        $st->bindValue(':start_hours_elapsed_since_last_even_year', $startHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
        $st->bindValue(':end_hours_elapsed_since_last_even_year', $endHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
        $st->bindValue(':query', '%'.$query.'%', PDO::PARAM_INT);
        $st->bindValue(':offset', $queryOffset, PDO::PARAM_INT);
        $st->bindValue(':count', $queryCount, PDO::PARAM_INT);
    } else {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS page_id AS urlId, SUM(page_views) AS pageViews, ROUND(SUM(avg_load_time*page_views)/SUM(page_views), 2) AS loadTime
            FROM '.$dbNames['summary'].'.page_summary
            WHERE hours_elapsed_since_last_even_year >= :start_hours_elapsed_since_last_even_year
            AND hours_elapsed_since_last_even_year <= :end_hours_elapsed_since_last_even_year
            GROUP BY page_id
            ORDER BY pageViews DESC
            LIMIT :offset, :count';
        $st = $db->prepare($sql);
        $st->bindValue(':start_hours_elapsed_since_last_even_year', $startHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
        $st->bindValue(':end_hours_elapsed_since_last_even_year', $endHoursElapsedSinceLastEvenYear, PDO::PARAM_INT);
        $st->bindValue(':offset', $queryOffset, PDO::PARAM_INT);
        $st->bindValue(':count', $queryCount, PDO::PARAM_INT);
    }
    $st->execute();
    $response['urls'] = $st->fetchAll(PDO::FETCH_ASSOC);
    $st->closeCursor();

    $sql = "SELECT FOUND_ROWS() AS totalUrls";
    $st = $db->prepare($sql);
    $st->execute();
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $response['totalUrls'] = $row['totalUrls'];
    $st->closeCursor();

    foreach ($response['urls'] as &$pageSummary) {
        $sql = 'SELECT name
            FROM '.$dbNames['summary'].'.page
            WHERE page_id = :page_id';
            $st = $db->prepare($sql);
        $st->bindValue(':page_id', $pageSummary['urlId'], PDO::PARAM_INT);
        $st->execute();
        $urlLabel = $st->fetch(PDO::FETCH_ASSOC);
        $st->closeCursor();
        $pageSummary['url'] = $urlLabel['name'];
    }
    unset($url);
} catch (Exception $e) {
    die('Error: '.$e->getMessage()."<br />\n");
}

header("Content-Type: text/javascript");
echo 'var '.$_REQUEST['responseVarName'].' = '.json_encode($response, true);
