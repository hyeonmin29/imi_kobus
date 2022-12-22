-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: stduy
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

SET @@GLOBAL.GTID_PURGED='0cbcb64d-3ef6-11e9-86d2-080027b3c2b5:1-26941';
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

SET @@GLOBAL.GTID_PURGED='0cbcb64d-3ef6-11e9-86d2-080027b3c2b5:1-26941';

--
-- Table structure for table `account_book`
--

DROP TABLE IF EXISTS `account_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_book` (
  `seq` int(11) NOT NULL AUTO_INCREMENT,
  `account_code` varchar(30) DEFAULT NULL,
  `seller_mile_change_seq` int(11) unsigned DEFAULT NULL COMMENT '마일리지 변동내역 코드',
  `buyer_mile_change_seq` int(11) unsigned DEFAULT NULL,
  `trade_type` char(1) DEFAULT NULL COMMENT '거래분류',
  `trade_id` varchar(20) DEFAULT NULL COMMENT '거래번호',
  `seller_id` varchar(20) DEFAULT NULL COMMENT '판매자 아이디',
  `buyer_id` varchar(20) DEFAULT NULL COMMENT '구매자 아이디',
  `total_money` int(11) DEFAULT '0' COMMENT '총거래금액',
  `mile_money` int(11) DEFAULT '0' COMMENT '마일리지 금액',
  `mile_commission_rate` float DEFAULT NULL COMMENT '마일리지 수수료율',
  `mile_commission` int(11) DEFAULT NULL COMMENT '마일리지 수수료',
  `trade_commission_rate` float DEFAULT NULL COMMENT '거래 수수료율',
  `trade_commission` int(11) DEFAULT '0' COMMENT '거래 수수료 금액',
  `trade_mile_money` int(11) DEFAULT '0' COMMENT '마일리지 결제 금액',
  `trade_cell_money` int(11) DEFAULT '0' COMMENT '핸드폰 결제 금액',
  `trade_cell_commission_rate` float DEFAULT NULL COMMENT '핸드폰 결제 수수료율',
  `trade_cell_commission` int(11) DEFAULT NULL COMMENT '핸드폰 결제 수수료',
  `trade_cell_corp` char(2) DEFAULT NULL,
  `trade_card_money` int(11) DEFAULT '0' COMMENT '카드 결제 금액',
  `trade_card_commission_rate` float DEFAULT NULL COMMENT '카드 결제 수수료율',
  `trade_card_commission` int(11) DEFAULT NULL COMMENT '카드 결제 수수료',
  `trade_card_corp` char(2) DEFAULT NULL,
  `seller_mile_before` int(11) DEFAULT NULL COMMENT '판매자 거래전 마일리지',
  `seller_mile_after` int(11) DEFAULT NULL COMMENT '판매자 거래후 마일리지',
  `buyer_mile_before` int(11) DEFAULT NULL COMMENT '구매자 거래전 마일리지',
  `buyer_mile_after` int(11) DEFAULT NULL COMMENT '구매자 거래후 마일리지',
  `admin_modify_id` varchar(20) DEFAULT NULL COMMENT '관리자 최종 수정자',
  `admin_memo` varchar(255) DEFAULT NULL COMMENT '관리자 메모',
  `ins_date` datetime DEFAULT NULL COMMENT '날짜',
  PRIMARY KEY (`seq`),
  KEY `account_code` (`account_code`),
  KEY `seller_mile_change_seq` (`seller_mile_change_seq`),
  KEY `buyer_mile_change_seq` (`buyer_mile_change_seq`),
  KEY `trade_type` (`trade_type`),
  KEY `trade_id` (`trade_id`),
  KEY `seller_id` (`seller_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `total_money` (`total_money`),
  KEY `mile_money` (`mile_money`),
  KEY `mile_commission_rate` (`mile_commission_rate`),
  KEY `cell_money` (`trade_cell_money`),
  KEY `cell_commission_rate` (`trade_cell_commission_rate`),
  KEY `cell_corp` (`trade_cell_corp`),
  KEY `card_money` (`trade_card_money`),
  KEY `card_commission_rate` (`trade_card_commission_rate`),
  KEY `card_corp` (`trade_card_corp`),
  KEY `admin_modify_id` (`admin_modify_id`),
  KEY `ins_date` (`ins_date`),
  KEY `trade_commission_rate` (`trade_commission_rate`),
  KEY `trade_mile_money` (`trade_mile_money`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='매출 장부';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_book`
--

LOCK TABLES `account_book` WRITE;
/*!40000 ALTER TABLE `account_book` DISABLE KEYS */;
INSERT INTO `account_book` VALUES (1,'accoun',NULL,NULL,'c',NULL,'admin',NULL,8500,10000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,43550,53550,NULL,NULL,NULL,NULL,NULL),(2,'account_book201907170052011',NULL,NULL,'c',NULL,'admin',NULL,8500,10000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,43550,53550,NULL,NULL,NULL,NULL,'2019-07-17 00:52:01'),(4,'account_book201907170101081',NULL,NULL,'c',NULL,'admin',NULL,85000,100000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,43550,143550,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-17 01:01:08'),(5,'account_book201907171451421',NULL,NULL,'c',NULL,'admin',NULL,4250,5000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,128550,133550,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-17 14:51:42'),(12,'account_book201907171503591',NULL,NULL,'c',NULL,'admin',NULL,4250,5000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,132800,137800,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-17 15:03:59'),(13,'account_book201907171541541',NULL,NULL,'c',NULL,'admin',NULL,42500,50000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,137050,187050,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-17 15:41:54'),(14,'account_book201907171732181',NULL,NULL,'c',NULL,'admin',NULL,8500,10000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,174550,184550,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-17 17:32:18'),(15,'account_book201907180944241',NULL,NULL,'c',NULL,'admin',NULL,42500,50000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,183050,233050,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-18 09:44:24'),(16,'account_book201907180951581',NULL,NULL,'c',NULL,'admin',NULL,51000,60000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,225550,285550,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-18 09:51:58'),(17,'account_book201907191755541',NULL,NULL,'c',NULL,'admin',NULL,6800,8000,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,0,NULL,NULL,NULL,229450,237450,NULL,NULL,NULL,'회원 마일리지 충전','2019-07-19 17:55:54');
/*!40000 ALTER TABLE `account_book` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy`
--

LOCK TABLES `buy` WRITE;
/*!40000 ALTER TABLE `buy` DISABLE KEYS */;
INSERT INTO `buy` VALUES (1,15,2,5000,5000,1,'2019-04-08 00:32:06'),(2,15,2,5000,5000,1,'2019-04-08 00:33:21'),(3,15,1,5000,5000,1,'2019-07-10 02:25:59'),(4,15,1,5000,5000,1,'2019-07-11 08:51:48'),(5,15,1,5000,5000,1,'2019-07-17 07:04:02'),(10,15,1,5000,30000,6,'2019-07-18 00:52:10'),(11,15,2,5000,5000,1,'2019-07-25 04:26:42'),(12,13,2,49900,49900,1,'2019-07-25 05:20:22'),(13,15,2,5000,5000,1,'2019-07-25 06:09:03'),(16,15,2,5000,5000,1,'2019-07-26 01:45:01'),(17,11,4,7777,15554,2,'2019-07-26 05:47:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buy_mileage`
--

LOCK TABLES `buy_mileage` WRITE;
/*!40000 ALTER TABLE `buy_mileage` DISABLE KEYS */;
INSERT INTO `buy_mileage` VALUES (1,2,400,850,850,150,'2019-04-08 00:32:45',2),(2,1,400,8500,8500,1500,'2019-07-18 06:23:01',1),(3,2,403,999150,1000000,0,'2019-07-25 02:50:50',2),(4,1,400,8500,17000,1500,'2019-07-26 02:16:58',1),(5,5,400,4250,4250,750,'2019-07-26 07:47:28',5);
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buypoint`
--

LOCK TABLES `buypoint` WRITE;
/*!40000 ALTER TABLE `buypoint` DISABLE KEYS */;
INSERT INTO `buypoint` VALUES (1,15,2,2,500,1250,0,'2019-04-08 00:32:06','2019-07-18 06:23:01'),(2,15,2,2,500,1250,0,'2019-04-08 00:33:21','2019-07-18 06:23:01'),(3,0,1,1,502,80000,64650,'2019-07-09 04:40:19','2019-07-26 02:16:58'),(4,15,1,1,503,1250,1250,'2019-07-10 02:25:59','2019-07-25 02:50:25'),(5,15,1,1,500,1250,0,'2019-07-11 08:51:48','2019-07-26 07:47:28'),(6,15,1,1,500,1250,0,'2019-07-17 07:04:02','2019-07-26 07:47:28'),(11,15,1,1,500,7500,5750,'2019-07-18 00:52:10','2019-07-26 07:47:28'),(12,0,1,1,502,1000000,1000000,'2019-07-25 02:50:50','2019-08-01 02:50:50'),(13,15,2,2,500,1250,1250,'2019-07-25 04:26:42','2019-08-01 04:26:42'),(14,13,2,2,500,12475,12475,'2019-07-25 05:20:22','2019-08-01 05:20:22'),(15,15,2,2,500,1250,1250,'2019-07-25 06:09:03','2019-08-01 06:09:03'),(18,15,2,2,500,1250,1250,'2019-07-26 01:45:01','2019-08-02 01:45:01'),(19,11,4,4,500,3889,3889,'2019-07-26 05:47:43','2019-08-02 05:47:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buypoint_log`
--

LOCK TABLES `buypoint_log` WRITE;
/*!40000 ALTER TABLE `buypoint_log` DISABLE KEYS */;
INSERT INTO `buypoint_log` VALUES (1,2,500,1250,'2019-04-08 00:32:06','0000-00-00 00:00:00'),(2,2,501,1000,'2019-04-08 00:32:45','0000-00-00 00:00:00'),(3,2,500,1250,'2019-04-08 00:33:21','0000-00-00 00:00:00'),(4,1,502,80000,'2019-07-09 04:40:19','0000-00-00 00:00:00'),(5,1,500,1250,'2019-07-10 02:25:59','0000-00-00 00:00:00'),(6,1,500,1250,'2019-07-11 08:51:48','0000-00-00 00:00:00'),(7,1,500,1250,'2019-07-17 07:04:02','0000-00-00 00:00:00'),(12,1,500,7500,'2019-07-18 00:52:10','0000-00-00 00:00:00'),(13,1,502,70000,'2019-07-18 05:27:35','0000-00-00 00:00:00'),(14,1,501,10000,'2019-07-18 06:23:01','0000-00-00 00:00:00'),(15,1,502,1000000,'2019-07-25 02:50:50','0000-00-00 00:00:00'),(16,2,500,1250,'2019-07-25 04:26:42','0000-00-00 00:00:00'),(17,2,500,12475,'2019-07-25 05:20:22','0000-00-00 00:00:00'),(18,2,500,1250,'2019-07-25 06:09:03','0000-00-00 00:00:00'),(21,2,500,1250,'2019-07-26 01:45:01','0000-00-00 00:00:00'),(22,1,502,50000,'2019-07-26 02:16:12','0000-00-00 00:00:00'),(23,1,502,300000,'2019-07-26 02:16:38','0000-00-00 00:00:00'),(24,1,501,10000,'2019-07-26 02:16:58','0000-00-00 00:00:00'),(25,4,500,3889,'2019-07-26 05:47:43','0000-00-00 00:00:00'),(26,5,501,5000,'2019-07-26 07:47:28','0000-00-00 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cash_mileage`
--

LOCK TABLES `cash_mileage` WRITE;
/*!40000 ALTER TABLE `cash_mileage` DISABLE KEYS */;
INSERT INTO `cash_mileage` VALUES (1,2,2,100,2550,2550,450,'2019-04-08 00:33:07'),(2,2,2,101,2550,0,0,'2019-04-08 00:33:21'),(9,1,1,100,8500,8500,1500,'2019-06-28 05:38:15'),(14,1,1,100,4250,12750,750,'2019-06-28 06:43:50'),(17,1,1,100,4250,17000,750,'2019-06-28 07:32:06'),(18,1,1,100,5100,22100,900,'2019-06-28 07:32:18'),(19,1,1,100,5950,28050,1050,'2019-07-01 06:41:31'),(20,1,1,101,5000,23050,0,'2019-07-10 02:25:59'),(21,1,1,101,5000,18050,0,'2019-07-11 08:51:48'),(28,1,1,100,4250,22300,750,'2019-07-17 06:03:59'),(29,1,1,101,5000,17300,0,'2019-07-17 07:04:02'),(30,1,1,100,8500,25800,1500,'2019-07-17 08:32:18'),(34,1,1,100,42500,68300,7500,'2019-07-18 00:44:24'),(36,1,1,101,30000,38300,0,'2019-07-18 00:52:10'),(37,3,3,103,70000,70000,0,'2019-07-18 05:27:35'),(38,1,1,102,3900,34400,0,'2019-07-18 07:44:00'),(39,1,1,102,3900,30500,0,'2019-07-18 08:08:37'),(40,1,1,102,10900,19600,0,'2019-07-18 08:12:26'),(41,1,1,102,6900,12700,0,'2019-07-18 08:17:30'),(42,1,1,100,6800,19500,1200,'2019-07-19 08:55:54'),(43,2,2,103,1000000,1000000,0,'2019-07-25 02:50:50'),(44,2,2,101,5000,995000,0,'2019-07-25 04:26:42'),(45,2,2,101,49900,945100,0,'2019-07-25 05:20:22'),(46,2,2,105,49900,69400,0,'2019-07-25 05:20:22'),(47,2,2,101,5000,940100,0,'2019-07-25 06:09:03'),(48,1,1,105,5000,24500,0,'2019-07-25 06:09:03'),(53,2,2,101,5000,935100,0,'2019-07-26 01:45:01'),(54,1,1,105,5000,29500,0,'2019-07-26 01:45:01'),(55,4,4,103,100000,100000,0,'2019-07-26 02:16:12'),(56,1,1,102,29500,0,0,'2019-07-26 04:17:56'),(57,4,4,101,15554,84446,0,'2019-07-26 05:47:43'),(58,1,1,105,15554,15554,0,'2019-07-26 05:47:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge`
--

LOCK TABLES `charge` WRITE;
/*!40000 ALTER TABLE `charge` DISABLE KEYS */;
INSERT INTO `charge` VALUES (1,1,10000,100,'2019-04-04 14:52:14'),(2,1,10000,200,'2019-04-04 14:52:20'),(3,1,10000,300,'2019-04-04 14:52:25'),(4,2,10000,200,'2019-04-08 00:31:32'),(5,2,3000,100,'2019-04-08 00:33:07'),(12,1,10000,100,'2019-06-28 05:38:15'),(20,1,5000,100,'2019-06-28 06:43:50'),(23,1,5000,100,'2019-06-28 07:32:06'),(24,1,6000,100,'2019-06-28 07:32:18'),(25,1,7000,100,'2019-07-01 06:41:31'),(26,1,5000,300,'2019-07-01 06:47:18'),(27,1,5000,200,'2019-07-01 07:18:16'),(28,1,5000,200,'2019-07-01 07:19:06'),(29,1,5000,200,'2019-07-01 07:19:25'),(30,1,5000,300,'2019-07-01 07:19:29'),(31,1,5000,300,'2019-07-01 09:30:04'),(32,3,700000,200,'2019-07-03 02:49:25'),(36,1,10000,100,'2019-07-16 15:36:04'),(39,1,10000,100,'2019-07-16 15:52:01'),(41,1,100000,300,'2019-07-16 16:01:08'),(42,1,5000,200,'2019-07-17 05:51:42'),(48,1,5000,100,'2019-07-17 06:03:59'),(49,1,50000,300,'2019-07-17 06:41:54'),(50,1,10000,100,'2019-07-17 08:32:18'),(51,1,50000,100,'2019-07-18 00:44:24'),(52,1,60000,200,'2019-07-18 00:51:58'),(53,1,8000,100,'2019-07-19 08:55:54');
/*!40000 ALTER TABLE `charge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charge_list`
--

DROP TABLE IF EXISTS `charge_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charge_list` (
  `seq` int(11) NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `account_code` char(6) DEFAULT NULL COMMENT '장부코드',
  `order_id` varchar(45) DEFAULT NULL COMMENT '주문번호',
  `tid` varchar(45) DEFAULT NULL COMMENT '거래번호',
  `charge_state` enum('connect','process','success','fail','cancel') DEFAULT NULL COMMENT '상태',
  `charge_money` int(11) DEFAULT NULL COMMENT '충전 금액',
  `charge_commission` int(11) DEFAULT NULL COMMENT '수수료',
  `charge_commission_rate` float DEFAULT NULL COMMENT '수수료율',
  `user_id` varchar(20) DEFAULT NULL COMMENT '회원 아이디',
  `user_name` varchar(20) DEFAULT NULL COMMENT '회원 성명',
  `charge_messge` tinytext COMMENT '충전 메시지',
  `complete_time` datetime DEFAULT NULL COMMENT '완료 시간',
  PRIMARY KEY (`seq`),
  KEY `account_code` (`account_code`),
  KEY `order_id` (`order_id`),
  KEY `tid` (`tid`),
  KEY `charge_state` (`charge_state`),
  KEY `user_id` (`user_id`),
  KEY `user_name` (`user_name`),
  KEY `complete_time` (`complete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='충전 내역';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge_list`
--

LOCK TABLES `charge_list` WRITE;
/*!40000 ALTER TABLE `charge_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `charge_list` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_mileage`
--

LOCK TABLES `credit_mileage` WRITE;
/*!40000 ALTER TABLE `credit_mileage` DISABLE KEYS */;
INSERT INTO `credit_mileage` VALUES (1,2,2,200,8500,8500,1500,'2019-04-08 00:31:32'),(2,2,2,201,5000,3500,0,'2019-04-08 00:32:06'),(3,2,2,201,2450,1050,0,'2019-04-08 00:33:21'),(4,1,1,200,4250,4250,750,'2019-07-01 07:18:16'),(5,1,1,200,4250,8500,750,'2019-07-01 07:19:06'),(6,1,1,200,4250,12750,750,'2019-07-01 07:19:25'),(7,3,3,200,595000,595000,105000,'2019-07-03 02:49:25'),(8,1,1,200,4250,17000,750,'2019-07-17 05:51:42'),(9,1,1,200,51000,68000,9000,'2019-07-18 00:51:58'),(10,2,2,203,998950,1000000,0,'2019-07-25 02:50:50'),(11,4,4,203,30000,30000,0,'2019-07-26 02:16:12'),(12,5,5,203,300000,300000,0,'2019-07-26 02:16:38'),(13,1,1,202,41400,26600,0,'2019-07-26 04:17:56');
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
  `member_admin` int(11) NOT NULL DEFAULT '0' COMMENT '회원권한',
  `member_gender` int(11) NOT NULL COMMENT '회원 성별',
  `member_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '발생일',
  `member_lastedit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '뱐걍ㅇ;ㄹ',
  PRIMARY KEY (`member_num`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'admin','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','jinsol','01050963716','jeonju',1,1,'2019-04-04 14:49:28','2019-04-04 14:49:28'),(2,'admin2','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','admin2','0001112222','jeonju',1,2,'2019-04-04 14:49:59','2019-04-04 14:49:59'),(3,'user1','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','user1','29482381938','seoul',0,1,'2019-04-04 14:50:18','2019-04-04 14:50:18'),(4,'user2','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','user2','24214124124','ii',0,2,'2019-04-04 14:50:36','2019-04-04 14:50:36'),(5,'user3','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','jjiieeiiww','2492949292','ieijet',0,1,'2019-04-04 14:50:55','2019-04-04 14:50:55');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mile_change_list`
--

DROP TABLE IF EXISTS `mile_change_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mile_change_list` (
  `mile_change_seq` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `user_no` int(11) unsigned DEFAULT NULL COMMENT '회원번호',
  `user_id` varchar(20) DEFAULT NULL COMMENT '회원아이디',
  `change_type` char(1) DEFAULT 'a' COMMENT '타입(a=>충전,b=>출금,c=>판매,d=>구매,e=>구매취소,f=>이벤트,g=>유효기간만료,i=>수정,j=> 정정)',
  `mile_money` int(11) unsigned NOT NULL COMMENT '충전 금액',
  `mile_use_before` int(11) DEFAULT NULL COMMENT '사용전 마일리지',
  `mile_use_after` int(11) DEFAULT NULL COMMENT '사용후 마일리지',
  `account_code` char(6) DEFAULT NULL COMMENT '장부코드',
  `mile_content` varchar(50) DEFAULT NULL COMMENT '마일리지 내용',
  `mile_req_date` datetime DEFAULT NULL COMMENT '등록일시',
  `trade_id` varchar(20) DEFAULT NULL COMMENT '거래번호',
  `mile_state` enum('continue','success','cancel') DEFAULT NULL COMMENT '(CONTINUE=>진행중,SUCCESS=>처리완료,CANCEL=>취소)',
  PRIMARY KEY (`mile_change_seq`),
  KEY `user_no` (`user_no`),
  KEY `user_id` (`user_id`),
  KEY `account_code` (`account_code`),
  KEY `trade_id` (`trade_id`),
  KEY `change_type` (`change_type`),
  KEY `mile_req_date` (`mile_req_date`),
  KEY `mile_state` (`mile_state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='마일리지 변동 내역';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mile_change_list`
--

LOCK TABLES `mile_change_list` WRITE;
/*!40000 ALTER TABLE `mile_change_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `mile_change_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mile_change_log`
--

DROP TABLE IF EXISTS `mile_change_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mile_change_log` (
  `seq` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `user_no` int(11) unsigned NOT NULL COMMENT '회원번호',
  `user_id` varchar(20) NOT NULL COMMENT '회원아이디',
  `account_code` char(6) DEFAULT NULL COMMENT '장부코드',
  `change_type` char(1) NOT NULL COMMENT '타입(a=>충전,b=>출금,c=>판매,d=>구매,e=>구매취소,f=>이벤트,g=>유효기간만료,i=>수정,j=> 정정)',
  `change_date` datetime NOT NULL COMMENT '수정일자',
  `change_mileage` int(11) unsigned NOT NULL COMMENT '수정마일리지',
  `before_mileage` int(11) unsigned DEFAULT NULL COMMENT '변경 이전 마일리지',
  `current_mileage` int(11) unsigned NOT NULL COMMENT '변경 이후 마일리지',
  `admin_id` varchar(20) DEFAULT NULL COMMENT '관리자 아이디',
  `admin_name` varchar(20) DEFAULT NULL COMMENT '관리자 이름',
  `change_reason` varchar(100) DEFAULT NULL COMMENT '변경사유',
  `mile_change_seq` int(11) unsigned DEFAULT NULL COMMENT 'mile_change_list의 시퀀스',
  PRIMARY KEY (`seq`),
  KEY `user_no` (`user_no`),
  KEY `user_id` (`user_id`),
  KEY `change_type` (`change_type`),
  KEY `change_date` (`change_date`),
  KEY `admin_id` (`admin_id`),
  KEY `admin_name` (`admin_name`),
  KEY `change_reason` (`change_reason`),
  KEY `mile_change_seq` (`mile_change_seq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='마일리지 변동 내역 로그';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mile_change_log`
--

LOCK TABLES `mile_change_log` WRITE;
/*!40000 ALTER TABLE `mile_change_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `mile_change_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mile_detail_list`
--

DROP TABLE IF EXISTS `mile_detail_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mile_detail_list` (
  `seq` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호',
  `user_no` int(11) unsigned NOT NULL COMMENT '회원번호',
  `user_id` varchar(20) NOT NULL COMMENT '아이디',
  `account_code` varchar(30) NOT NULL,
  `mile_code` char(6) NOT NULL COMMENT '마일리지코드',
  `payment_money` int(11) unsigned NOT NULL COMMENT '금액',
  `remain_money` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '잔여금액',
  `payment_result` char(1) DEFAULT NULL COMMENT '결제결과',
  `payment_date` datetime NOT NULL COMMENT '입금일',
  `used_date` datetime DEFAULT NULL COMMENT '사용일',
  `game_code` smallint(6) unsigned DEFAULT '0' COMMENT '게임코드(0:전체게임)',
  `exp_start_date` date DEFAULT NULL COMMENT '만료시작일',
  `exp_end_date` date DEFAULT NULL COMMENT '만료종료일',
  PRIMARY KEY (`seq`),
  KEY `user_no` (`user_no`),
  KEY `user_id` (`user_id`),
  KEY `account_code` (`account_code`),
  KEY `mile_code` (`mile_code`),
  KEY `payment_result` (`payment_result`),
  KEY `payment_date` (`payment_date`),
  KEY `used_date` (`used_date`),
  KEY `game_code` (`game_code`),
  KEY `exp_date` (`exp_start_date`,`exp_end_date`),
  KEY `exp_end_date` (`exp_end_date`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='마일리지 적립 내역';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mile_detail_list`
--

LOCK TABLES `mile_detail_list` WRITE;
/*!40000 ALTER TABLE `mile_detail_list` DISABLE KEYS */;
INSERT INTO `mile_detail_list` VALUES (1,1,'admin','accoun','1',51000,285550,NULL,'2019-07-18 09:51:58',NULL,0,NULL,NULL),(2,1,'admin','accoun','1',0,276550,NULL,'2019-07-18 09:52:10',NULL,0,NULL,NULL),(3,1,'admin','accoun','1',900,251150,NULL,'2019-07-18 16:44:00',NULL,0,NULL,NULL),(4,1,'admin','account_book201907181708371','1',900,247250,NULL,'2019-07-18 17:08:37',NULL,0,NULL,NULL),(5,1,'admin','account_book201907181712261','1',0,236350,NULL,'2019-07-18 17:12:26',NULL,0,NULL,NULL),(6,1,'admin','account_book201907181717301','1',0,229450,NULL,'2019-07-18 17:17:30',NULL,0,NULL,NULL),(7,1,'admin','account_book201907191755541','1',6800,237450,NULL,'2019-07-19 17:55:54',NULL,0,NULL,NULL),(8,2,'admin2','account_book201907251326422','2',0,4000000,NULL,'2019-07-25 13:26:42',NULL,0,NULL,NULL),(9,2,'admin2','account_book201907251420222','2',0,3995000,NULL,'2019-07-25 14:20:22',NULL,0,NULL,NULL),(10,2,'admin2','account_book201907251509032','2',0,3069400,NULL,'2019-07-25 15:09:03',NULL,0,NULL,NULL),(13,2,'admin2','account_book201907261045012','2',5000,3940100,NULL,'2019-07-26 10:45:01',NULL,0,NULL,NULL),(14,1,'admin','account_book201907261317561','1',0,183850,NULL,'2019-07-26 13:17:56',NULL,0,NULL,NULL),(15,1,'admin','account_book201907261447434','1',15554,183850,NULL,'2019-07-26 14:47:43',NULL,0,NULL,NULL),(16,4,'user2','account_book201907261447434','4',15554,180000,NULL,'2019-07-26 14:47:43',NULL,0,NULL,NULL);
/*!40000 ALTER TABLE `mile_detail_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mile_detail_log`
--

DROP TABLE IF EXISTS `mile_detail_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mile_detail_log` (
  `seq` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '고유번호',
  `detail_id` int(11) unsigned NOT NULL COMMENT 'mile_detail_list 고유번호',
  `mile_code` char(6) DEFAULT NULL COMMENT '마일리지코드',
  `user_no` int(11) unsigned NOT NULL COMMENT '회원번호',
  `user_id` varchar(20) NOT NULL COMMENT '아이디',
  `mile_money` int(11) unsigned NOT NULL COMMENT '금액',
  `trade_id` varchar(20) DEFAULT NULL COMMENT '거래번호',
  `ins_type` char(1) NOT NULL COMMENT '사용구분',
  `ins_result` char(1) NOT NULL COMMENT '사용결과',
  `ins_date` datetime NOT NULL COMMENT '사용일시',
  `mile_state` enum('continue','success','cancel') DEFAULT NULL COMMENT '마일리지상태(continue, success, cancel)',
  PRIMARY KEY (`seq`),
  KEY `detail_id` (`detail_id`),
  KEY `user_no` (`user_no`),
  KEY `user_id` (`user_id`),
  KEY `ins_type` (`ins_type`),
  KEY `ins_result` (`ins_result`),
  KEY `ins_date` (`ins_date`),
  KEY `user_trade` (`user_no`,`trade_id`),
  KEY `mile_state` (`mile_state`),
  KEY `user_trade_state` (`user_no`,`trade_id`,`mile_state`),
  KEY `mile_code` (`mile_code`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='마일리지 적립 로그';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mile_detail_log`
--

LOCK TABLES `mile_detail_log` WRITE;
/*!40000 ALTER TABLE `mile_detail_log` DISABLE KEYS */;
INSERT INTO `mile_detail_log` VALUES (1,1,'1',1,'admin',51000,'charge20190718095158','a','1','2019-07-18 09:51:58','continue'),(2,2,'1',1,'admin',0,'charge20190718095210','a','1','2019-07-18 09:52:10','continue'),(3,3,'1',1,'admin',900,'withdraw201907181644','a','1','2019-07-18 16:44:00','continue'),(4,4,'1',1,'admin',900,'withdraw201907181708','a','1','2019-07-18 17:08:37',''),(5,5,'1',1,'admin',0,'withdraw201907181712','w','1','2019-07-18 17:12:26',''),(6,6,'1',1,'admin',0,'withdraw201907181717','w','1','2019-07-18 17:17:30',''),(7,7,'1',1,'admin',6800,'charge20190719175554','a','1','2019-07-19 17:55:54',''),(8,8,'2',2,'admin2',0,'buy201907251326422','a','1','2019-07-25 13:26:42','continue'),(9,9,'2',2,'admin2',0,'buy201907251420222','a','1','2019-07-25 14:20:22','continue'),(10,10,'2',2,'admin2',0,'buy201907251509032','a','1','2019-07-25 15:09:03','continue'),(13,13,'2',2,'admin2',5000,'buy201907261045012','b','1','2019-07-26 10:45:01','continue'),(14,14,'1',1,'admin',0,'withdraw201907261317','w','1','2019-07-26 13:17:56',''),(15,15,'1',1,'user2',15554,'buy201907261447434','s','1','2019-07-26 14:47:43','continue'),(16,16,'4',4,'user2',15554,'buy201907261447434','b','1','2019-07-26 14:47:43','continue');
/*!40000 ALTER TABLE `mile_detail_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mile_group`
--

DROP TABLE IF EXISTS `mile_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mile_group` (
  `mile_seq` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `mile_name` varchar(20) DEFAULT NULL,
  `mile_type` int(11) DEFAULT NULL COMMENT '마일리지 성격',
  `mile_code` char(6) DEFAULT NULL,
  `mile_priority` int(11) unsigned DEFAULT NULL COMMENT '우선순위',
  `mile_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mile_function` char(1) DEFAULT '0',
  PRIMARY KEY (`mile_seq`),
  KEY `mile_type` (`mile_type`),
  KEY `mile_code` (`mile_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='마일리지 그룹';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mile_group`
--

LOCK TABLES `mile_group` WRITE;
/*!40000 ALTER TABLE `mile_group` DISABLE KEYS */;
INSERT INTO `mile_group` VALUES (1,'출금전용',1,'1',1,'2019-07-17 06:38:48','0'),(2,'사용및출금가능',1,'2',1,'2019-07-17 06:38:48','0'),(3,'사용만가능',1,'3',1,'2019-07-17 06:38:48','0'),(4,'사용중',1,'4',1,'2019-07-17 06:38:48','0'),(5,'출금요청중',1,'5',1,'2019-07-17 06:38:48','0'),(6,'사용불가',1,'6',1,'2019-07-17 06:38:48','0');
/*!40000 ALTER TABLE `mile_group` ENABLE KEYS */;
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
INSERT INTO `mileage` VALUES (1,1,15554,26600,140250,1090875,17000),(2,2,935100,1000000,1000000,1099375,1000000),(3,3,70000,595000,0,92900,0),(4,4,84446,30000,50000,1094764,0),(5,5,0,300000,0,1090514,4250);
/*!40000 ALTER TABLE `mileage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mileage_fill`
--

DROP TABLE IF EXISTS `mileage_fill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mileage_fill` (
  `oid` varchar(50) DEFAULT NULL COMMENT '주문번호',
  `tid` varchar(50) DEFAULT NULL COMMENT '거래번호',
  `state` enum('connect','process','success','fail','cancel') NOT NULL DEFAULT 'connect' COMMENT '상태 => 접속 : connect , 충전중 : process , 완료 : success , 실패 : fail , 취소 : cancel',
  `account_code` char(6) DEFAULT NULL COMMENT '장부코드',
  `mile_code` char(6) DEFAULT NULL COMMENT '마일리지코드',
  `price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '금액',
  `before_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '충전 이전 마일리지',
  `after_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '충전 이후 마일리지',
  `user_id` varchar(20) NOT NULL DEFAULT '' COMMENT '사용자 아이디',
  `user_name` varchar(20) NOT NULL DEFAULT '' COMMENT '사용자 이름',
  `message` tinytext NOT NULL COMMENT '기타 메세지',
  `response_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '완료시간',
  KEY `oid` (`oid`),
  KEY `user_id` (`user_id`),
  KEY `response_date` (`response_date`),
  KEY `account_code` (`account_code`),
  KEY `mile_code` (`mile_code`),
  KEY `pay_oid` (`account_code`,`oid`,`tid`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='마일리지 충전 전체로그';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mileage_fill`
--

LOCK TABLES `mileage_fill` WRITE;
/*!40000 ALTER TABLE `mileage_fill` DISABLE KEYS */;
INSERT INTO `mileage_fill` VALUES ('charge201907170036041',NULL,'success','accoun','1',10000,43550,53550,'admin','jinsol','충전중','2019-07-17 00:36:04'),('charge201907170052011',NULL,'success','accoun','1',10000,43550,53550,'admin','jinsol','충전중','2019-07-17 00:52:01'),('charge201907170101081',NULL,'success','accoun','1',100000,43550,143550,'admin','jinsol','충전중','2019-07-17 01:01:08'),('charge201907171451421',NULL,'success','accoun','1',5000,128550,133550,'admin','jinsol','충전중','2019-07-17 14:51:42'),('charge201907171503591',NULL,'success','accoun','1',5000,132800,137800,'admin','jinsol','충전완료','2019-07-17 15:03:59'),('charge201907171541541',NULL,'success','accoun','1',50000,137050,187050,'admin','jinsol','충전완료','2019-07-17 15:41:54'),('charge201907171732181',NULL,'success','accoun','1',10000,174550,184550,'admin','jinsol','충전완료','2019-07-17 17:32:18'),('charge201907180944241',NULL,'success','accoun','1',50000,183050,233050,'admin','jinsol','충전완료','2019-07-18 09:44:24'),('charge201907180951581',NULL,'success','accoun','1',60000,225550,285550,'admin','jinsol','충전완료','2019-07-18 09:51:58'),('charge201907191755541',NULL,'success','accoun','1',8000,229450,237450,'admin','jinsol','충전완료','2019-07-19 17:55:54');
/*!40000 ALTER TABLE `mileage_fill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mileage_fill_succ`
--

DROP TABLE IF EXISTS `mileage_fill_succ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mileage_fill_succ` (
  `oid` varchar(50) DEFAULT NULL COMMENT '주문번호',
  `tid` varchar(50) DEFAULT NULL COMMENT '거래번호',
  `state` enum('connect','process','success','fail','cancel') NOT NULL DEFAULT 'connect' COMMENT '상태 => 접속 : connect , 충전중 : process , 완료 : success , 실패 : fail , 취소 : cancel',
  `account_code` char(6) DEFAULT NULL COMMENT '장부코드',
  `mile_code` char(6) DEFAULT NULL COMMENT '마일리지코드',
  `price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '금액',
  `before_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '충전이전 마일리지',
  `after_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '충전이후 마일리지',
  `user_id` varchar(20) NOT NULL DEFAULT '' COMMENT '사용자 아이디',
  `user_name` varchar(20) NOT NULL DEFAULT '' COMMENT '사용자 이름',
  `message` tinytext NOT NULL,
  `response_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `oid` (`oid`),
  KEY `user_id` (`user_id`),
  KEY `account_code` (`account_code`),
  KEY `mile_code` (`mile_code`),
  KEY `pay_oid` (`account_code`,`oid`,`tid`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='마일리지 충전 성공로그';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mileage_fill_succ`
--

LOCK TABLES `mileage_fill_succ` WRITE;
/*!40000 ALTER TABLE `mileage_fill_succ` DISABLE KEYS */;
INSERT INTO `mileage_fill_succ` VALUES ('charge201907170101081',NULL,'success','accoun','1',100000,43550,143550,'admin','jinsol','충전중','2019-07-17 01:01:08'),('charge201907171451421',NULL,'success','accoun','1',5000,128550,133550,'admin','jinsol','충전중','2019-07-17 14:51:42'),('charge201907171503591',NULL,'success','accoun','1',5000,132800,137800,'admin','jinsol','충전완료','2019-07-17 15:03:59'),('charge201907171541541',NULL,'success','accoun','1',50000,137050,187050,'admin','jinsol','충전완료','2019-07-17 15:41:54'),('charge201907171732181',NULL,'success','accoun','1',10000,174550,184550,'admin','jinsol','충전완료','2019-07-17 17:32:18'),('charge201907180944241',NULL,'success','accoun','1',50000,183050,233050,'admin','jinsol','충전완료','2019-07-18 09:44:24'),('charge201907180951581',NULL,'success','accoun','1',60000,225550,285550,'admin','jinsol','충전완료','2019-07-18 09:51:58'),('charge201907191755541',NULL,'success','accoun','1',8000,229450,237450,'admin','jinsol','충전완료','2019-07-19 17:55:54');
/*!40000 ALTER TABLE `mileage_fill_succ` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_table`
--

DROP TABLE IF EXISTS `new_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_table` (
  `p_key` int(11) NOT NULL AUTO_INCREMENT,
  `data1` int(11) DEFAULT NULL,
  `data2` int(11) DEFAULT NULL,
  `data3` int(11) DEFAULT NULL,
  `data4` int(11) DEFAULT NULL,
  PRIMARY KEY (`p_key`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_table`
--

LOCK TABLES `new_table` WRITE;
/*!40000 ALTER TABLE `new_table` DISABLE KEYS */;
INSERT INTO `new_table` VALUES (1,1,2,3,4),(2,1,2,3,4),(3,1,2,3,4),(4,1,2,3,4),(5,1,2,3,4),(6,1,2,3,4),(7,1,2,3,4),(8,1,2,3,4),(9,1,2,3,4),(10,1,2,3,4),(11,1,2,3,4),(12,1,2,3,4),(13,1,2,3,4),(14,1,2,3,4),(15,1,NULL,NULL,NULL),(16,1,NULL,NULL,NULL),(17,1,NULL,NULL,NULL),(18,1,2,3,4),(19,1,2,3,4),(20,1,2,3,4),(21,1,2,3,4),(22,1,2,3,4),(23,1,2,3,4),(24,1,2,3,4);
/*!40000 ALTER TABLE `new_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phone_mileage`
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phone_mileage`
--

LOCK TABLES `phone_mileage` WRITE;
/*!40000 ALTER TABLE `phone_mileage` DISABLE KEYS */;
INSERT INTO `phone_mileage` VALUES (1,1,1,300,4250,4250,750,'2019-07-01 06:47:18'),(2,1,1,300,4250,8500,750,'2019-07-01 07:19:29'),(3,1,1,300,4250,12750,750,'2019-07-01 09:30:04'),(4,1,1,300,85000,97750,15000,'2019-07-16 16:01:08'),(5,1,1,300,42500,140250,7500,'2019-07-17 06:41:54'),(6,2,2,303,1000000,1000000,0,'2019-07-25 02:50:50'),(7,4,4,303,50000,50000,0,'2019-07-26 02:16:12');
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
  `state` int(11) NOT NULL DEFAULT '1',
  `sel_check` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sel`
--

LOCK TABLES `sel` WRITE;
/*!40000 ALTER TABLE `sel` DISABLE KEYS */;
INSERT INTO `sel` VALUES (9,'수제 구두','admin',600000,30,'높은값테스트용','2019-03-28 08:23:55','2019-03-28 08:23:55',1,1),(10,'가성비 운동화','admin',20000,300,'중간값테스트','2019-03-28 08:24:13','2019-03-28 08:24:13',1,1),(11,'쪼리','admin',7777,9985,'쪼리','2019-03-28 08:24:32','2019-07-26 05:47:43',1,1),(12,'여성용 구두','admin',35000,-999,'여서용ㅇ구두','2019-03-28 08:24:48','2019-07-26 02:11:51',3,1),(13,'귀여운워커','admin',49900,296,'워커','2019-03-28 08:25:09','2019-07-25 05:20:22',1,1),(14,'초경량 작업화','admin',70000,354,'ㅇㅇ','2019-03-28 08:25:23','2019-04-01 02:52:12',1,1),(15,'신발닦이','admin',5000,9947,'다닦여요','2019-04-01 08:10:08','2019-07-26 01:45:01',1,1);
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
INSERT INTO `sel_image` VALUES (51,'boots1.png','http://192.168.56.116/shop/sel/image/1553761435123709.png',11196,9),(52,'shoe2.png','http://192.168.56.13116/shop/sel/image/1553761453893834.png',13053,10),(53,'8.png','http://192.168.56.116/shop/sel/image/1553761472453063.png',16092,11),(54,'high-heel.png','http://192.168.56.116/shop/sel/image/1553761488220295.png',10683,12),(55,'booty66.png','http://192.168.56.116/shop/sel/image/1553761509790169.png',29775,13),(56,'5555.png','http://192.168.56.116/shop/sel/image/1553761523904375.png',20415,14),(57,'shoes1.png','http://192.168.56.116/shop/sel/image/1554106208575047.png',20831,15);
/*!40000 ALTER TABLE `sel_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trade_id`
--

DROP TABLE IF EXISTS `trade_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trade_id` (
  `seq` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `oid` varchar(30) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trade_id`
--

LOCK TABLES `trade_id` WRITE;
/*!40000 ALTER TABLE `trade_id` DISABLE KEYS */;
INSERT INTO `trade_id` VALUES (1,'3151351531','2019-07-16 14:41:11'),(19,'charge201907170036041','2019-07-16 15:36:04'),(24,'charge201907170052011','2019-07-16 15:52:01'),(27,'charge201907170101081','2019-07-16 16:01:08'),(28,'charge201907171451421','2019-07-17 05:51:42'),(35,'charge201907171503591','2019-07-17 06:03:59'),(36,'charge201907171541541','2019-07-17 06:41:54'),(37,'charge201907171732181','2019-07-17 08:32:18'),(38,'charge201907180944241','2019-07-18 00:44:24'),(39,'charge201907180951581','2019-07-18 00:51:58'),(41,'withdraw201907181644001','2019-07-18 07:44:00'),(42,'withdraw201907181708371','2019-07-18 08:08:37'),(43,'withdraw201907181712261','2019-07-18 08:12:26'),(44,'withdraw201907181717301','2019-07-18 08:17:30'),(45,'charge201907191755541','2019-07-19 08:55:54'),(47,'withdraw201907261317561','2019-07-26 04:17:56');
/*!40000 ALTER TABLE `trade_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trade_perfect_list`
--

DROP TABLE IF EXISTS `trade_perfect_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trade_perfect_list` (
  `trade_id` varchar(20) NOT NULL COMMENT '거래번호',
  `trade_date` char(8) DEFAULT NULL COMMENT '거래일',
  `trade_type` char(4) DEFAULT NULL COMMENT '팝니다삽니다구분',
  `seller_id` varchar(20) DEFAULT NULL COMMENT '판매자 아이디',
  `seller_name` varchar(20) DEFAULT NULL COMMENT '판매자 성명',
  `seller_contact` varchar(256) DEFAULT NULL COMMENT '판매자 연락처',
  `buyer_id` varchar(20) DEFAULT NULL COMMENT '구매자 아이디',
  `buyer_name` varchar(20) DEFAULT NULL COMMENT '구매자 성명',
  `buyer_contact` varchar(256) DEFAULT NULL COMMENT '구매자 연락처',
  `trade_money` int(11) DEFAULT NULL COMMENT '거래금액',
  `trade_quantity` bigint(11) unsigned DEFAULT NULL COMMENT '거래수량',
  `trade_reg_date` datetime DEFAULT NULL COMMENT '거래등록일',
  `payment_mileage` int(11) unsigned DEFAULT NULL COMMENT '마일리지결제금액',
  `trade_complete_time` datetime DEFAULT NULL COMMENT '거래 완료시간',
  PRIMARY KEY (`trade_id`),
  KEY `trade_type` (`trade_type`),
  KEY `seller_id` (`seller_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `trade_money` (`trade_money`),
  KEY `trade_quantity` (`trade_quantity`),
  KEY `trade_complete_time` (`trade_complete_time`),
  KEY `trade_date` (`trade_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='구매 완료 내역';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trade_perfect_list`
--

LOCK TABLES `trade_perfect_list` WRITE;
/*!40000 ALTER TABLE `trade_perfect_list` DISABLE KEYS */;
INSERT INTO `trade_perfect_list` VALUES ('buy201907261045012','20190726','sell','admin','jinsol','01050963716','admin2','admin2','0001112222',5000,1,'2019-04-01 17:10:08',5000,'2019-07-26 10:45:01'),('buy201907261447434','20190726','trad','admin','jinsol','01050963716','user2','user2','24214124124',15554,2,'2019-03-28 17:24:32',15554,'2019-07-26 14:47:43');
/*!40000 ALTER TABLE `trade_perfect_list` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdraw`
--

LOCK TABLES `withdraw` WRITE;
/*!40000 ALTER TABLE `withdraw` DISABLE KEYS */;
INSERT INTO `withdraw` VALUES (4,1,3900,'5000',1,'2019-07-18 07:44:00'),(5,1,3900,'5000',1,'2019-07-18 08:08:37'),(6,1,10900,'3520836915413',1,'2019-07-18 08:12:26'),(7,1,6900,'56416946949',0,'2019-07-18 08:17:30'),(9,1,70900,'515151',1,'2019-07-26 04:17:56');
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

-- Dump completed on 2019-07-26 17:36:51
