<?php

$username = $argv[1];
$password = $argv[2];

echo hash('sha256', $password.$username)."\n";
