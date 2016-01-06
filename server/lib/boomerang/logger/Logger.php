<?php

namespace NewMonk\lib\boomerang\logger;

class Logger
{
    private $deviceDetector;

    public function __construct($deviceDetector) {
        $this->deviceDetector = $deviceDetector;
    }

    public function logBoomr($boomData, $logTime, $ip, $userAgent) {
        if ($this->loadTimeOutOfBounds($boomData)) {
            return false;
        }

        $boomData = $this->fixMissingData($boomData);

        if (!$logTime) {
            $logTime = date('Y-m-d H:i:s');
        }
        if (!$ip) {
            $ip = ip2long(\CDNHeaders::getInstance()->getRemoteIP());
        }
        if (!$userAgent) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        $deviceDetails = $this->deviceDetector->getDeviceDetails($userAgent);
        $osName =  $deviceDetails['osDetails']["name"]." ".$deviceDetails['osDetails']["version"];
        $browserName =  $deviceDetails['browserDetails']["name"] ? $deviceDetails['browserDetails']["name"] : "Unknown";
        $browserVersion =  $deviceDetails['browserDetails']["version"]? $deviceDetails['browserDetails']["version"] : 0;
        $deviceType =  $deviceDetails['type'];
        try {
            $dbNames = \DbUtil::getDbNames($boomData['appId']);
            $db = \ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
            $sql = 'SELECT os_id FROM '.$dbNames['common'].'.os WHERE name = :name and device_type = :device_type';
            $st = $db->prepare($sql);
            $st->bindValue(':name', $osName, \PDO::PARAM_STR);
            $st->bindValue(':device_type', $deviceType, \PDO::PARAM_STR);
            $st->execute();
            $result = $st->fetch(\PDO::FETCH_ASSOC);
            $st->closeCursor();
            if ($result['os_id']) {
                $osId = $result['os_id'];
            } else {
                $sql = 'INSERT INTO '.$dbNames['common'].'.os (os_id, name,device_type) VALUES(NULL, :name, :device_type)';
                $st = $db->prepare($sql);
                $st->bindValue(':name', $osName, \PDO::PARAM_STR);
                $st->bindValue(':device_type', $deviceType, \PDO::PARAM_STR);
                $st->execute();
                $osId = $db->lastInsertId();
                $st->closeCursor();
            }


            $sql = 'SELECT browser_id FROM '.$dbNames['common'].'.browser WHERE name = :name AND version = :version';
            $st = $db->prepare($sql);
            $st->bindValue(':name', $browserName, \PDO::PARAM_STR);
            $st->bindValue(':version', $browserVersion, \PDO::PARAM_STR);
            $st->execute();
            $result = $st->fetch(\PDO::FETCH_ASSOC);
            $st->closeCursor();
            if ($result['browser_id']) {
                $browserId = $result['browser_id'];
            } else {
                $sql = 'INSERT INTO '.$dbNames['common'].'.browser (browser_id, name, version) VALUES(NULL, :name, :version)';
                $st = $db->prepare($sql);
                $st->bindValue(':name', $browserName, \PDO::PARAM_STR);
                $st->bindValue(':version', $browserVersion, \PDO::PARAM_STR);
                $st->execute();
                $browserId = $db->lastInsertId();
                $st->closeCursor();
            }


            $sql = 'SELECT env_id FROM '.$dbNames['main'].'.env WHERE os_id = :os_id AND browser_id = :browser_id';
            $st = $db->prepare($sql);
            $st->bindValue(':os_id', $osId, \PDO::PARAM_INT);
            $st->bindValue(':browser_id', $browserId, \PDO::PARAM_INT);
            $st->execute();
            $result = $st->fetch(\PDO::FETCH_ASSOC);
            $st->closeCursor();
            if ($result['env_id']) {
                $envId = $result['env_id'];
            } else {
                $sql = 'INSERT INTO '.$dbNames['main'].'.env (env_id, os_id, browser_id) VALUES(NULL, :os_id, :browser_id)';
                $st = $db->prepare($sql);
                $st->bindValue(':os_id', $osId, \PDO::PARAM_INT);
                $st->bindValue(':browser_id', $browserId, \PDO::PARAM_INT);
                $st->execute();
                $envId = $db->lastInsertId();
                $st->closeCursor();
            }


            $isUrlATag = !empty($boomData['tag']);
            if ($isUrlATag) {
                $boomData['u'] = $boomData['tag'];
            }
            $urlIds = $this->insertUrl($dbNames, $db, $boomData['u'], $isUrlATag);
            $urlStaticId = $urlIds['urlStaticId'];
            $urlDynamicId = $urlIds['urlDynamicId'];


            $sql = 'SELECT url_id FROM '.$dbNames['main'].'.url WHERE url_static_id = :url_static_id AND url_dynamic_id = :url_dynamic_id';
            $st = $db->prepare($sql);
            $st->bindValue(':url_static_id', $urlStaticId, \PDO::PARAM_INT);
            $st->bindValue(':url_dynamic_id', $urlDynamicId, \PDO::PARAM_INT);
            // $st->bindValue(':referrer_static_id', $referrerStaticId, \PDO::PARAM_INT);
            // $st->bindValue(':referrer_dynamic_id', $referrerDynamicId, \PDO::PARAM_INT);
            $st->execute();
            $result = $st->fetch(\PDO::FETCH_ASSOC);
            $st->closeCursor();
            if ($result['url_id']) {
                $urlId = $result['url_id'];
            } else {
                $sql = 'INSERT INTO '.$dbNames['main'].'.url (url_id, url_static_id, url_dynamic_id) VALUES(NULL, :url_static_id, :url_dynamic_id)';
                $st = $db->prepare($sql);
                $st->bindValue(':url_static_id', $urlStaticId, \PDO::PARAM_INT);
                $st->bindValue(':url_dynamic_id', $urlDynamicId, \PDO::PARAM_INT);
                $st->execute();
                $urlId = $db->lastInsertId();
                $st->closeCursor();
            }


            $sql = 'INSERT INTO '.$dbNames['main'].'.main (main_id, app_id, log_time, ip_address, env_id, url_id) VALUES(NULL, :app_id, :log_time, :ip_address, :env_id, :url_id)';
            $st = $db->prepare($sql);
            $st->bindValue(':app_id', $boomData['appId'], \PDO::PARAM_INT);
            $st->bindValue(':log_time', $logTime, \PDO::PARAM_STR);
            $st->bindValue(':ip_address', $ip, \PDO::PARAM_INT);
            $st->bindValue(':env_id', $envId, \PDO::PARAM_INT);
            $st->bindValue(':url_id', $urlId, \PDO::PARAM_INT);
            $st->execute();
            $mainId = $db->lastInsertId();
            $st->closeCursor();


            $sql = 'INSERT INTO '.$dbNames['main'].'.load_time (main_id, dns, response, page, done, ready) VALUES(:main_id, :dns, :response, :page, :done, :ready)';
            $st = $db->prepare($sql);
            $st->bindValue(':main_id', $mainId, \PDO::PARAM_INT);
            $st->bindValue(':dns', $boomData['nt_dns'], \PDO::PARAM_STR);
            $st->bindValue(':response', $boomData['t_resp'], \PDO::PARAM_STR);
            $st->bindValue(':page', $boomData['t_page'], \PDO::PARAM_STR);
            $st->bindValue(':done', $boomData['t_done'], \PDO::PARAM_STR);
            $st->bindValue(':ready', $boomData['t_resr']['t_domloaded'] ? $boomData['t_resr']['t_domloaded'] : $boomData['t_done'], \PDO::PARAM_STR);
            $st->execute();
            $st->closeCursor();


            $sql = 'INSERT INTO '.$dbNames['main'].'.bandwidth_latency (main_id, bandwidth, bandwidth_error, latency, latency_error, round_trip_type) VALUES(:main_id, :bandwidth, :bandwidth_error, :latency, :latency_error, :round_trip_type)';
            $st = $db->prepare($sql);
            $st->bindValue(':main_id', $mainId, \PDO::PARAM_INT);
            $st->bindValue(':bandwidth', $boomData['bw'], \PDO::PARAM_STR);
            $st->bindValue(':bandwidth_error', $boomData['bw_err'], \PDO::PARAM_STR);
            $st->bindValue(':latency', $boomData['lat'], \PDO::PARAM_STR);
            $st->bindValue(':latency_error', $boomData['lat_err'], \PDO::PARAM_STR);
            $st->bindValue(':round_trip_type', $boomData['rt.start'], \PDO::PARAM_STR);
            $st->execute();
            $st->closeCursor();


            foreach ($boomData['t_resr'] as $customTimerName => $customTimerTime) {
                if ($customTimerName == 't_domloaded') {
                    continue;
                }

                $sql = 'SELECT custom_timer_id FROM '.$dbNames['common'].'.custom_timer WHERE name = :name';
                $st = $db->prepare($sql);
                $st->bindValue(':name', $customTimerName, \PDO::PARAM_STR);
                $st->execute();
                $result = $st->fetch(\PDO::FETCH_ASSOC);
                $st->closeCursor();
                if ($result['custom_timer_id']) {
                    $customTimerId = $result['custom_timer_id'];
                } else {
                    $sql = 'INSERT INTO '.$dbNames['common'].'.custom_timer (custom_timer_id, name) VALUES(NULL, :name)';
                    $st = $db->prepare($sql);
                    $st->bindValue(':name', $customTimerName, \PDO::PARAM_STR);
                    $st->execute();
                    $customTimerId = $db->lastInsertId();
                    $st->closeCursor();
                }


                $sql = 'INSERT INTO '.$dbNames['main'].'.custom_time (main_id, custom_timer_id, time) VALUE (:main_id, :custom_timer_id, :time)';
                $st = $db->prepare($sql);
                $st->bindValue(':main_id', $mainId, \PDO::PARAM_INT);
                $st->bindValue(':custom_timer_id', $customTimerId, \PDO::PARAM_INT);
                $st->bindValue(':time', $customTimerTime, \PDO::PARAM_STR);
                $st->execute();
                $st->closeCursor();
            }
        } catch (Exception $e) {
            die('Error: '.$e->getMessage()."<br />\n");
        }

        return true;
    }

