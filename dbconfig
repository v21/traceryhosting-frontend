CREATE DATABASE `traceryhosting` DEFAULT CHARSET=utf8mb4 ;
USE traceryhosting;

CREATE USER `tracery_node`@`localhost` IDENTIFIED BY 'tracery_test';
GRANT SELECT, UPDATE (last_error_code, last_reply) ON `traceryhosting`.`traceries` TO `tracery_node`@`localhost`;

CREATE USER `tracery_php`@`localhost` IDENTIFIED BY 'tracery_test';
GRANT INSERT, SELECT, UPDATE ON `traceryhosting`.`traceries` TO `tracery_php`@`localhost`;

CREATE USER `tracery_spam`@`localhost` IDENTIFIED BY 'tracery_test';
GRANT SELECT, UPDATE (blocked_status) ON `traceryhosting`.`traceries` TO `tracery_spam`@`localhost`;


CREATE TABLE `traceries` (
  `token` varchar(64) NOT NULL,
  `token_secret` varchar(64) DEFAULT NULL,
  `screen_name` varchar(15) DEFAULT NULL,
  `frequency` int DEFAULT NULL,
  `tracery` mediumtext,
  `user_id` varchar(64) NOT NULL DEFAULT '',
  `public_source` tinyint(1) DEFAULT NULL,
  `blocked_status` smallint DEFAULT NULL,
  `does_replies` tinyint(1) DEFAULT '0',
  `reply_rules` mediumtext,
  `last_reply` varchar(25) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_error_code` smallint DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `token` (`token`),
  KEY `screen_name` (`screen_name`),
  KEY `created_on` (`created_on`),
  KEY `last_updated` (`last_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;