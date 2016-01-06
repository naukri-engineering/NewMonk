CREATE DATABASE nLogger_heatmap;

CREATE TABLE nLogger_heatmap.`browser` (
  `browser_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`browser_id`),
  UNIQUE KEY `bname` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE nLogger_heatmap.`data` (
  `data_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `env_id` int(10) unsigned NOT NULL DEFAULT '0',
  `x` double DEFAULT NULL,
  `y` double DEFAULT NULL,
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  PRIMARY KEY (`data_id`),
  UNIQUE KEY `uniq_index` (`page_id`,`date`,`env_id`,`x`,`y`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE nLogger_heatmap.`env` (
  `env_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `browser_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `resolution_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `os_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`env_id`),
  UNIQUE KEY `bid_2` (`browser_id`,`resolution_id`,`os_id`),
  KEY `bid` (`browser_id`),
  KEY `oid` (`os_id`),
  KEY `rid` (`resolution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE nLogger_heatmap.`os` (
  `os_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`os_id`),
  UNIQUE KEY `osname` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE nLogger_heatmap.`page` (
  `page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_tag` varchar(255) NOT NULL,
  `type` enum('URL','TAG') NOT NULL,
  `app_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `appid` (`app_id`,`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE nLogger_heatmap.`resolution` (
  `resolution_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `resolution` varchar(20) NOT NULL,
  PRIMARY KEY (`resolution_id`),
  UNIQUE KEY `resolution` (`resolution`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE nLogger_heatmap.`urltag` (
  `page_id` bigint(20) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  UNIQUE KEY `url` (`url`,`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
