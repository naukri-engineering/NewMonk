<?php
/**
 * Description of CityIPSpatialDatabaseException
 *
 * @author pulkit
 */
class CityIPSpatialDatabaseException extends GeoIPDatabaseException {
    /**
     * @param $message
     * @param $exception
     * @param $code
     */
    public function __construct($message = null, $exception = null, $code = null) {
        parent::__construct($message, $exception, $code);
    }
}