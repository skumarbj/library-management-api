/* 
  CREATE SCHEMA `library` DEFAULT CHARACTER SET utf8mb4 ; 
*/

# ************************************************************
# Sequel Ace SQL dump
# Version 20067
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 8.2.0)
# Database: library
# Generation Time: 2024-06-24 04:24:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table book
# ------------------------------------------------------------

DROP TABLE IF EXISTS `book`;

CREATE TABLE `book` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `author` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `genre` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `published_date` date NOT NULL,
  `status` enum('AVAILABLE','BORROWED') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'AVAILABLE',
  `deleted_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_IK_title` (`title`),
  KEY `book_IK_author` (`author`),
  KEY `book_IK_genre` (`genre`),
  KEY `book_IK_isbn` (`isbn`),
  KEY `book_IK_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;

INSERT INTO `book` (`id`, `title`, `author`, `genre`, `isbn`, `published_date`, `status`, `deleted_date`)
VALUES
	(1,'ThiruKural','ThiruValluvar','Treatise','8796498734545','1983-03-18','AVAILABLE',NULL),
	(2,'Symfony 5: The Fast Track','Fabien Potencier','Programming Language','978-2918390374','2019-11-01','AVAILABLE',NULL),
	(3,'Python Crash Course, 3rd Edition: A Hands-On, Project-Based Introduction to Programming 3rd Edition','Eric Matthes','Programming Language','978-1718502703','2023-01-10','AVAILABLE',NULL);

/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table borrow
# ------------------------------------------------------------

DROP TABLE IF EXISTS `borrow`;

CREATE TABLE `borrow` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `checkout_date` date NOT NULL DEFAULT (curdate()),
  `checkin_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrow_IK_user` (`user_id`),
  KEY `borrow_IK_book` (`book_id`),
  KEY `borrow_IK_checkout` (`checkout_date`),
  KEY `borrow_IK_checkin` (`checkin_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `borrow` WRITE;
/*!40000 ALTER TABLE `borrow` DISABLE KEYS */;

INSERT INTO `borrow` (`id`, `user_id`, `book_id`, `checkout_date`, `checkin_date`)
VALUES
	(1,2,1,'2024-06-20','2024-06-21');

/*!40000 ALTER TABLE `borrow` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('ADMIN','MEMBER') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'MEMBER',
  `deleted_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_UK_email` (`email`),
  KEY `user_IK_role` (`role`),
  KEY `user_IK_deleted` (`deleted_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `deleted_date`)
VALUES
	(1,'Admin','Admin@example.com','$2y$10$1MUQNPbgWMThQyYjkyQOEu1egEXrXzUkwHevuX9TKbK1OqQ4fq6Bq','ADMIN',NULL),
	(2,'User','User@example.com','$2y$10$c.K05/gzyPt5IMsfDVDO3.rYuQRtXCCpwBigAnm9Fkp.ecpqzWhEC','MEMBER',NULL),
	(3,'Reader','Reader@example.com','$2y$10$LyhjkaQgcTopQf0Zs7oQBuQA2x.v/zNwEjSIGREmZwqvl3y8AaMKq','MEMBER',NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/*
DROP TABLE IF EXISTS `library`.`book`;
CREATE TABLE IF NOT EXISTS `library`.`book` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(256) NOT NULL,
  `author` VARCHAR(256) NOT NULL,
  `genre` VARCHAR(256) NOT NULL,
  `isbn` VARCHAR(20) NOT NULL,
  `published_date` DATE NOT NULL,
  `status` ENUM('AVAILABLE','BORROWED') NOT NULL DEFAULT 'AVAILABLE',
  `deleted_date` DATE NULL,
  PRIMARY KEY (`id`),
  INDEX `book_IK_title` (`title`),
  INDEX `book_IK_author` (`author`),
  INDEX `book_IK_genre` (`genre`),
  INDEX `book_IK_isbn` (`isbn`),
  INDEX `book_IK_status` (`status`)
);

DROP TABLE IF EXISTS `library`.`user`;
CREATE TABLE IF NOT EXISTS `library`.`user` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `role` ENUM('ADMIN','MEMBER') NOT NULL DEFAULT 'MEMBER',
  `deleted_date` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_UK_email` (`email`),
  INDEX `user_IK_role` (`role`),
  INDEX `user_IK_deleted` (`deleted_date`)
);

DROP TABLE IF EXISTS `library`.`borrow`;
CREATE TABLE IF NOT EXISTS `library`.`borrow` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `book_id` BIGINT UNSIGNED NOT NULL,
  `checkout_date` DATE NOT NULL DEFAULT (CURRENT_DATE),
  `checkin_date` DATE NULL,
  PRIMARY KEY (`id`),
  INDEX `borrow_IK_user` (`user_id`),
  INDEX `borrow_IK_book` (`book_id`),
  INDEX `borrow_IK_checkout` (`checkout_date`),
  INDEX `borrow_IK_checkin` (`checkin_date`)
);
*/

