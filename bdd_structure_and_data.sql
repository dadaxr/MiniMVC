/*
SQLyog Trial v11.13 (64 bit)
MySQL - 5.5.29-log : Database - medialibs
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`medialibs` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `medialibs`;

/*Table structure for table `a_category_entry` */

DROP TABLE IF EXISTS `a_category_entry`;

CREATE TABLE `a_category_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_entry_id` int(11) NOT NULL,
  `fk_category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_UNIQUE_ENTRY_CATEGORY` (`fk_entry_id`,`fk_category_id`),
  KEY `relation_a_category_entry__category` (`fk_category_id`),
  CONSTRAINT `relation_a_category_entry__category` FOREIGN KEY (`fk_category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `relation_a_category_entry__entry` FOREIGN KEY (`fk_entry_id`) REFERENCES `entry` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `a_category_entry` */

insert  into `a_category_entry`(`id`,`fk_entry_id`,`fk_category_id`) values (20,1,4),(22,1,5),(21,1,6),(23,17,7);

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `order` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `category` */

insert  into `category`(`id`,`title`,`order`,`parent_id`) values (1,'categorie A',0,0),(3,'categore A1',0,1),(4,'categorie C',1,0),(5,'categorie C1',0,4),(6,'categorie C22',0,4),(7,'Categorie-29',2,0),(8,'Categorie-17',0,7),(9,'Categorie-94',3,0),(10,'Categorie C',4,0);

/*Table structure for table `entry` */

DROP TABLE IF EXISTS `entry`;

CREATE TABLE `entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `entry` */

insert  into `entry`(`id`,`title`,`description`) values (1,'Première Fiche','Ceci est la première fiche de l\'annuaire.'),(2,'Deuxième Fiche','Ceci est la deuxième fiche de l\'annuaire'),(3,'3eme fiche','blabla'),(17,'test','oui');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
