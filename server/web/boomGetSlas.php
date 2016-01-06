<?php

require_once __DIR__.'/../lib/authentication/Authenticator.php';
require_once __DIR__.'/../config/config.php';

$response = \ncYaml::load($boomSlaConfigFilePath);
header("Content-Type: text/javascript");
echo $_REQUEST['callback'].'('.json_encode($response, true).');';
