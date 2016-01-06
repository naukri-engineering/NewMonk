<?php

/**
 * Description of CityIPSpatialDatabase
 *
 * @author pulkit
 */
class CityIPSpatialDatabase
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
                throw new CityIPSpatialDatabaseException("Missing DB Node Name", $e);
            }
        } catch (Exception $e) {
            throw new CityIPSpatialDatabaseException("Unable to construct CityIP database.", $e);
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
     * returns back long name for location
     * @param string $ipAddress
     * @return string
     */
    public function getLocationDetailsByIP($ipAddress = null) {
        if (!empty($ipAddress)) {
            $intIP = sprintf("%u", ip2long($ipAddress));   // Converting string to long[datatype]
            try {
                $sql = 'SELECT location.city_name, location.country_code, location.lat, location.lon
                        FROM blocks, location
                        WHERE MBRCONTAINS(ip_poly, POINTFROMWKB(POINT(INET_ATON(:ipAddress), 0)))
                        AND blocks.locid = location.locid
                        LIMIT 1';
                $res = $this->db->prepare($sql);
                $res->bindValue(":ipAddress", $ipAddress);
                $res->execute();
                if ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $locationDetails = $row;
                }
                $res->closeCursor();
                return $locationDetails;
            } catch (PDOException $e) {
                throw new CityIPSpatialDatabaseException($e);
            } catch (Exception $e) {
                throw new CityIPSpatialDatabaseException("Unable to fetch IP Short Name: ", $e);
            }
        } else {
            throw new CityIPSpatialDatabaseException("IP Address missing.", $e);
        }
    }
}
