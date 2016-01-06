<?php

/**
 * Description of CountryIPDatabase
 *
 * @author pulkit
 */
class CountryIPDatabase
{
    public static $instance;
    protected $db;

    protected function __construct($node = "geoip") {
        try {
            if (!empty($node)) {
                $this->db = ncDatabaseManager::getInstance()->getDatabase($node)->getConnection();
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            } else {
                throw new CountryIPDatabaseException("Missing DB Node Name", $e);
            }
        } catch (Exception $e) {
            throw new CountryIPDatabaseException("Unable to construct CountryIP database.", $e);
        }
    }

    public static function getInstance($node) {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class($node);
        }
        return self::$instance;
    }

    /**
     * returns back short name for location
     * @param string $ipAddress
     * @return string 
     */
    public function getLocationShortName($ipAddress = null) {
        if (!empty($ipAddress)) {
            $intIP = sprintf("%u", ip2long($ipAddress));   // Converting string to long[datatype]
            try {
                $sql = 'SELECT aeipShortName 
                    FROM GeoIP_108_20110830 
                    WHERE aeipFromNum <= :intIP 
                    AND aeipToNum >= :intIP';
                $res = $this->db->prepare($sql);
                $res->bindValue(":intIP", $intIP);
                $res->execute();
                if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $shortName = $row['aeipShortName'];
                }
                $res->closeCursor();
                return $shortName;
            } catch (PDOException $e) {
                throw new CountryIPDatabaseException($e);
            } catch (Exception $e) {
                throw new CountryIPDatabaseException("Unable to fetch IP Short Name: ", $e);
            }
        } else {
            throw new CountryIPDatabaseException("IP Address missing.", $e);
        }
    }

    /**
     * returns back long name for location
     * @param string $ipAddress
     * @return string 
     */
    public function getLocationLongName($ipAddress = null) {
        if (!empty($ipAddress)) {
            $intIP = sprintf("%u", ip2long($ipAddress));   // Converting string to long[datatype]
            try {
                $sql = 'SELECT aeipLongName 
                    FROM GeoIP_108_20110830 
                    WHERE aeipFromNum <= :intIP 
                    AND aeipToNum >= :intIP';
                $res = $this->db->prepare($sql);
                $res->bindValue(":intIP", $intIP);
                $res->execute();
                if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $longName = $row['aeipLongName'];
                }
                $res->closeCursor();
                return $longName;
            } catch (PDOException $e) {
                throw new CountryIPDatabaseException($e);
            } catch (Exception $e) {
                throw new CountryIPDatabaseException("Unable to fetch IP Short Name: ", $e);
            }
        } else {
            throw new CountryIPDatabaseException("IP Address missing.", $e);
        }
    }
}