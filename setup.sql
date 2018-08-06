DROP DATABASE IF EXISTS `bof_test`;
CREATE DATABASE IF NOT EXISTS `bof_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP USER IF EXISTS 'bof-test'@'localhost';
CREATE USER 'bof-test'@'localhost' IDENTIFIED BY 'bof-test';

GRANT USAGE ON * . * TO 'bof-test'@'localhost' IDENTIFIED BY 'bof-test' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON `bof_test` . * TO 'bof-test'@'localhost' WITH GRANT OPTION ;


DROP TABLE IF EXISTS `daily_statistics_views`;
CREATE TABLE `daily_statistics_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile` int(11) unsigned NOT NULL,
  `date` date NOT NULL,
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`,`deleted`,`profile`),
  KEY `profile` (`profile`),
  CONSTRAINT `daily_statistics_views_ibfk_1` FOREIGN KEY (`profile`) REFERENCES `profiles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;

INSERT INTO `profiles` (`id`, `name`, `created`, `updated`, `deleted`)
VALUES
  (1,'Karl Lagerfeld','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (2,'Anna Wintour','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (3,'Tom Ford','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (4,'Pierre Alexis Dumas','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (5,'Sandra Choi','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00');

/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;


DROP TABLE IF EXISTS `views`;
CREATE TABLE `views` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile` int(11) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `user_data` text,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` datetime DEFAULT '9999-12-31 00:00:00',
  PRIMARY KEY (`id`),
  KEY `profile` (`profile`),
  CONSTRAINT `views_ibfk_1` FOREIGN KEY (`profile`) REFERENCES `profiles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;