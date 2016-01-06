<?php

class HeatmapValidator
{
    private $startDate;
    private $endDate;

    public function __construct($startDate,$endDate) {
        $this->startDate=$startDate;
        $this->endDate=$endDate;
    }

    public function validateDate() {
        $datediff = ($this->endDate- $this->startDate);
        if (($datediff/86400)>30) {
            echo "no. of days selected is greater than 30";
            die;
        }
    }
}