    private function loadTimeOutOfBounds($boomData) {
        return $boomData['t_done'] > 20000 || $boomData['t_done'] < 0
            || $boomData['t_page'] > 20000 || $boomData['t_page'] < 0
            || $boomData['t_resp'] > 20000 || $boomData['t_resp'] < 0
            || $boomData['nt_dns'] > 20000 || $boomData['nt_dns'] < 0;
    }

    private function fixMissingData($boomData) {
        $fields = array('nt_dns', 't_resp', 't_page', 't_done', 'bw', 'bw_err', 'lat', 'lat_err');
        foreach ($fields as $field) {
            if (is_null($boomData[$field])) {
                $boomData[$field] = 0;
            }
        }
        return $boomData;
    }

    private function insertUrl($dbNames, $db, $url, $isUrlATag = false) {
        $urlDynamicId = $urlStaticId = 0;
        if ($isUrlATag) {
            $urlStatic = $url;
        } else {
            list($urlStatic, $urlDynamic) = \UrlHelper::breakUrl($url);
        }

        if ($urlDynamic) {
            $checksum = md5($urlDynamic);
            $sql = 'SELECT url_id FROM '.$dbNames['main'].'.url_dynamic WHERE checksum = :checksum';
            $st = $db->prepare($sql);
            $st->bindValue(':checksum', $checksum, \PDO::PARAM_STR);
            $st->execute();
            $result = $st->fetch(\PDO::FETCH_ASSOC);
            if ($result['url_id']) {
                $urlDynamicId = $result['url_id'];
            } else {
                $sql = 'INSERT INTO '.$dbNames['main'].'.url_dynamic (url_id, url, checksum) VALUES(NULL, :url, :checksum)';
                $st = $db->prepare($sql);
                $st->bindValue(':url', $urlDynamic, \PDO::PARAM_STR);
                $st->bindValue(':checksum', $checksum, \PDO::PARAM_STR);
                $st->execute();
                $urlDynamicId = $db->lastInsertId();
                $st->closeCursor();
            }
        }

        $checksum = md5($urlStatic);
        $sql = 'SELECT url_id FROM '.$dbNames['main'].'.url_static WHERE checksum = :checksum';
        $st = $db->prepare($sql);
        $st->bindValue(':checksum', $checksum, \PDO::PARAM_STR);
        $st->execute();
        $result = $st->fetch(\PDO::FETCH_ASSOC);
        $st->closeCursor();
        if ($result['url_id']) {
            $urlStaticId = $result['url_id'];
        } else {
            $sql = 'INSERT INTO '.$dbNames['main'].'.url_static (url_id, url, checksum) VALUES(NULL, :url, :checksum)';
            $st = $db->prepare($sql);
            $st->bindValue(':url', $urlStatic, \PDO::PARAM_STR);
            $st->bindValue(':checksum', $checksum, \PDO::PARAM_STR);
            $st->execute();
            $urlStaticId = $db->lastInsertId();
            $st->closeCursor();
        }

        return array('urlStaticId'=>$urlStaticId, 'urlDynamicId'=>$urlDynamicId);
    }
}
