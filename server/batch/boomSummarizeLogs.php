<?php

// prepare sab jagah loop mein baar baar ho raha hai - use bacahao
// remove "detail" word in var names

echo 'Cron started @ '.date('Y-m-d H:i:s')."\n";
ini_set('memory_limit', '1024M');
try {
    require_once __DIR__.'/../config/config.php';

    $lastHourTimestamp = time()-3600;
    $cronStartTimestamp = strtotime(date('Y-m-d H', $lastHourTimestamp).':00:00');
    $cronEndTimestamp = strtotime(date('Y-m-d H', $lastHourTimestamp).':59:59');

    $db = ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

    $loggingConfig = ncYaml::load(__DIR__.'/../config/logging.yml');
    $boomWhitelistedAppIds = $loggingConfig['boomerang'];

    foreach ($boomWhitelistedAppIds['appIds'] as $appId) {
        for ($time = $cronStartTimestamp; $time<$cronEndTimestamp; $time+=3600) {
            $date = date('Y-m-d', $time);
            $hour = date('H', $time);
            $startTime = $date.' '.$hour.':00:00';
            $endTime = $date.' '.$hour.':59:59';
            $dbNames = DbUtil::getDbNames($appId, $date);
            echo 'Processing '.implode(', ', $dbNames).' from '.$startTime.' to '.$endTime."\n";
            summarizeDb($db, $dbNames, $startTime, $endTime);
            echo "\n----------------\n";
        }
    }
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."<br />\n";
    echo 'Trace: ';print_r($e->getTrace());echo "<br />\n";
    exit($e->getCode());
}
echo 'Cron ended @ '.date('Y-m-d H:i:s')."\n";



function summarizeDb($db, $dbNames, $startTime, $endTime) {
    $lastEvenYear = (date('Y') % 2 === 0) ? date('Y') : date('Y') - 1;
    $lastEvenYearTimestamp = mktime(0, 0, 0, 1, 1, $lastEvenYear);
    $hoursElapsedSinceLastEvenYear = floor((strtotime($startTime)-$lastEvenYearTimestamp) / 3600);

    echo date('Y-m-d H:i:s').": calling getUrlWisePageViewsAndLoaddate()...\n";
    $urlWisePageViewsAndLoadTimes = getUrlWisePageViewsAndLoadTime($db, $dbNames, $startTime, $endTime);
    echo date('Y-m-d H:i:s').": calling getPageIds()...\n";
    $urlIdPageIdMap = getPageIds($db, $dbNames, $urlWisePageViewsAndLoadTimes);
    echo date('Y-m-d H:i:s').": calling savePageSummary()...\n";
    savePageSummary($db, $dbNames, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap, $urlWisePageViewsAndLoadTimes);
    echo date('Y-m-d H:i:s').": calling processLoadTimeSummary()...\n";
    processLoadTimeSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap);
    echo date('Y-m-d H:i:s').": calling processLoadTimeRangesSummary()...\n";
    processLoadTimeRangesSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap);
    echo date('Y-m-d H:i:s').": calling processEnvOSSummary()...\n";
    processEnvOSSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap);
    echo date('Y-m-d H:i:s').": calling processEnvBrowserSummary()...\n";
    processEnvBrowserSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap);
    echo date('Y-m-d H:i:s').": calling processCustomTimeSummary()...\n";
    processCustomTimeSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap);
    // echo date('Y-m-d H:i:s').": calling processCountryCitySummary()...\n";
    // processCountryCitySummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap);
}

