-- ----------------------------------------------------------------
-- Database: nLogger_boomerang_common_<appId>
-- ----------------------------------------------------------------
CREATE DATABASE nLogger_boomerang_common_<appId>;

 CREATE TABLE nLogger_boomerang_common_<appId>.`browser` (
  `browser_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `version` varchar(20) NOT NULL,
  PRIMARY KEY (`browser_id`),
  UNIQUE KEY `browser_name` (`name`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_common_<appId>.`os` (
  `os_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `device_type` enum('desktop','mobile','tablet') NOT NULL DEFAULT 'desktop',
  PRIMARY KEY (`os_id`),
  UNIQUE KEY `os_key1` (`device_type`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_common_<appId>.`custom_timer` (
  `custom_timer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- ----------------------------------------------------------------
-- ----------------------------------------------------------------
-- ----------------------------------------------------------------



-- ----------------------------------------------------------------
-- Database: nLogger_boomerang_<appId>_<dd%32>
-- ----------------------------------------------------------------
CREATE DATABASE nLogger_boomerang_<appId>_<dd%32>;

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`bandwidth_latency` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `bandwidth` int(11) NOT NULL,
  `bandwidth_error` int(11) NOT NULL,
  `latency` int(11) NOT NULL,
  `latency_error` int(11) NOT NULL,
  `round_trip_type` enum('navigation','csi','cookie','raw') NOT NULL DEFAULT 'navigation',
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`custom_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `custom_timer_id` int(10) unsigned NOT NULL,
  `time` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`,`custom_timer_id`),
  KEY `custom_time_ibfk_2` (`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`env` (
  `env_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `os_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  PRIMARY KEY (`env_id`),
  KEY `os_id` (`os_id`),
  KEY `browser_id` (`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`load_time` (
  `main_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dns` float NOT NULL,
  `response` float NOT NULL,
  `page` float NOT NULL,
  `done` float NOT NULL,
  `ready` float NOT NULL,
  UNIQUE KEY `main_id` (`main_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`main` (
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

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`url` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url_static_id` int(11) unsigned NOT NULL,
  `url_dynamic_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `idx_urlsd` (`url_static_id`,`url_dynamic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`url_dynamic` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE nLogger_boomerang_<appId>_<dd%32>.`url_static` (
  `url_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `checksum` (`checksum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- ----------------------------------------------------------------
-- ----------------------------------------------------------------
-- ----------------------------------------------------------------



-- ----------------------------------------------------------------
-- Database: nLogger_boomerang_summary_<appId>
-- ----------------------------------------------------------------
CREATE DATABASE nLogger_boomerang_summary_<appId>;

CREATE TABLE nLogger_boomerang_summary_<appId>.`country_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `country_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_<appId>.`city_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `city_id` int(10) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `city_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_<appId>.`custom_time_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `custom_timer_id` int(10) unsigned NOT NULL,
  `avg_time` varchar(255) NOT NULL,
  UNIQUE KEY `custom_time_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`custom_timer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_<appId>.`env_browser_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `browser_id` int(10) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `env_browser_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_<appId>.`env_os_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `os_id` int(10) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `env_os_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`os_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_<appId>.`load_time_ranges_summary` (
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

CREATE TABLE nLogger_boomerang_summary_<appId>.`load_time_summary` (
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

CREATE TABLE nLogger_boomerang_summary_<appId>.`page` (
  `page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_checksum` (`checksum`,`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;

CREATE TABLE nLogger_boomerang_summary_<appId>.`page_summary` (
  `page_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hours_elapsed_since_last_even_year` smallint(5) unsigned NOT NULL,
  `page_views` mediumint(8) unsigned NOT NULL,
  `avg_load_time` float unsigned NOT NULL,
  UNIQUE KEY `page_summary_key1` (`page_id`,`hours_elapsed_since_last_even_year`,`page_views`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY HASH (`page_id`)
PARTITIONS 100;
