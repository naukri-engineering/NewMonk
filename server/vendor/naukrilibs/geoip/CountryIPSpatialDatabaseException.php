<?php
/**
 * Description of CountryIPSpatialDatabaseException
 *
 * @author pulkit
 */
class CountryIPSpatialDatabaseException extends GeoIPDatabaseException
{
    /**
     * @param $message
     * @param $exception
     * @param $code
     */
    public function __construct($message = null, $exception = null, $code = null) {
        parent::__construct($message, $exception, $code);
    }
}
