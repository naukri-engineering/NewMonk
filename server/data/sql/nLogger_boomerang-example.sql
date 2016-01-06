CREATE DATABASE nLogger_boomerang_common_126;

 CREATE TABLE nLogger_boomerang_common_126.`browser` (
  `browser_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `version` varchar(20) NOT NULL,
  PRIMARY KEY (`browser_id`),
  UNIQUE KEY `browser_name` (`name`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_common_126.`os` (
  `os_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `device_type` enum('desktop','mobile','tablet') NOT NULL DEFAULT 'desktop',
  PRIMARY KEY (`os_id`),
  UNIQUE KEY `os_key1` (`device_type`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_common_126.`custom_timer` (
  `custom_timer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- ----------------------------------------------------------------
-- ----------------------------------------------------------------
-- ----------------------------------------------------------------



CREATE DATABASE nLogger_boomerang_summary_126;

CREATE TABLE nLogger_boomerang_summary_126.`country_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `country_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_126.`city_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `city_id` int(10) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `city_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;


CREATE TABLE nLogger_boomerang_summary_126.`custom_time_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `custom_timer_id` int(10) unsigned NOT NULL,
  `avg_time` varchar(255) NOT NULL,
  UNIQUE KEY `custom_time_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_126.`env_browser_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `browser_id` int(10) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `env_browser_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_126.`env_os_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `os_id` int(10) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `env_os_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`os_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_126.`load_time_ranges_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `page_views_0` mediumint(8) unsigned NOT NULL,
  `page_views_1` mediumint(8) unsigned NOT NULL,
  `page_views_2` mediumint(8) unsigned NOT NULL,
  `page_views_3` mediumint(8) unsigned NOT NULL,
  `page_views_4` mediumint(8) unsigned NOT NULL,
  `page_views_5` mediumint(8) unsigned NOT NULL,
  `page_views_6` mediumint(8) unsigned NOT NULL,
  `page_views_7` mediumint(8) unsigned NOT NULL,
  `page_views_8` mediumint(8) unsigned NOT NULL,
  `page_views_9` mediumint(8) unsigned NOT NULL,
  `page_views_10` mediumint(8) unsigned NOT NULL,
  `page_views_11` mediumint(8) unsigned NOT NULL,
  `page_views_12` mediumint(8) unsigned NOT NULL,
  `page_views_13` mediumint(8) unsigned NOT NULL,
  `page_views_14` mediumint(8) unsigned NOT NULL,
  `page_views_15` mediumint(8) unsigned NOT NULL,
  `page_views_16` mediumint(8) unsigned NOT NULL,
  `page_views_17` mediumint(8) unsigned NOT NULL,
  `page_views_18` mediumint(8) unsigned NOT NULL,
  `page_views_19` mediumint(8) unsigned NOT NULL,
  `page_views_20` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `load_time_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_126.`load_time_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `avg_network_time` float unsigned NOT NULL,
  `avg_backend_time` float unsigned NOT NULL,
  `avg_frontend_time` float unsigned NOT NULL,
  `avg_dom_ready_time` float unsigned NOT NULL,
  `avg_done_time` float unsigned NOT NULL,
  UNIQUE KEY `load_time_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_126.`page` (
  `page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_checksum` (`checksum`,`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_126.`page_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `page_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`page_views`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;



-- ----------------------------------------------------------------
-- ----------------------------------------------------------------
-- ----------------------------------------------------------------



CREATE DATABASE nLogger_boomerang_126_1;

CREATE TABLE nLogger_boomerang_126_1.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_1.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_1.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_1.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_1.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_1.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_1.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_1.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_2;

CREATE TABLE nLogger_boomerang_126_2.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_2.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_2.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_2.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_2.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_2.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_2.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_2.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_3;

CREATE TABLE nLogger_boomerang_126_3.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_3.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_3.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_3.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_3.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_3.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_3.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_3.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_4;

CREATE TABLE nLogger_boomerang_126_4.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_4.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_4.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_4.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_4.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_4.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_4.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_4.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_5;

CREATE TABLE nLogger_boomerang_126_5.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_5.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_5.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_5.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_5.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_5.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_5.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_5.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_6;

CREATE TABLE nLogger_boomerang_126_6.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_6.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_6.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_6.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_6.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_6.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_6.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_6.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_7;

CREATE TABLE nLogger_boomerang_126_7.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_7.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_7.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_7.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_7.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_7.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_7.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_7.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_8;

CREATE TABLE nLogger_boomerang_126_8.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_8.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_8.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_8.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_8.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_8.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_8.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_8.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_9;

CREATE TABLE nLogger_boomerang_126_9.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_9.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_9.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_9.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_9.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_9.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_9.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_9.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_10;

CREATE TABLE nLogger_boomerang_126_10.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_10.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_10.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_10.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_10.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_10.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_10.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_10.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_11;

CREATE TABLE nLogger_boomerang_126_11.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_11.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_11.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_11.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_11.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_11.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_11.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_11.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_12;

CREATE TABLE nLogger_boomerang_126_12.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_12.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_12.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_12.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_12.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_12.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_12.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_12.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_13;

CREATE TABLE nLogger_boomerang_126_13.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_13.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_13.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_13.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_13.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_13.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_13.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_13.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_14;

CREATE TABLE nLogger_boomerang_126_14.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_14.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_14.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_14.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_14.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_14.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_14.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_14.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_15;

CREATE TABLE nLogger_boomerang_126_15.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_15.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_15.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_15.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_15.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_15.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_15.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_15.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_16;

CREATE TABLE nLogger_boomerang_126_16.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_16.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_16.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_16.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_16.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_16.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_16.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_16.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_17;

CREATE TABLE nLogger_boomerang_126_17.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_17.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_17.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_17.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_17.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_17.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_17.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_17.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_18;

CREATE TABLE nLogger_boomerang_126_18.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_18.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_18.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_18.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_18.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_18.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_18.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_18.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_19;

CREATE TABLE nLogger_boomerang_126_19.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_19.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_19.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_19.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_19.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_19.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_19.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_19.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_20;

CREATE TABLE nLogger_boomerang_126_20.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_20.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_20.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_20.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_20.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_20.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_20.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_20.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_21;

CREATE TABLE nLogger_boomerang_126_21.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_21.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_21.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_21.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_21.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_21.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_21.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_21.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_22;

CREATE TABLE nLogger_boomerang_126_22.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_22.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_22.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_22.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_22.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_22.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_22.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_22.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_23;

CREATE TABLE nLogger_boomerang_126_23.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_23.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_23.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_23.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_23.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_23.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_23.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_23.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_24;

CREATE TABLE nLogger_boomerang_126_24.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_24.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_24.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_24.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_24.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_24.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_24.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_24.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_25;

CREATE TABLE nLogger_boomerang_126_25.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_25.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_25.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_25.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_25.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_25.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_25.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_25.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_26;

CREATE TABLE nLogger_boomerang_126_26.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_26.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_26.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_26.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_26.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_26.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_26.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_26.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_27;

CREATE TABLE nLogger_boomerang_126_27.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_27.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_27.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_27.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_27.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_27.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_27.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_27.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_28;

CREATE TABLE nLogger_boomerang_126_28.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_28.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_28.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_28.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_28.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_28.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_28.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_28.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_29;

CREATE TABLE nLogger_boomerang_126_29.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_29.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_29.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_29.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_29.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_29.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_29.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_29.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_30;

CREATE TABLE nLogger_boomerang_126_30.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_30.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_30.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_30.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_30.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_30.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_30.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_30.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------------------------------------------

CREATE DATABASE nLogger_boomerang_126_31;

CREATE TABLE nLogger_boomerang_126_31.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_31.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_31.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_31.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_31.`main` (
  `main_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` smallint(5) unsigned NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` int(10) unsigned NOT NULL,
  `env_id` int(11) unsigned NOT NULL,
  `url_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`main_id`),
  KEY `env_id` (`env_id`,`url_id`),
  KEY `url_id` (`url_id`),
  KEY `time_stamp` (`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_31.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_31.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_126_31.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
