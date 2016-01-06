<?php

class NullMobileDetect
{
    public function isTablet($userAgent = null, $httpHeaders = null) {
        return null;
    }

    public function isMobile($userAgent = null, $httpHeaders = null) {
        return null;
    }

    public function getBrandName($userAgent) {
        return false;
    }

    public function setUserAgent($userAgent = null) {
    }
}
