/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `bof_test`;
CREATE DATABASE IF NOT EXISTS `bof_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP USER IF EXISTS 'bof-test'@'localhost';
CREATE USER 'bof-test'@'localhost' IDENTIFIED BY 'bof-test';

GRANT USAGE ON * . * TO 'bof-test'@'localhost' IDENTIFIED BY 'bof-test' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON `bof_test` . * TO 'bof-test'@'localhost' WITH GRANT OPTION ;

DROP TABLE IF EXISTS `bof_test`.`profiles`;
CREATE TABLE `bof_test`.`profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `bof_test`.`profiles` WRITE;
/*!40000 ALTER TABLE `bof_test`.`profiles` DISABLE KEYS */;

INSERT INTO `bof_test`.`profiles` (`id`, `profile_name`, `created`, `updated`, `deleted`)
VALUES
  (1,'Karl Lagerfeld','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (2,'Anna Wintour','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (3,'Tom Ford','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (4,'Pierre Alexis Dumas','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00'),
  (5,'Sandra Choi','2017-01-01 00:00:00','2017-01-01 00:00:00','9999-12-31 00:00:00');

/*!40000 ALTER TABLE `bof_test`.`profiles` ENABLE KEYS */;
UNLOCK TABLES;


DROP TABLE IF EXISTS `bof_test`.`views`;
CREATE TABLE `bof_test`.`views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL,
  `date` date NOT NULL,
  `views` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`,`deleted`,`profile_id`),
  KEY `profile_id` (`profile_id`),
  CONSTRAINT `views_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `bof_test`.`profiles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `bof_test`.`views` WRITE;
/*!40000 ALTER TABLE `bof_test`.`views` DISABLE KEYS */;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;