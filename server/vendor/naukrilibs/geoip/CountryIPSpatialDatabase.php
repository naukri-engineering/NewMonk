<?php

/**
 * Description of CountryIPSpatialDatabase
 *
 * @author pulkit
 */
class CountryIPSpatialDatabase
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
                throw new CountryIPSpatialDatabaseException("Missing DB Node Name", $e);
            }
        } catch (Exception $e) {
            throw new CountryIPSpatialDatabaseException("Unable to construct CountryIP database.", $e);
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
            try {
                $sql = 'SELECT country_code 
                        FROM ip_country 
                        WHERE MBRCONTAINS(ip_poly, POINTFROMWKB(POINT(INET_ATON(:ipAddress), 0)))';
                $res = $this->db->prepare($sql);
                $res->bindValue(":ipAddress", $ipAddress);
                $res->execute();
                if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $shortName = $row['country_code'];
                }
                $res->closeCursor();
                return $shortName;
            } catch (PDOException $e) {
                throw new CountryIPSpatialDatabaseException($e);
            } catch (Exception $e) {
                throw new CountryIPSpatialDatabaseException("Unable to fetch IP Short Name: ", $e);
            }
        } else {
            throw new CountryIPSpatialDatabaseException("IP Address missing.", $e);
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
                $sql = 'SELECT country_name
                        FROM ip_country 
                        WHERE MBRCONTAINS(ip_poly, POINTFROMWKB(POINT(INET_ATON(:ipAddress), 0)))';
                $res = $this->db->prepare($sql);
                $res->bindValue(":ipAddress", $ipAddress);
                $res->execute();
                if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $longName = $row['country_name'];
                }
                $res->closeCursor();
                return $longName;
            } catch (PDOException $e) {
                throw new CountryIPSpatialDatabaseException($e);
            } catch (Exception $e) {
                throw new CountryIPSpatialDatabaseException("Unable to fetch IP Short Name: ", $e);
            }
        } else {
            throw new CountryIPSpatialDatabaseException("IP Address missing.", $e);
        }
    }
}
