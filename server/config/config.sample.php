<?php

ini_set("error_reporting", E_ALL & ~E_NOTICE);

require_once '/applications/newmonk/server/vendor/autoload.php';

define('APP_PATH', '/applications/newmonk/server/');
define('NC_DB_CONFIG', '/applications/newmonk/server/config/'); // path of nc_databases.yml
define('LOGIN_URL', $dashboardBaseUrl.'/login.php');
define('COOKIE_NAME', '_nlog');
define('COOKIE_EXPIRE_TIME', 86400);
define('_Key_New', 'CHANGE-THIS-KEY1'); // DEFINE YOUR OWN ENCRYPTION KEY HERE TO A 16 BYTE STRING
define('NAUKRI_CACHE_CONFIG', APP_PATH.'/config/'); // path of nc_caches.yml
define('LOG_DIR', __DIR__.'/../log');

$errorConfigFilePath = APP_PATH.'config/error.yml';
$elasticSearchConfigFilePath = APP_PATH.'config/elasticsearch.yml';
$boomSlaConfigFilePath = '/applications/newmonk/dashboard/config/sla.yml';
$deviceDetetorServerPath = '/applications/newmonk/server/lib/devicedetectorserver';
$baseUrl = "http://localhost/newmonk/server";
$dashboardBaseUrl = "http://localhost/newmonk/dashboard";

NewMonk\lib\common\error\HandlerFactory::getInstance()->getErrorHandler(LOG_DIR)->register();
