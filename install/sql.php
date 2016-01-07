<?php

$sql = "-- Create syntax for TABLE '".TBL_PREFIX."log'
CREATE TABLE `".TBL_PREFIX."log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT NULL,
  `who` varchar(11) DEFAULT NULL,
  `what` varchar(2) NOT NULL DEFAULT '',
  `data` longtext,
  `from` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE '".TBL_PREFIX."session'
CREATE TABLE `".TBL_PREFIX."session` (
  `session_id` varchar(256) NOT NULL DEFAULT '',
  `session_data` longtext NOT NULL,
  `session_lastaccesstime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE '".TBL_PREFIX."user'
CREATE TABLE `".TBL_PREFIX."user` (
  `uid` varchar(11) NOT NULL DEFAULT '',
  `username` varchar(32) NOT NULL,
  `password` longtext NOT NULL,
  `email` varchar(128) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `rank` enum('A','M','U') NOT NULL DEFAULT 'U',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`,`email`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

?>