/*
DROP TABLE IF EXISTS `library`.`book`;
CREATE TABLE IF NOT EXISTS `library`.`book` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(256) NOT NULL,
  `author` VARCHAR(256) NOT NULL,
  `genre` VARCHAR(256) NOT NULL,
  `isbn` VARCHAR(20) NOT NULL,
  `published_date` DATE NOT NULL,
  `status` ENUM('AVAILABLE','BORROWED') NOT NULL DEFAULT 'AVAILABLE',
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `book_IK_title` (`title`),
  INDEX `book_IK_author` (`author`),
  INDEX `book_IK_genre` (`genre`),
  INDEX `book_IK_isbn` (`isbn`),
  INDEX `book_IK_status` (`status`)
);

DROP TABLE IF EXISTS `library`.`user`;
CREATE TABLE IF NOT EXISTS `library`.`user` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `usr_pwd` VARCHAR(256) NOT NULL,
  `role` ENUM('ADMIN','MEMBER') NOT NULL DEFAULT 'MEMBER',
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `user_IK_email` (`email`),
  INDEX `user_IK_role` (`role`)
);

DROP TABLE IF EXISTS `library`.`borrow`;
CREATE TABLE IF NOT EXISTS `library`.`borrow` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `book_id` BIGINT UNSIGNED NOT NULL,
  `checkout_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `checkin_date` DATETIME NULL,
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `borrow_IK_user` (`user_id`),
  INDEX `borrow_IK_book` (`book_id`),
  INDEX `borrow_IK_checkout` (`checkout_date`),
  INDEX `borrow_IK_checkin` (`checkin_date`)
);
/*

/*



DROP TABLE IF EXISTS `library`.`book_status`;
CREATE TABLE IF NOT EXISTS `library`.`book_status` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(25) NOT NULL,
  `status` VARCHAR(25) NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `book_status_IK_status` (`status`)
);

DROP TABLE IF EXISTS `library`.`user_status`;
CREATE TABLE IF NOT EXISTS `library`.`user_status` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(25) NOT NULL,
  `status` VARCHAR(25) NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `user_status_IK_status` (`status`)
);

DROP TABLE IF EXISTS `library`.`role`;
CREATE TABLE IF NOT EXISTS `library`.`role` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(25) NOT NULL,
  `role` VARCHAR(25) NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `role_IK_role` (`role`)
);


DROP TABLE IF EXISTS `library`.`book`;
CREATE TABLE IF NOT EXISTS `library`.`book` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(256) NOT NULL,
  `author` VARCHAR(256) NOT NULL,
  `genre` VARCHAR(256) NOT NULL,
  `isbn` VARCHAR(20) NOT NULL,
  `published_date` DATE NOT NULL,
  `status_id` TINYINT NOT NULL DEFAULT '1',
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `book_IK_title` (`title`),
  INDEX `book_IK_author` (`author`),
  INDEX `book_IK_genre` (`genre`),
  INDEX `book_IK_isbn` (`isbn`),
  INDEX `book_IK_status` (`status_id`)
);

DROP TABLE IF EXISTS `library`.`user`;
CREATE TABLE IF NOT EXISTS `library`.`user` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `usr_pwd` VARCHAR(256) NOT NULL,
  `role_id` TINYINT NOT NULL DEFAULT '2',
  `user_status_id` TINYINT NOT NULL DEFAULT '1',
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `user_IK_email` (`email`),
  INDEX `user_IK_role` (`role_id`),
  INDEX `user_IK_status` (`user_status_id`)
);

DROP TABLE IF EXISTS `library`.`borrow`;
CREATE TABLE IF NOT EXISTS `library`.`borrow` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `book_id` BIGINT UNSIGNED NOT NULL,
  `checkout_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `checkin_date` DATETIME NULL,
  `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` DATETIME ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `borrow_IK_user` (`user_id`),
  INDEX `borrow_IK_book` (`book_id`),
  INDEX `borrow_IK_checkout` (`checkout_date`),
  INDEX `borrow_IK_checkin` (`checkin_date`)
);

*/