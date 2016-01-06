<?php

class DbUtil
{
    public static function getDbNames($appId, $date = null) {
        if (!$date) {
            $mainDbNameSuffix = date('j') % 32;
        } else {
            $mainDbNameSuffix = date('j', strtotime($date)) % 32;
        }

        return [
            'common' => addslashes('nLogger_boomerang_common_'.$appId),
            'main' => addslashes('nLogger_boomerang_'.$appId.'_'.$mainDbNameSuffix),
            'summary' => addslashes('nLogger_boomerang_summary_'.$appId)
        ];
    }

    public static function multipleInsert($db, $queryPrefix, $valuesToInsert, $numColumns) {
        $numRows = count($valuesToInsert) / $numColumns;
        $queryValue = '('.implode(', ', array_fill(0, $numColumns, '?')).')';
        $queryValues = array_fill(0, $numRows, $queryValue);
        if (!empty($queryValues)) {
            $sql = $queryPrefix.implode(', ', $queryValues);
            $st = $db->prepare($sql);
            $st->execute($valuesToInsert);
            $st->closeCursor();
        }
        return $numRows;
    }
}
