<?php

class DeviceDetector
{
    private $propertiesToDetect;
    private $mbDetect;
    private $uaParser;

    public function __construct($propertiesToDetect, $uaParser, $mbDetect) {
        $this->propertiesToDetect = $propertiesToDetect;
        $this->mbDetect = $mbDetect;
        $this->uaParser = $uaParser;
    }

    public function getDeviceDetails($ua = null) {
        if (!isset($ua) || $ua === null) {
            $ua = $_SERVER['HTTP_USER_AGENT'];
        }
        $uaAndParsedDetails = [
            'ua' => $ua,
            'uaParserParsedDetails' => $this->uaParser->parse($ua)
        ];
        $this->mbDetect->setUserAgent($ua);

        $deviceDetails = [];
        foreach ($this->propertiesToDetect as $propertyToDetect) {
            $deviceDetails[$propertyToDetect] = call_user_func([$this, 'get'.$propertyToDetect], $uaAndParsedDetails);
        }
        return $deviceDetails;
    }

    public function getType($uaAndParsedDetails) {
        if ($this->mbDetect->isMobile()) {
            return "mobile";
        } else if ($this->mbDetect->isTablet()) {
            return "tablet";
        } else {
            return "desktop";
        }
    }

    private function getBrowserDetails($uaAndParsedDetails) {
        $major = $uaAndParsedDetails['uaParserParsedDetails']->ua->major;
        $minor = $uaAndParsedDetails['uaParserParsedDetails']->ua->minor;
        $patch = $uaAndParsedDetails['uaParserParsedDetails']->ua->patch;
        return [
            "name" => $uaAndParsedDetails['uaParserParsedDetails']->ua->family,
            "version" => $this->getFormattedVersion($major, $minor, $patch),
            "major" => $major,
            "minor" => $minor,
            "patch" => $patch
        ];
    }

    private function getOsDetails($uaAndParsedDetails) {
        $major = $uaAndParsedDetails['uaParserParsedDetails']->os->major;
        $minor = $uaAndParsedDetails['uaParserParsedDetails']->os->minor;
        $patch = $uaAndParsedDetails['uaParserParsedDetails']->os->patch;
        return [
            "name" => $uaAndParsedDetails['uaParserParsedDetails']->os->family,
            "version" => $this->getFormattedVersion($major, $minor, $patch),
            "major" => $major,
            "minor" => $minor,
            "patch" => $patch
        ];
    }

    private function getMarketingName($uaAndParsedDetails) {
        return $uaAndParsedDetails['uaParserParsedDetails']->device->family;
    }

    private function getBrandName($uaAndParsedDetails) {
        return $this->mbDetect->getBrandName($uaAndParsedDetails['ua']);
    }

    private function getFormattedVersion($major, $minor, $patch) {
        $version = $major;
        $hasMinor = ($minor != '' || $minor === 0);
        $hasPatch = $hasMinor && ($patch != '' || $patch === 0);
        if ($hasMinor) {
            $version .= '.'.$minor;
        }
        if ($hasPatch) {
            $version .= '.'.$patch;
        }

        return $version;
    }
}
