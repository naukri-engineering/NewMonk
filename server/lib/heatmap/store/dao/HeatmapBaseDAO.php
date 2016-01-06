<?php

abstract class HeatmapBaseDAO
{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }
}
