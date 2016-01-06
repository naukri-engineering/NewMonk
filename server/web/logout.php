<?php

require_once __DIR__.'/../config/config.php';

$objAuthenticationManager = new AuthenticationManager(COOKIE_NAME, COOKIE_EXPIRE_TIME);
$objAuthenticationManager->clearAuthentication();
header('Location: '.LOGIN_URL);