function getUrlWisePageViewsAndLoadTime($db, $dbNames, $startTime, $endTime) {
    $sql = 'SELECT url_static.url_id AS urlId, url_static.url, url_static.checksum AS urlChecksum, COUNT(1) AS pageViews, ROUND(AVG(load_time.done), 2) AS loadTime
        FROM '.$dbNames['main'].'.main, '.$dbNames['main'].'.url, '.$dbNames['main'].'.url_static, '.$dbNames['main'].'.load_time
        WHERE main.url_id = url.url_id
        AND url.url_static_id = url_static.url_id
        AND main.main_id = load_time.main_id
        AND main.log_time >= :start_time AND main.log_time <= :end_time
        GROUP BY url_static.url_id';
    $st = $db->prepare($sql);
    $st->bindValue(':start_time', $startTime, PDO::PARAM_STR);
    $st->bindValue(':end_time', $endTime, PDO::PARAM_STR);
    $st->execute();
    $urlWisePageViewsAndLoadTimes = array();
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $urlWisePageViewsAndLoadTimes[$row['urlId']] = array(
            'url' => $row['url'],
            'urlChecksum' => $row['urlChecksum'],
            'pageViews' => $row['pageViews'],
            'loadTime' => $row['loadTime']
        );
    }
    return $urlWisePageViewsAndLoadTimes;
}

function getPageIds($db, $dbNames, $urlDetails) {
    $urlIdPageIdMap = array();

    $urlDetailsToInsert = array();
    foreach ($urlDetails as $urlId=>$urlDetail) {
        $sql = 'SELECT page_id
            FROM '.$dbNames['summary'].'.page
            WHERE checksum = :checksum';
        $st = $db->prepare($sql);
        $st->bindValue(':checksum', $urlDetail['urlChecksum'], PDO::PARAM_STR);
        $st->execute();
        $pageDetails = $st->fetch(PDO::FETCH_ASSOC);
        if ($pageDetails['page_id']) {
            $urlIdPageIdMap[$urlId] = $pageDetails['page_id'];
        } else {
            $urlDetailsToInsert[$urlId] = $urlDetail;
        }
        $st->closeCursor();
    }

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.page(page_id, name, checksum) VALUES';
    foreach ($urlDetailsToInsert as $urlDetailToInsert) {
        if ($numValuesToInsert >= 5000) {
            DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 3);
            $numValuesToInsert = 0;
            $valuesToInsert = array();
        }
        ++$numValuesToInsert;
        $valuesToInsert[] = '';
        $valuesToInsert[] = $urlDetailToInsert['url'];
        $valuesToInsert[] = $urlDetailToInsert['urlChecksum'];
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 3);

    foreach ($urlDetailsToInsert as $urlId=>$urlDetailToInsert) {
        $urlIdPageIdMap[$urlId] = getPageId($db, $dbNames, $urlDetailToInsert['url'], $urlDetailToInsert['urlChecksum']);
    }
    return $urlIdPageIdMap;
}

function getPageId($db, $dbNames, $url, $urlChecksum) {
    $sql = 'SELECT page_id
        FROM '.$dbNames['summary'].'.page
        WHERE checksum = :checksum';
    $st = $db->prepare($sql);
    $st->bindValue(':checksum', $urlChecksum, PDO::PARAM_STR);
    $st->execute();
    $pageDetails = $st->fetch(PDO::FETCH_ASSOC);
    if ($pageDetails['page_id']) {
        return $pageDetails['page_id'];
    }
    $st->closeCursor();

    $sql = 'INSERT INTO '.$dbNames['summary'].'.page(page_id, name, checksum) VALUES(NULL, :name, :checksum)';
    $st = $db->prepare($sql);
    $st->bindValue(':name', $url, PDO::PARAM_STR);
    $st->bindValue(':checksum', $urlChecksum, PDO::PARAM_STR);
    $st->execute();
    $pageId = $db->lastInsertId();
    $st->closeCursor();
    return $pageId;
}

function savePageSummary($db, $dbNames, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap, $urlWisePageViewsAndLoadTimes) {
    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.page_summary(page_id, hours_elapsed_since_last_even_year, page_views, avg_load_time) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        if ($numValuesToInsert >= 5000) {
            DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 4);
            $numValuesToInsert = 0;
            $valuesToInsert = array();
        }
        ++$numValuesToInsert;
        $valuesToInsert[] = $pageId;
        $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
        $valuesToInsert[] = $urlWisePageViewsAndLoadTimes[$urlId]['pageViews'];
        $valuesToInsert[] = $urlWisePageViewsAndLoadTimes[$urlId]['loadTime'];
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 4);
}

function processLoadTimeSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap) {
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        $sql = 'SELECT ROUND(AVG(load_time.dns), 2) AS networkTime, ROUND(AVG(load_time.response), 2) AS backendTime, ROUND(AVG(load_time.page), 2) AS frontendTime, ROUND(AVG(load_time.ready), 2) AS domReadyTime, ROUND(AVG(load_time.done), 2) doneTime
            FROM '.$dbNames['main'].'.main, '.$dbNames['main'].'.url, '.$dbNames['main'].'.url_static, '.$dbNames['main'].'.load_time
            WHERE main.url_id = url.url_id
            AND url.url_static_id = url_static.url_id
            AND main.main_id = load_time.main_id
            AND main.log_time >= :start_time AND main.log_time <= :end_time
            AND url_static.url_id = :url_id';
        $st = $db->prepare($sql);
        $st->bindValue(':start_time', $startTime, PDO::PARAM_STR);
        $st->bindValue(':end_time', $endTime, PDO::PARAM_STR);
        $st->bindValue(':url_id', $urlId, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetchAll(PDO::FETCH_ASSOC);
        $loadTimeSummary[$urlId] = $row[0];
        $st->closeCursor();
    }

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.load_time_summary(page_id, hours_elapsed_since_last_even_year, avg_network_time, avg_backend_time, avg_frontend_time, avg_dom_ready_time, avg_done_time) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        if ($numValuesToInsert >= 5000) {
            DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 7);
            $numValuesToInsert = 0;
            $valuesToInsert = array();
        }
        ++$numValuesToInsert;
        $valuesToInsert[] = $pageId;
        $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
        $valuesToInsert[] = $loadTimeSummary[$urlId]['networkTime'];
        $valuesToInsert[] = $loadTimeSummary[$urlId]['backendTime'];
        $valuesToInsert[] = $loadTimeSummary[$urlId]['frontendTime'];
        $valuesToInsert[] = $loadTimeSummary[$urlId]['domReadyTime'];
        $valuesToInsert[] = $loadTimeSummary[$urlId]['doneTime'];
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 7);
}

function processLoadTimeRangesSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap) {
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        $sql = 'SELECT COUNT(1) AS pageViews, FLOOR(load_time.done/1000) AS loadTime
            FROM '.$dbNames['main'].'.main, '.$dbNames['main'].'.url, '.$dbNames['main'].'.url_static, '.$dbNames['main'].'.load_time
            WHERE main.url_id = url.url_id
            AND url.url_static_id = url_static.url_id
            AND main.main_id = load_time.main_id
            AND main.log_time >= :start_time AND main.log_time <= :end_time
            AND url_static.url_id = :url_id
            GROUP BY FLOOR(load_time.done/1000)';
        $st = $db->prepare($sql);
        $st->bindValue(':start_time', $startTime, PDO::PARAM_STR);
        $st->bindValue(':end_time', $endTime, PDO::PARAM_STR);
        $st->bindValue(':url_id', $urlId, PDO::PARAM_INT);
        $st->execute();
        $loadTimeRangesSummary[$urlId] = array_fill(0, 21, 0);
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $loadTimeRangesSummary[$urlId][$row['loadTime']] = $row['pageViews'];
        }
        $st->closeCursor();
    }

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.load_time_ranges_summary(page_id, hours_elapsed_since_last_even_year, page_views_0, page_views_1, page_views_2, page_views_3, page_views_4, page_views_5, page_views_6, page_views_7, page_views_8, page_views_9, page_views_10, page_views_11, page_views_12, page_views_13, page_views_14, page_views_15, page_views_16, page_views_17, page_views_18, page_views_19, page_views_20) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        if ($numValuesToInsert >= 5000) {
            DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 23);
            $numValuesToInsert = 0;
            $valuesToInsert = array();
        }
        ++$numValuesToInsert;
        $valuesToInsert[] = $pageId;
        $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
        for ($i=0; $i<21; ++$i) {
            $valuesToInsert[] = $loadTimeRangesSummary[$urlId][$i];
        }
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 23);
}

function processEnvOSSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap) {
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        $sql = 'SELECT env.os_id AS osId, COUNT(1) AS pageViews, ROUND(AVG(load_time.done), 2) AS loadTime
            FROM '.$dbNames['main'].'.main, '.$dbNames['main'].'.url, '.$dbNames['main'].'.url_static, '.$dbNames['main'].'.load_time, '.$dbNames['main'].'.env
            WHERE main.url_id = url.url_id
            AND url.url_static_id = url_static.url_id
            AND main.main_id = load_time.main_id
            AND main.env_id = env.env_id
            AND main.log_time >= :start_time AND main.log_time <= :end_time
            AND url_static.url_id = :url_id
            GROUP BY env.os_id';
        $st = $db->prepare($sql);
        $st->bindValue(':start_time', $startTime, PDO::PARAM_STR);
        $st->bindValue(':end_time', $endTime, PDO::PARAM_STR);
        $st->bindValue(':url_id', $urlId, PDO::PARAM_INT);
        $st->execute();
        $osWisePageViewsAndLoadTimes[$urlId] = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
    }

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.env_os_summary(page_id, hours_elapsed_since_last_even_year, os_id, page_views, avg_load_time) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        foreach ($osWisePageViewsAndLoadTimes[$urlId] as $osWisePageViewsAndLoadTime) {
            if ($numValuesToInsert >= 5000) {
                DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);
                $numValuesToInsert = 0;
                $valuesToInsert = array();
            }
            ++$numValuesToInsert;
            $valuesToInsert[] = $pageId;
            $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
            $valuesToInsert[] = $osWisePageViewsAndLoadTime['osId'];
            $valuesToInsert[] = $osWisePageViewsAndLoadTime['pageViews'];
            $valuesToInsert[] = $osWisePageViewsAndLoadTime['loadTime'];
        }
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);
}

function processEnvBrowserSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap) {
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        $sql = 'SELECT env.browser_id AS browserId, COUNT(1) AS pageViews, ROUND(AVG(load_time.done), 2) AS loadTime
            FROM '.$dbNames['main'].'.main, '.$dbNames['main'].'.url, '.$dbNames['main'].'.url_static, '.$dbNames['main'].'.load_time, '.$dbNames['main'].'.env
            WHERE main.url_id = url.url_id
            AND url.url_static_id = url_static.url_id
            AND main.main_id = load_time.main_id
            AND main.env_id = env.env_id
            AND main.log_time >= :start_time AND main.log_time <= :end_time
            AND url_static.url_id = :url_id
            GROUP BY env.browser_id';
        $st = $db->prepare($sql);
        $st->bindValue(':start_time', $startTime, PDO::PARAM_STR);
        $st->bindValue(':end_time', $endTime, PDO::PARAM_STR);
        $st->bindValue(':url_id', $urlId, PDO::PARAM_INT);
        $st->execute();
        $browserWisePageViewsAndLoadTimes[$urlId] = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
    }

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.env_browser_summary(page_id, hours_elapsed_since_last_even_year, browser_id, page_views, avg_load_time) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        foreach ($browserWisePageViewsAndLoadTimes[$urlId] as $browserWisePageViewsAndLoadTime) {
            if ($numValuesToInsert >= 5000) {
                DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);
                $numValuesToInsert = 0;
                $valuesToInsert = array();
            }
            ++$numValuesToInsert;
            $valuesToInsert[] = $pageId;
            $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
            $valuesToInsert[] = $browserWisePageViewsAndLoadTime['browserId'];
            $valuesToInsert[] = $browserWisePageViewsAndLoadTime['pageViews'];
            $valuesToInsert[] = $browserWisePageViewsAndLoadTime['loadTime'];
        }
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);
}

function processCustomTimeSummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap) {
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        $sql = 'SELECT custom_time.custom_timer_id AS customTimerId, ROUND(AVG(custom_time.time), 2) AS loadTime
            FROM '.$dbNames['main'].'.main, '.$dbNames['main'].'.url, '.$dbNames['main'].'.url_static, '.$dbNames['main'].'.custom_time
            WHERE main.url_id = url.url_id
            AND url.url_static_id = url_static.url_id
            AND main.main_id = custom_time.main_id
            AND main.log_time >= :start_time AND main.log_time <= :end_time
            AND url_static.url_id = :url_id
            GROUP BY custom_time.custom_timer_id';
        $st = $db->prepare($sql);
        $st->bindValue(':start_time', $startTime, PDO::PARAM_STR);
        $st->bindValue(':end_time', $endTime, PDO::PARAM_STR);
        $st->bindValue(':url_id', $urlId, PDO::PARAM_INT);
        $st->execute();
        $customTimerWiseLoadTimes[$urlId] = $st->fetchAll(PDO::FETCH_ASSOC);
        $st->closeCursor();
    }

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.custom_time_summary(page_id, hours_elapsed_since_last_even_year, custom_timer_id, avg_time) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        foreach ($customTimerWiseLoadTimes[$urlId] as $customTimerWiseLoadTime) {
            if ($numValuesToInsert >= 5000) {
                DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 4);
                $numValuesToInsert = 0;
                $valuesToInsert = array();
            }
            ++$numValuesToInsert;
            $valuesToInsert[] = $pageId;
            $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
            $valuesToInsert[] = $customTimerWiseLoadTime['customTimerId'];
            $valuesToInsert[] = $customTimerWiseLoadTime['loadTime'];
        }
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 4);
}

