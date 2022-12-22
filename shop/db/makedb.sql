-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: study
-- ------------------------------------------------------
-- Server version	5.7.25-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup
--

SET @@GLOBAL.GTID_PURGED='0cbcb64d-3ef6-11e9-86d2-080027b3c2b5:1-14717';

--
-- Table structure for table `buy`
--

DROP TABLE IF EXISTS `buy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buy` (
  `buy_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '구매번호',
  `sel_id` int(11) NOT NULL COMMENT '판매번호 ',
  `member_num` int(11) NOT NULL COMMENT '유저 번호 ',
  `buy_price` int(11) NOT NULL COMMENT '구매가격 ',
  `buy_amount` int(11) NOT NULL COMMENT '구매 총 금액 ',
  `buy_quantity` int(11) NOT NULL COMMENT '구매 수량 ',
  `buy_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '구매일 ',
  PRIMARY KEY (`buy_id`),
  KEY `sel_id` (`sel_id`),
  CONSTRAINT `buy_ibfk_1` FOREIGN KEY (`sel_id`) REFERENCES `sel` (`sel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy`
--

LOCK TABLES `buy` WRITE;
/*!40000 ALTER TABLE `buy` DISABLE KEYS */;
INSERT INTO `buy` VALUES (1,15,2,5000,5000,1,'2019-04-08 00:32:06'),(2,15,2,5000,5000,1,'2019-04-08 00:33:21');
/*!40000 ALTER TABLE `buy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buy_mileage`
--

DROP TABLE IF EXISTS `buy_mileage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buy_mileage` (
  `buymileage_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '구매마일리지 번호',
  `mileage_id` int(11) NOT NULL COMMENT '마일리지 번호',
  `buymileage_type` int(11) NOT NULL COMMENT '마일리지 타입',
  `buymileage_price` int(11) NOT NULL COMMENT '발생가격',
  `buymileage_amount` int(11) NOT NULL COMMENT '발생가격 총액',
  `buymileage_tax` int(11) NOT NULL COMMENT '발생 수수료',
  `buymileage_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '발생일',
  `member_num` int(11) NOT NULL,
  PRIMARY KEY (`buymileage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy_mileage`
--

LOCK TABLES `buy_mileage` WRITE;
/*!40000 ALTER TABLE `buy_mileage` DISABLE KEYS */;
INSERT INTO `buy_mileage` VALUES (1,2,400,850,850,150,'2019-04-08 00:32:45',2);
/*!40000 ALTER TABLE `buy_mileage` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger buymileageamount_update after insert on buy_mileage for each row
    begin
    update mileage set buymileage_amount = new.buymileage_amount where member_num=new.member_num order by new.buymileage_regdate desc limit 1;
    end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `buypoint`
--

DROP TABLE IF EXISTS `buypoint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buypoint` (
  `buypoint_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '구매포인트 번호',
  `buy_id` int(11) NOT NULL COMMENT '구매 번호',
  `member_num` int(11) NOT NULL COMMENT '구매자 번호',
  `mileage_id` int(11) NOT NULL COMMENT '마일리지번호',
  `buypoint_type` int(11) NOT NULL COMMENT '마일리지타입',
  `buypoint_price` int(11) NOT NULL COMMENT '발생가격',
  `buypoint_amount` int(11) NOT NULL COMMENT '발생가격 총액',
  `buypoint_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '발생일',
  `buypoint_deldate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '삭제일',
  PRIMARY KEY (`buypoint_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buypoint`
--

LOCK TABLES `buypoint` WRITE;
/*!40000 ALTER TABLE `buypoint` DISABLE KEYS */;
INSERT INTO `buypoint` VALUES (1,15,2,2,500,1250,400,'2019-04-08 00:32:06','2019-02-28 15:00:00'),(2,15,2,2,500,1250,1250,'2019-04-08 00:33:21','2019-02-28 15:00:00');
/*!40000 ALTER TABLE `buypoint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buypoint_log`
--

DROP TABLE IF EXISTS `buypoint_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buypoint_log` (
  `buyLog_num` int(11) NOT NULL AUTO_INCREMENT,
  `mileage_id` int(11) NOT NULL,
  `buypoint_type` int(11) NOT NULL,
  `buypoint_price` int(11) NOT NULL,
  `buypoint_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `buypoint_deldate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`buyLog_num`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buypoint_log`
--

LOCK TABLES `buypoint_log` WRITE;
/*!40000 ALTER TABLE `buypoint_log` DISABLE KEYS */;
INSERT INTO `buypoint_log` VALUES (1,2,500,1250,'2019-04-08 00:32:06','0000-00-00 00:00:00'),(2,2,501,1000,'2019-04-08 00:32:45','0000-00-00 00:00:00'),(3,2,500,1250,'2019-04-08 00:33:21','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `buypoint_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cash_mileage`
--

DROP TABLE IF EXISTS `cash_mileage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cash_mileage` (
  `cash_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '현금마일리지 번호',
  `mileage_id` int(11) NOT NULL COMMENT '마일리지번호',
  `member_num` int(11) NOT NULL COMMENT '회원 번호',
  `cash_type` int(11) NOT NULL COMMENT '마일리지 타입',
  `cash_price` int(11) NOT NULL COMMENT '마일리지 가격',
  `cash_amount` int(11) NOT NULL COMMENT '마일리지총합',
  `cash_tax` int(11) NOT NULL COMMENT '마일리지 수수료',
  `cash_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cash_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cash_mileage`
--

LOCK TABLES `cash_mileage` WRITE;
/*!40000 ALTER TABLE `cash_mileage` DISABLE KEYS */;
INSERT INTO `cash_mileage` VALUES (1,2,2,100,2550,2550,450,'2019-04-08 00:33:07'),(2,2,2,101,2550,0,0,'2019-04-08 00:33:21');
/*!40000 ALTER TABLE `cash_mileage` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger cashamount_update after insert on cash_mileage for each row
    begin
    update mileage set cash_amount=new.cash_amount where member_num=new.member_num order by new.cash_regdate desc limit 1;
    end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `charge`
--

DROP TABLE IF EXISTS `charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charge` (
  `charge_num` int(11) NOT NULL AUTO_INCREMENT COMMENT '충전번호',
  `member_num` int(11) NOT NULL COMMENT '회원번호',
  `charge_price` int(11) NOT NULL COMMENT '충전금액',
  `charge_type` int(11) NOT NULL COMMENT '충전타입',
  `charge_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '발생일',
  PRIMARY KEY (`charge_num`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge`
--

LOCK TABLES `charge` WRITE;
/*!40000 ALTER TABLE `charge` DISABLE KEYS */;
INSERT INTO `charge` VALUES (1,1,10000,100,'2019-04-04 14:52:14'),(2,1,10000,200,'2019-04-04 14:52:20'),(3,1,10000,300,'2019-04-04 14:52:25'),(4,2,10000,200,'2019-04-08 00:31:32'),(5,2,3000,100,'2019-04-08 00:33:07');
/*!40000 ALTER TABLE `charge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_mileage`
--

DROP TABLE IF EXISTS `credit_mileage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_mileage` (
  `credit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '신용카드마일리지 번호',
  `mileage_id` int(11) NOT NULL COMMENT '마일리지 번호',
  `member_num` int(11) NOT NULL COMMENT '회원번호',
  `credit_type` int(11) NOT NULL COMMENT '마일리지 타입',
  `credit_price` int(11) NOT NULL COMMENT '마일리지금액',
  `credit_amount` int(11) NOT NULL COMMENT '마일리지총액',
  `credit_tax` int(11) NOT NULL COMMENT '마일리지 수수료',
  `credit_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '발생일',
  PRIMARY KEY (`credit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_mileage`
--

LOCK TABLES `credit_mileage` WRITE;
/*!40000 ALTER TABLE `credit_mileage` DISABLE KEYS */;
INSERT INTO `credit_mileage` VALUES (1,2,2,200,8500,8500,1500,'2019-04-08 00:31:32'),(2,2,2,201,5000,3500,0,'2019-04-08 00:32:06'),(3,2,2,201,2450,1050,0,'2019-04-08 00:33:21');
/*!40000 ALTER TABLE `credit_mileage` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger creditamount_update after insert on credit_mileage for each row
    begin
    update mileage set credit_amount=new.credit_amount where member_num=new.member_num order by new.credit_regdate desc limit 1;
    end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `member_num` int(11) NOT NULL AUTO_INCREMENT COMMENT '회원번호',
  `member_id` varchar(15) NOT NULL COMMENT '회원id',
  `member_password` varchar(50) NOT NULL COMMENT '회원비밀번호',
  `member_name` varchar(10) NOT NULL COMMENT '회원이름',
  `member_tel` varchar(13) NOT NULL COMMENT '회원전화번호',
  `member_address` varchar(30) NOT NULL COMMENT '회원주소',
  `member_type` int not null DEFAULT '1' COMMENT '1->정상유저 2->탈퇴유저 3->정지유저 4->???',
  `member_admin` int(11) NOT NULL DEFAULT '0' COMMENT '회원권한',
  `member_gender` int(11) NOT NULL COMMENT '회원 성별',
  `member_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '발생일',
  `member_lastedit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '변경일',
  PRIMARY KEY (`member_num`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'admin','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','jinsol','01050963716','jeonju',1,1,'2019-04-04 14:49:28','2019-04-04 14:49:28'),(2,'admin2','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','admin2','0001112222','jeonju',0,2,'2019-04-04 14:49:59','2019-04-04 14:49:59'),(3,'user1','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','user1','29482381938','seoul',0,1,'2019-04-04 14:50:18','2019-04-04 14:50:18'),(4,'user2','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','user2','24214124124','ii',0,2,'2019-04-04 14:50:36','2019-04-04 14:50:36'),(5,'user3','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','jjiieeiiww','2492949292','ieijet',0,1,'2019-04-04 14:50:55','2019-04-04 14:50:55');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mileage`
--

DROP TABLE IF EXISTS `mileage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mileage` (
  `mileage_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '마일리지번호',
  `member_num` int(11) NOT NULL COMMENT '회원번호',
  `cash_amount` int(11) NOT NULL DEFAULT '0' COMMENT '현금총합',
  `credit_amount` int(11) NOT NULL DEFAULT '0' COMMENT '신용카드총합',
  `phone_amount` int(11) NOT NULL DEFAULT '0' COMMENT '휴대폰 총합',
  `buypoint_amount` int(11) NOT NULL DEFAULT '0' COMMENT '구매포인트총합',
  `buymileage_amount` int(11) NOT NULL DEFAULT '0' COMMENT '구매마일리지 총합',
  PRIMARY KEY (`mileage_id`),
  KEY `member_num` (`member_num`),
  CONSTRAINT `mileage_ibfk_1` FOREIGN KEY (`member_num`) REFERENCES `member` (`member_num`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mileage`
--

LOCK TABLES `mileage` WRITE;
/*!40000 ALTER TABLE `mileage` DISABLE KEYS */;
INSERT INTO `mileage` VALUES (1,1,0,0,0,0,0),(2,2,0,1050,0,0,850),(3,3,0,0,0,0,0),(4,4,0,0,0,0,0),(5,5,0,0,0,0,0);
/*!40000 ALTER TABLE `mileage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_table`
--

DROP TABLE IF EXISTS `phone_mileage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phone_mileage` (
  `phone_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '휴대폰마일리지 번호',
  `mileage_id` int(11) NOT NULL COMMENT '마일리지번호',
  `member_num` int(11) NOT NULL COMMENT '회원번호',
  `phone_type` int(11) NOT NULL COMMENT '마일리지타입',
  `phone_price` int(11) NOT NULL COMMENT '마일리지가격',
  `phone_amount` int(11) NOT NULL COMMENT '마일리지 총합',
  `phone_tax` int(11) NOT NULL COMMENT '마일리지 수수료',
  `phone_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '발생일',
  PRIMARY KEY (`phone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phone_mileage`
--

LOCK TABLES `phone_mileage` WRITE;
/*!40000 ALTER TABLE `phone_mileage` DISABLE KEYS */;
/*!40000 ALTER TABLE `phone_mileage` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 trigger phoneamount_update after insert on phone_mileage for each row
    begin
    update mileage set phone_amount=new.phone_amount where member_num=new.member_num order by new.phone_regdate desc limit 1;
     end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `sel`
--

DROP TABLE IF EXISTS `sel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sel` (
  `sel_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '판매번호',
  `sel_title` varchar(30) NOT NULL COMMENT '판매제목',
  `sel_author` varchar(15) NOT NULL COMMENT '판매자',
  `sel_price` int(11) NOT NULL COMMENT '판매가격',
  `sel_quantity` int(11) NOT NULL COMMENT '판매수량',
  `sel_contents` text NOT NULL COMMENT '판매내용',
  `sel_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '발생일',
  `sel_editdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
  PRIMARY KEY (`sel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sel`
--

LOCK TABLES `sel` WRITE;
/*!40000 ALTER TABLE `sel` DISABLE KEYS */;
INSERT INTO `sel` VALUES (9,'수제 구두','admin',600000,30,'높은값테스트용','2019-03-28 08:23:55','2019-03-28 08:23:55'),(10,'가성비 운동화','admin',20000,300,'중간값테스트','2019-03-28 08:24:13','2019-03-28 08:24:13'),(11,'쪼리','admin',7777,9987,'쪼리','2019-03-28 08:24:32','2019-04-04 14:53:45'),(12,'여성용 구두','admin',35000,-999,'여서용ㅇ구두','2019-03-28 08:24:48','2019-03-28 08:58:46'),(13,'귀여운워커','admin',49900,297,'워커','2019-03-28 08:25:09','2019-03-28 08:31:16'),(14,'초경량 작업화','admin',70000,354,'ㅇㅇ','2019-03-28 08:25:23','2019-04-01 02:52:12'),(15,'신발닦이','admin',5000,9959,'다닦여요','2019-04-01 08:10:08','2019-04-08 00:33:21');
/*!40000 ALTER TABLE `sel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sel_image`
--

DROP TABLE IF EXISTS `sel_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sel_image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '이미지번호',
  `image_filename` varchar(150) NOT NULL COMMENT '파일이름',
  `image_url` varchar(300) NOT NULL COMMENT '파일주소',
  `image_size` int(11) NOT NULL COMMENT '파일크기',
  `sel_id` int(11) NOT NULL COMMENT '판매번호',
  PRIMARY KEY (`image_id`),
  KEY `sel_id` (`sel_id`),
  CONSTRAINT `sel_image_ibfk_1` FOREIGN KEY (`sel_id`) REFERENCES `sel` (`sel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sel_image`
--

LOCK TABLES `sel_image` WRITE;
/*!40000 ALTER TABLE `sel_image` DISABLE KEYS */;
INSERT INTO `sel_image` VALUES (51,'boots1.png','http://192.168.56.13/sel/image/1553761435123709.png',11196,9),(52,'shoe2.png','http://192.168.56.13/sel/image/1553761453893834.png',13053,10),(53,'8.png','http://192.168.56.13/sel/image/1553761472453063.png',16092,11),(54,'high-heel.png','http://192.168.56.13/sel/image/1553761488220295.png',10683,12),(55,'booty66.png','http://192.168.56.13/sel/image/1553761509790169.png',29775,13),(56,'5555.png','http://192.168.56.13/sel/image/1553761523904375.png',20415,14),(57,'shoes1.png','http://192.168.56.13/sel/image/1554106208575047.png',20831,15);
/*!40000 ALTER TABLE `sel_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `view_timeDiff`
--

DROP TABLE IF EXISTS `view_timeDiff`;
/*!50001 DROP VIEW IF EXISTS `view_timeDiff`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_timeDiff` AS SELECT
 1 AS `mileage_id`,
 1 AS `buypoint_id`,
 1 AS `buypoint_type`,
 1 AS `buypoint_amount`,
 1 AS `timediff`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `withdraw`
--

DROP TABLE IF EXISTS `withdraw`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `withdraw` (
  `withdraw_num` int(11) NOT NULL AUTO_INCREMENT COMMENT '출금번호',
  `member_num` int(11) NOT NULL COMMENT '멤버 id',
  `withdraw_price` int(11) NOT NULL COMMENT '출금가격',
  `withdraw_banknum` varchar(20) NOT NULL COMMENT '계좌번호',
  `withdraw_bank` int(11) NOT NULL COMMENT '출금은행',
  `withdraw_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '발생일',
  PRIMARY KEY (`withdraw_num`),
  KEY `member_num` (`member_num`),
  CONSTRAINT `withdraw_ibfk_1` FOREIGN KEY (`member_num`) REFERENCES `member` (`member_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraw`
--

LOCK TABLES `withdraw` WRITE;
/*!40000 ALTER TABLE `withdraw` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdraw` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `view_timeDiff`
--

/*!50001 DROP VIEW IF EXISTS `view_timeDiff`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_timeDiff` AS select `buypoint`.`mileage_id` AS `mileage_id`,`buypoint`.`buypoint_id` AS `buypoint_id`,`buypoint`.`buypoint_type` AS `buypoint_type`,`buypoint`.`buypoint_amount` AS `buypoint_amount`,timestampdiff(DAY,`buypoint`.`buypoint_deldate`,now()) AS `timediff` from `buypoint` where ((select timestampdiff(DAY,`buypoint`.`buypoint_deldate`,now())) > 7) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-28 10:45:47
