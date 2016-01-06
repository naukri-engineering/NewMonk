<?php

class MobileDetect extends Mobile_Detect
{
	public function __construct() {
		$customDevices = [];
        parent::$phoneDevices = $this->mergeArraysOrRegularExpressions(parent::$phoneDevices, $customDevices);
	}

    private function mergeArraysOrRegularExpressions($array1, $array2) {
        foreach ($array2 as $brandName=>$regex) {
            if (isset($array1[$brandName])) {
                $array1[$brandName] .= '|'.$regex;
            }
            else {
                $array1[$brandName] = $regex;
            }
        }
        return $array1;
    }

    public function getBrandName($userAgent) {
        $this->setDetectionType(parent::DETECTION_TYPE_MOBILE);
        foreach (parent::$phoneDevices as $brandName=>$regex) {
            if ($this->match($regex, $userAgent)) {
                return $brandName;
            }
        }
        return false;
    }
}
