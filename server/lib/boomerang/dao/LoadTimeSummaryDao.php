<?php

namespace NewMonk\lib\boomerang\dao;

class LoadTimeSummaryDao
{
    public function __construct($db, $dbUtilClassName) {
        $this->db = $db;
        $this->dbUtilClassName = $dbUtilClassName;
    }

    public function getByPageId($appId, $pageId, $startHoursElapsedSinceLastEvenYear, $endHoursElapsedSinceLastEvenYear) {
        $dbUtilClassName = $this->dbUtilClassName;
        $dbNames = $dbUtilClassName::getDbNames($appId);
        $sqlGetStats = 'SELECT hours_elapsed_since_last_even_year % 24 AS hourOfDay, avg_backend_time AS ttfb, avg_done_time AS loadTime '
        .'FROM '.$dbNames['summary'].'.load_time_summary '
        .'WHERE hours_elapsed_since_last_even_year >= :start_hours_elapsed_since_last_even_year '
        .'AND hours_elapsed_since_last_even_year <= :end_hours_elapsed_since_last_even_year '
        .'AND page_id = :page_id '
        .'ORDER BY hours_elapsed_since_last_even_year ASC';
        $statementGetStats = $this->db->prepare($sqlGetStats);
        $statementGetStats->bindValue(':start_hours_elapsed_since_last_even_year', $startHoursElapsedSinceLastEvenYear, \PDO::PARAM_INT);
        $statementGetStats->bindValue(':end_hours_elapsed_since_last_even_year', $endHoursElapsedSinceLastEvenYear, \PDO::PARAM_INT);
        $statementGetStats->bindValue(':page_id', $pageId, \PDO::PARAM_INT);
        $statementGetStats->execute();
        $rowsGetStats = $statementGetStats->fetchAll(\PDO::FETCH_ASSOC);
        $statementGetStats->closeCursor();
        return $rowsGetStats;
    }
}
