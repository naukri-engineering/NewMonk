<?php

namespace NewMonk\web;

require_once __DIR__.'/../config/config.php';

header('HTTP/1.0 204 No Content');

$db = \ncDatabaseManager::getInstance()->getDatabase('nLogger')->getConnection();
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

$sql = 'INSERT IGNORE INTO newmonk_common.deployment(deployment_id, app_id, deployed_at, tag, deployed_by, comment) VALUES(null, :app_id, :deployed_at, :tag, :deployed_by, :comment)';
$st = $db->prepare($sql);
$st->bindValue(':app_id', $_REQUEST['appId'], \PDO::PARAM_INT);
$st->bindValue(':deployed_at', $_REQUEST['deployedAt'], \PDO::PARAM_STR);
$st->bindValue(':tag', $_REQUEST['tag'], \PDO::PARAM_STR);
$st->bindValue(':deployed_by', $_REQUEST['deployedBy'], \PDO::PARAM_STR);
$st->bindValue(':comment', $_REQUEST['comment'], \PDO::PARAM_STR);
$st->execute();
$st->closeCursor();

$sql = 'DELETE FROM newmonk_common.deployment WHERE deployed_at < DATE_SUB(CURDATE(), INTERVAL 2 YEAR)';
$st = $db->prepare($sql);
$st->execute();
$st->closeCursor();