function processCountryCitySummary($db, $dbNames, $startTime, $endTime, $hoursElapsedSinceLastEvenYear, $urlIdPageIdMap) {
    $geoipProvider = CityIPSpatialDatabase::getInstance('geoip');
    $countryWiseLoadTimes = $cityWiseLoadTimes = $ipCountryCityMap = $countryCodeCountryIdMap = $cityNameCityIdMap = array();
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        $sql = 'SELECT ip_address, load_time.done AS loadTime
            FROM '.$dbNames['main'].'.main, '.$dbNames['main'].'.url, '.$dbNames['main'].'.url_static, '.$dbNames['main'].'.load_time
            WHERE main.url_id = url.url_id
            AND url.url_static_id = url_static.url_id
            AND main.main_id = load_time.main_id
            AND main.log_time >= :start_time AND main.log_time <= :end_time
            AND url_static.url_id = :url_id';
        $st = $db->prepare($sql);
        $st->bindValue(':start_time', $startTime, PDO::PARAM_STR);
        $st->bindValue(':end_time', $endTime, PDO::PARAM_STR);
        $st->bindValue(':url_id', $urlId, PDO::PARAM_INT);
        $st->execute();

        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $ipAddress = long2ip($row['ip_address']);
            if (!isset($ipCountryCityMap[$ipAddress])) {
                $geoipDetails = $geoipProvider->getLocationDetailsByIP($ipAddress);
                $ipCountryCityMap[$ipAddress] = array(
                    'country_code' => $geoipDetails['country_code'],
                    'city_name' => $geoipDetails['city_name'],
                );
            }
            $countryCode = $ipCountryCityMap[$ipAddress]['country_code'];
            $cityName = $ipCountryCityMap[$ipAddress]['city_name'];

            if (!isset($countryCodeCountryIdMap[$countryCode])) {
                $sqlCountry = 'SELECT country_id FROM newmonk_common.country WHERE code = :code';
                $stCountry = $db->prepare($sqlCountry);
                $stCountry->bindValue(':code', $countryCode ? $countryCode : '', PDO::PARAM_STR);
                $stCountry->execute();
                $rowCountry = $stCountry->fetch(PDO::FETCH_ASSOC);
                $countryCodeCountryIdMap[$countryCode] = $rowCountry['country_id'];
                $stCountry->closeCursor();
            }
            $countryId = $countryCodeCountryIdMap[$countryCode];

            if (!isset($cityNameCityIdMap[$countryId][$cityName])) {
                $sqlCity = 'SELECT city_id
                    FROM newmonk_common.city
                    WHERE country_id = :country_id
                    AND name = :name';
                $stCity = $db->prepare($sqlCity);
                $stCity->bindValue(':country_id', $countryId, PDO::PARAM_INT);
                $stCity->bindValue(':name', $cityName ? $cityName : '', PDO::PARAM_STR);
                $stCity->execute();
                $rowCity = $stCity->fetch(PDO::FETCH_ASSOC);
                $cityNameCityIdMap[$countryId][$cityName] = $rowCity['city_id'];
                $stCity->closeCursor();
            }
            $cityId = $cityNameCityIdMap[$countryId][$cityName];

            $countryWiseLoadTimes[$urlId][$countryId][] = $row['loadTime'];
            $cityWiseLoadTimes[$urlId][$cityId][] = $row['loadTime'];
        }
        $st->closeCursor();
    }

    $countryWisePageViewsAndLoadTimes = $cityWisePageViewsAndLoadTimes = array();
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        foreach ($countryWiseLoadTimes[$urlId] as $countryId=>$countryWiseLoadTime) {
            $countryWisePageViewsAndLoadTimes[$urlId][] = array(
                'countryId' => $countryId,
                'pageViews' => count($countryWiseLoadTime),
                'loadTime' => round(array_sum($countryWiseLoadTime)/count($countryWiseLoadTime), 2)
            );
        }

        foreach ($cityWiseLoadTimes[$urlId] as $cityId=>$cityWiseLoadTime) {
            $cityWisePageViewsAndLoadTimes[$urlId][] = array(
                'cityId' => $cityId,
                'pageViews' => count($cityWiseLoadTime),
                'loadTime' => round(array_sum($cityWiseLoadTime)/count($cityWiseLoadTime), 2)
            );
        }
    }

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.country_summary(page_id, hours_elapsed_since_last_even_year, country_id, page_views, avg_load_time) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        foreach ($countryWisePageViewsAndLoadTimes[$urlId] as $countryWisePageViewsAndLoadTime) {
            if ($numValuesToInsert >= 5000) {
                DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);
                $numValuesToInsert = 0;
                $valuesToInsert = array();
            }
            ++$numValuesToInsert;
            $valuesToInsert[] = $pageId;
            $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
            $valuesToInsert[] = $countryWisePageViewsAndLoadTime['countryId'];
            $valuesToInsert[] = $countryWisePageViewsAndLoadTime['pageViews'];
            $valuesToInsert[] = $countryWisePageViewsAndLoadTime['loadTime'];
        }
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);

    $numValuesToInsert = 0;
    $valuesToInsert = array();
    $queryPrefix = 'INSERT INTO '.$dbNames['summary'].'.city_summary(page_id, hours_elapsed_since_last_even_year, city_id, page_views, avg_load_time) VALUES';
    foreach ($urlIdPageIdMap as $urlId=>$pageId) {
        foreach ($cityWisePageViewsAndLoadTimes[$urlId] as $cityWisePageViewsAndLoadTime) {
            if ($numValuesToInsert >= 5000) {
                DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);
                $numValuesToInsert = 0;
                $valuesToInsert = array();
            }
            ++$numValuesToInsert;
            $valuesToInsert[] = $pageId;
            $valuesToInsert[] = $hoursElapsedSinceLastEvenYear;
            $valuesToInsert[] = $cityWisePageViewsAndLoadTime['cityId'];
            $valuesToInsert[] = $cityWisePageViewsAndLoadTime['pageViews'];
            $valuesToInsert[] = $cityWisePageViewsAndLoadTime['loadTime'];
        }
    }
    DbUtil::multipleInsert($db, $queryPrefix, $valuesToInsert, 5);
}
