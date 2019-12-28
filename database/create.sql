-- Adminer 4.7.4 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `consumption`;
CREATE TABLE `consumption` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `meter_state_id` int(10) unsigned NOT NULL,
  `month` int(2) unsigned NOT NULL,
  `year` int(4) unsigned NOT NULL,
  `average_day_consumption` float unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `meter_state_id` (`meter_state_id`),
  CONSTRAINT `consumption_ibfk_1` FOREIGN KEY (`meter_state_id`) REFERENCES `meter_state` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `meter_state`;
CREATE TABLE `meter_state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commodity` enum('gas','electric') COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `state` float NOT NULL,
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2019-11-22 17:29:07
