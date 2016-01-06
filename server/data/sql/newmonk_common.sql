CREATE DATABASE newmonk_common;

CREATE TABLE newmonk_common.`deployment` (
  `deployment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `deployed_at` datetime NOT NULL,
  `tag` varchar(20) DEFAULT NULL,
  `deployed_by` varchar(40) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`deployment_id`),
  UNIQUE KEY `deployment_key1` (`app_id`,`deployed_at`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
