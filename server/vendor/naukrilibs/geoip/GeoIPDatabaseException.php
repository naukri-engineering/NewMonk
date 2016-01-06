<?php
/**
 * Description of GeoIPDatabaseException
 *
 * @author pulkit
 */
class GeoIPDatabaseException extends PEAR_EXCEPTION {
    /**
     * @param $message
     * @param $exception
     * @param $code
     */
    public function __construct($message = null, $exception = null, $code = null) {
        parent::__construct($message, $exception, $code);
    }
}