<?php

abstract class NLoggerBaseDAO
{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getFoundRows() {
        $sql = 'SELECT FOUND_ROWS() AS total_row_count';
        $st = $this->db->prepare($sql);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        $st->closeCursor();
        return $row['total_row_count'];
    }

}

