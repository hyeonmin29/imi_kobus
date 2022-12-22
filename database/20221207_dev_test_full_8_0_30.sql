-- MySQL dump 10.13  Distrib 8.0.30, for Linux (x86_64)
--
-- Host: localhost    Database: dev_test
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_book`
--

DROP TABLE IF EXISTS `account_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_book` (
  `trade_code` varchar(20) NOT NULL COMMENT '거래번호',
  `user_num` int unsigned NOT NULL COMMENT '유저번호',
  `user_id` varchar(20) NOT NULL COMMENT '유저아이디',
  `user_name` varchar(20) NOT NULL COMMENT '유저이름',
  `account_code` char(5) NOT NULL COMMENT '장부코드\n',
  `trade_type` char(1) NOT NULL COMMENT '예매 = y, 예매취소 = n, , 충전 = c, 출금 = w',
  `summary_text` varchar(100) DEFAULT NULL,
  `payment_type` char(1) NOT NULL COMMENT '결제 방법 m=마일리지, p=모바일, c=카드',
  `before_mile` int NOT NULL DEFAULT '0' COMMENT '변동 전 마일리지',
  `change_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '변동 마일리지',
  `after_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '변동 후 마일리지',
  `commission` int unsigned NOT NULL DEFAULT '0' COMMENT '수수료',
  `reg_day` datetime NOT NULL COMMENT '등록일',
  PRIMARY KEY (`trade_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='매출 장부';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_book`
--

LOCK TABLES `account_book` WRITE;
/*!40000 ALTER TABLE `account_book` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_code_info`
--

DROP TABLE IF EXISTS `account_code_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_code_info` (
  `type1` char(1) NOT NULL COMMENT 'step1 - c=충전, w=출금, r=예매, e=예매예약\n\n',
  `type2` char(1) NOT NULL COMMENT 'a=계좌, m=마일리지, p=핸드폰',
  `type3` char(2) NOT NULL COMMENT 'step3 00=일반, 01=농협, 02=우리은행 03=카카오뱅크, 05=이벤트',
  `type4` char(1) NOT NULL COMMENT 'a=완료 c=취소',
  `type1_comment` varchar(30) NOT NULL COMMENT 'type1 코멘트',
  `type2_comment` varchar(30) NOT NULL COMMENT 'type2 코멘트',
  `type3_comment` varchar(30) NOT NULL COMMENT 'type3 코멘트',
  `type4_comment` varchar(30) NOT NULL COMMENT 'tpye4 코멘트',
  PRIMARY KEY (`type1`,`type2`,`type3`,`type4`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='장부 코드';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_code_info`
--

LOCK TABLES `account_code_info` WRITE;
/*!40000 ALTER TABLE `account_code_info` DISABLE KEYS */;
INSERT INTO `account_code_info` VALUES ('c','a','01','t','충전','계좌','농협','완료'),('c','a','02','t','충전','계좌','우리은행','완료'),('c','a','03','t','충전','계좌','카카오뱅크','완료'),('c','m','00','t','충전','마일리지','일반','완료'),('c','m','05','t','충전','마일리지','이벤트','완료'),('c','p','00','t','충전','핸드폰','일반','완료'),('e','a','01','f','예약','계좌','농협','취소'),('e','a','01','t','예약','계좌','농협','완료'),('e','a','02','f','예약','계좌','우리은행','취소'),('e','a','02','t','예약','계좌','우리은행','완료'),('e','a','03','f','예약','계좌','카카오뱅크','취소'),('e','a','03','t','예약','계좌','카카오뱅크','완료'),('e','m','00','f','예약','마일리지','일반','취소'),('e','m','00','t','예약','마일리지','일반','완료'),('e','m','05','f','예약','마일리지','이벤트','취소'),('e','m','05','t','예약','마일리지','이벤트','완료'),('r','a','01','f','예매','계좌','농협','취소'),('r','a','01','t','예매','계좌','농협','완료'),('r','a','02','f','예매','계좌','우리은행','취소'),('r','a','02','t','예매','계좌','우리은행','완료'),('r','a','03','f','예매','계좌','카카오뱅크','취소'),('r','a','03','t','예매','계좌','카카오뱅크','완료'),('r','m','00','f','예매','마일리지','일반','취소'),('r','m','00','t','예매','마일리지','일반','완료'),('r','m','05','f','예매','마일리지','이벤트','취소'),('r','m','05','t','예매','마일리지','이벤트','완료'),('w','a','01','t','출금','계좌','농협','완료'),('w','a','02','t','출금','계좌','우리은행','완료'),('w','a','03','t','출금','계좌','카카오뱅크','완료');
/*!40000 ALTER TABLE `account_code_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accum_mile`
--

DROP TABLE IF EXISTS `accum_mile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accum_mile` (
  `user_num` int NOT NULL,
  `account_code` char(5) NOT NULL COMMENT '장부코드',
  `before_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '이전 마일리지',
  `accum_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '적립 마일리지',
  `after_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '적립 후 마일리지',
  `reg_day` datetime NOT NULL COMMENT '첫 등록일',
  `change_day` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '마지막 변동일',
  PRIMARY KEY (`user_num`,`account_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='적립 테이블';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accum_mile`
--

LOCK TABLES `accum_mile` WRITE;
/*!40000 ALTER TABLE `accum_mile` DISABLE KEYS */;
INSERT INTO `accum_mile` VALUES (1,'ca01t',36149953,2892,36152845,'2022-12-07 20:40:09','2022-12-07 21:28:54'),(1,'ca02t',54703,2892,57595,'2022-12-07 20:40:15','2022-12-07 20:42:29'),(2,'ca01t',20028,2818,22846,'2022-12-07 21:37:35','2022-12-07 21:53:33'),(2,'ca02t',3811,2811,6622,'2022-12-07 21:32:19','2022-12-07 21:40:16'),(3,'ca01t',123171,1108,124279,'2022-12-07 21:54:20','2022-12-07 22:00:05'),(4,'ca01t',2811,2811,5622,'2022-12-07 22:00:48','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `accum_mile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accum_mile_log`
--

DROP TABLE IF EXISTS `accum_mile_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accum_mile_log` (
  `seq` int unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `user_num` int unsigned NOT NULL,
  `account_code` char(5) NOT NULL COMMENT '장부코드\n',
  `trade_code` varchar(20) NOT NULL COMMENT '거래번호',
  `before_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '이전 마일리지',
  `accum_mile` int unsigned NOT NULL COMMENT '적립 마일리지',
  `after_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '적립 후 마일리지',
  `reg_day` datetime NOT NULL COMMENT '마일리지 적립일',
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='적립 로그';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accum_mile_log`
--

LOCK TABLES `accum_mile_log` WRITE;
/*!40000 ALTER TABLE `accum_mile_log` DISABLE KEYS */;
INSERT INTO `accum_mile_log` VALUES (1,1,'ca02t','20221207ca02t1',51811,2892,54703,'2022-12-07 20:42:13'),(2,1,'ca02t','20221207ca02t1',54703,2892,57595,'2022-12-07 20:42:29'),(3,1,'ca01t','20221207ca01t1',36073813,2892,36076705,'2022-12-07 21:07:35'),(4,1,'ca01t','20221207ca01t1',36076705,2892,36079597,'2022-12-07 21:08:02'),(5,1,'ca01t','20221207ca01t1',36079597,2892,36082489,'2022-12-07 21:09:16'),(6,1,'ca01t','20221207ca01t1',36082408,2811,36085219,'2022-12-07 21:09:28'),(7,1,'ca01t','20221207ca01t1',36085219,2811,36088030,'2022-12-07 21:09:35'),(8,1,'ca01t','20221207ca01t1',36147061,28110,36175171,'2022-12-07 21:13:08'),(9,1,'ca01t','20221207ca01t1',36149953,2892,36152845,'2022-12-07 21:28:54'),(10,2,'ca02t','20221207ca02t2',1000,1000,2000,'2022-12-07 21:32:19'),(15,2,'ca01t','20221207ca01t2',1000,1000,2000,'2022-12-07 21:38:33'),(16,2,'ca02t','20221207ca02t2',3811,2811,6622,'2022-12-07 21:40:16'),(17,2,'ca01t','20221207ca01t2',2110,1110,3220,'2022-12-07 21:44:18'),(20,2,'ca01t','20221207ca01t2',13208,11098,24306,'2022-12-07 21:48:56'),(24,2,'ca01t','20221207ca01t2',14318,1110,15428,'2022-12-07 21:52:08'),(25,2,'ca01t','20221207ca01t2',17210,2892,20102,'2022-12-07 21:52:11'),(26,2,'ca01t','20221207ca01t2',20028,2818,22846,'2022-12-07 21:53:33'),(27,3,'ca01t','20221207ca01t3',110981,110981,221962,'2022-12-07 21:54:20'),(30,3,'ca01t','20221207ca01t3',122063,11082,133145,'2022-12-07 21:58:56'),(31,3,'ca01t','20221207ca01t3',123171,1108,124279,'2022-12-07 22:00:05'),(32,4,'ca01t','20221207ca01t4',2811,2811,5622,'2022-12-07 22:00:48');
/*!40000 ALTER TABLE `accum_mile_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `isbn` char(13) NOT NULL,
  `author` char(50) DEFAULT NULL,
  `title` char(100) DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bus_info`
--

DROP TABLE IF EXISTS `bus_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bus_info` (
  `bus_num` int unsigned NOT NULL COMMENT '버스번호',
  `account_code` char(5) NOT NULL COMMENT '장부코드',
  `bus_class` char(1) NOT NULL COMMENT '버스등급',
  `seat_cnt` int unsigned NOT NULL COMMENT '좌석 수',
  `used_flag` char(1) NOT NULL DEFAULT 'n' COMMENT '운행 유무(y= yes, n=no)',
  `bus_location` varchar(20) NOT NULL COMMENT '버스 위치',
  `reg_day` datetime NOT NULL COMMENT '등록일',
  `change_day` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '변동일',
  PRIMARY KEY (`bus_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='버스정보';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_info`
--

LOCK TABLES `bus_info` WRITE;
/*!40000 ALTER TABLE `bus_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `bus_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bus_move_info`
--

DROP TABLE IF EXISTS `bus_move_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bus_move_info` (
  `seq` int unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `bus_num` int unsigned NOT NULL COMMENT '버스번호',
  `driver_num` int unsigned NOT NULL COMMENT '기사정보',
  `bus_route` varchar(20) NOT NULL COMMENT '버스경로',
  `bus_class` varchar(20) NOT NULL COMMENT '버스등급',
  `seat_cnt` int unsigned NOT NULL COMMENT '좌석 수',
  `people_cnt` int unsigned NOT NULL COMMENT '승객 수',
  `move_time` time NOT NULL DEFAULT '00:00:00' COMMENT '운행시간',
  `price` int unsigned NOT NULL COMMENT '금액',
  `drive_memo` text COMMENT '운행 중 이슈',
  `bus_leave_location` varchar(20) NOT NULL COMMENT '출발지',
  `bus_arrive_location` varchar(20) NOT NULL COMMENT '도착지',
  `leave_day` datetime NOT NULL COMMENT '출발시간',
  `arrive_time` datetime NOT NULL COMMENT '도착시간',
  PRIMARY KEY (`seq`),
  KEY `fk_busmoveinfo_driverinfo1_idx` (`driver_num`),
  KEY `fk_busmoveinfo_businfo1_idx` (`bus_num`),
  CONSTRAINT `fk_busmoveinfo_businfo1` FOREIGN KEY (`bus_num`) REFERENCES `bus_info` (`bus_num`),
  CONSTRAINT `fk_busmoveinfo_driverinfo1` FOREIGN KEY (`driver_num`) REFERENCES `driver_info` (`driver_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='버스 운행 정보';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_move_info`
--

LOCK TABLES `bus_move_info` WRITE;
/*!40000 ALTER TABLE `bus_move_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `bus_move_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customerID` int unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `address` char(100) NOT NULL,
  `city` char(30) NOT NULL,
  PRIMARY KEY (`customerID`),
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `ordersf` (`customerID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dev_account_book`
--

DROP TABLE IF EXISTS `dev_account_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dev_account_book` (
  `trade_id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '거래번호',
  `seller_id` varchar(10) NOT NULL COMMENT '판매자ID',
  `buyer_id` varchar(10) NOT NULL COMMENT '구매자ID',
  `quantity` int unsigned NOT NULL COMMENT '거래수량',
  `money` int unsigned NOT NULL COMMENT '거래금액',
  `reg_date` datetime NOT NULL COMMENT '거래일',
  PRIMARY KEY (`trade_id`),
  KEY `seller_id` (`seller_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `quantity` (`quantity`),
  KEY `money` (`money`),
  KEY `reg_date` (`reg_date`)
) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dev_account_book`
--

LOCK TABLES `dev_account_book` WRITE;
/*!40000 ALTER TABLE `dev_account_book` DISABLE KEYS */;
/*!40000 ALTER TABLE `dev_account_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dev_user_info`
--

DROP TABLE IF EXISTS `dev_user_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dev_user_info` (
  `user_no` int unsigned NOT NULL AUTO_INCREMENT COMMENT '회원번호',
  `user_id` varchar(10) NOT NULL COMMENT '회원아이디',
  `user_passwd` varchar(50) NOT NULL COMMENT '회원비밀번호',
  `user_birth` char(6) NOT NULL COMMENT '회원생일',
  `user_gender` char(1) NOT NULL COMMENT '회원성별(1:남/2:여)',
  `user_city` char(2) NOT NULL COMMENT '회원 거주지',
  `user_reg_date` datetime NOT NULL COMMENT '가입일',
  PRIMARY KEY (`user_no`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dev_user_info`
--

LOCK TABLES `dev_user_info` WRITE;
/*!40000 ALTER TABLE `dev_user_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `dev_user_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `driver_info`
--

DROP TABLE IF EXISTS `driver_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_info` (
  `driver_num` int unsigned NOT NULL AUTO_INCREMENT COMMENT '기사번호',
  `driver_id` varchar(20) NOT NULL COMMENT '기사 아이디',
  `driver_pw` varchar(20) NOT NULL COMMENT '기사 패스워드',
  `driver_name` varchar(20) NOT NULL COMMENT '기사 이름',
  `driver_phone` int NOT NULL COMMENT '전화번호',
  `driver_email` varchar(50) NOT NULL COMMENT '기사 이메일',
  `driver_location` varchar(45) NOT NULL COMMENT '위치',
  `driver_check` char(1) NOT NULL COMMENT '운행여부 y=yes, n=no',
  `reg_day` datetime NOT NULL COMMENT '등록일',
  PRIMARY KEY (`driver_num`),
  UNIQUE KEY `driver_id_UNIQUE` (`driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='기사 정보';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_info`
--

LOCK TABLES `driver_info` WRITE;
/*!40000 ALTER TABLE `driver_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `driver_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_list`
--

DROP TABLE IF EXISTS `login_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_list` (
  `seq` int unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `user_num` int unsigned NOT NULL COMMENT '유저번호',
  `success_flag` char(1) NOT NULL COMMENT '성공 여부 t=true, f=flase',
  `login_day` datetime NOT NULL COMMENT '로그인 날짜',
  PRIMARY KEY (`seq`),
  KEY `fk_login_list_user_info1_idx` (`user_num`),
  CONSTRAINT `fk_login_list_user_info1` FOREIGN KEY (`user_num`) REFERENCES `user_info` (`user_num`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='로그인 리스트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_list`
--

LOCK TABLES `login_list` WRITE;
/*!40000 ALTER TABLE `login_list` DISABLE KEYS */;
INSERT INTO `login_list` VALUES (1,2,'t','2022-12-07 21:32:14'),(2,3,'t','2022-12-07 21:54:15'),(3,4,'t','2022-12-07 22:00:43'),(4,5,'t','2022-12-07 22:02:41');
/*!40000 ALTER TABLE `login_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mile_charge_list`
--

DROP TABLE IF EXISTS `mile_charge_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mile_charge_list` (
  `seq` int unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `user_num` int unsigned NOT NULL,
  `account_code` char(5) NOT NULL COMMENT '장부코드',
  `trade_code` varchar(20) NOT NULL COMMENT '거래번호',
  `charge_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '충전금액',
  `charge_route` varchar(6) NOT NULL COMMENT '충전 경로 (A=계좌이체 , M=모바일 등)',
  `charge_day` datetime NOT NULL COMMENT '충전일',
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='마일리지 충전 리스트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mile_charge_list`
--

LOCK TABLES `mile_charge_list` WRITE;
/*!40000 ALTER TABLE `mile_charge_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `mile_charge_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mile_withdraw_list`
--

DROP TABLE IF EXISTS `mile_withdraw_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mile_withdraw_list` (
  `seq` int unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `user_num` int unsigned NOT NULL,
  `account_code` char(5) NOT NULL COMMENT '장부코드',
  `trade_code` varchar(20) NOT NULL COMMENT '거래번호',
  `withdraw_mile` int unsigned NOT NULL COMMENT '출금 마일리지',
  `withdraw_route` varchar(20) NOT NULL COMMENT '출금 경로(계좌)',
  `withdraw_day` datetime NOT NULL COMMENT '출금일',
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='마일리지 출금 리스트';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mile_withdraw_list`
--

LOCK TABLES `mile_withdraw_list` WRITE;
/*!40000 ALTER TABLE `mile_withdraw_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `mile_withdraw_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `orderID` int unsigned NOT NULL,
  `isbn` char(13) NOT NULL,
  `quantity` tinyint unsigned DEFAULT NULL,
  PRIMARY KEY (`orderID`,`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `orderID` int unsigned NOT NULL AUTO_INCREMENT,
  `customerID` int unsigned NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`orderID`),
  KEY `customerID` (`customerID`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `ordersf` (`customerID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordersf`
--

DROP TABLE IF EXISTS `ordersf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordersf` (
  `customerID` int unsigned NOT NULL,
  `orderID` int unsigned NOT NULL,
  `ordernumber` int NOT NULL,
  PRIMARY KEY (`customerID`),
  KEY `orderID` (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordersf`
--

LOCK TABLES `ordersf` WRITE;
/*!40000 ALTER TABLE `ordersf` DISABLE KEYS */;
/*!40000 ALTER TABLE `ordersf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservation` (
  `seq` int unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `trade_code` varchar(20) NOT NULL COMMENT '예매번호',
  `user_num` int unsigned NOT NULL COMMENT '유저번호\n',
  `bus_move_info_seq` int unsigned NOT NULL COMMENT '운행 정보 시퀀스',
  `status` char(1) NOT NULL COMMENT '예매상태 y=성공, n=취소, e=예약, f=예약취소',
  `account_code` char(5) NOT NULL COMMENT '장부코드',
  `bus_num` int unsigned NOT NULL COMMENT '버스번호',
  `bus_class` char(1) NOT NULL COMMENT '버스등급',
  `price` int unsigned NOT NULL COMMENT '금액',
  `seat_num` int unsigned NOT NULL COMMENT '좌석 번호',
  `route` varchar(20) NOT NULL COMMENT '예매경로',
  `trade_type` varchar(20) NOT NULL COMMENT '예매 = y, 예매취소 = n, , 충전 = c, 출금 = w',
  `start_day` datetime NOT NULL COMMENT '출발일시',
  `buy_day` datetime NOT NULL COMMENT '구매일시',
  `change_day` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '예매변경일시',
  PRIMARY KEY (`seq`),
  KEY `fk_reservation_userinfo1_idx` (`user_num`),
  KEY `fk_reservation_bus_move_info1_idx` (`bus_move_info_seq`),
  KEY `fk_reservation_bus_info1_idx` (`bus_num`),
  CONSTRAINT `fk_reservation_bus_info1` FOREIGN KEY (`bus_num`) REFERENCES `bus_info` (`bus_num`),
  CONSTRAINT `fk_reservation_bus_move_info1` FOREIGN KEY (`bus_move_info_seq`) REFERENCES `bus_move_info` (`seq`),
  CONSTRAINT `fk_reservation_userinfo1` FOREIGN KEY (`user_num`) REFERENCES `user_info` (`user_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='예매정보';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `thistory`
--

DROP TABLE IF EXISTS `thistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thistory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `1_id` int NOT NULL,
  `2_id` int NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `thistory`
--

LOCK TABLES `thistory` WRITE;
/*!40000 ALTER TABLE `thistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `thistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_account`
--

DROP TABLE IF EXISTS `user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_account` (
  `number` varchar(8) NOT NULL,
  `user_name` varchar(20) DEFAULT NULL,
  `balance` int DEFAULT NULL,
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_account`
--

LOCK TABLES `user_account` WRITE;
/*!40000 ALTER TABLE `user_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_bank`
--

DROP TABLE IF EXISTS `user_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_bank` (
  `number` varchar(8) NOT NULL,
  `order_no` int NOT NULL,
  `date` date DEFAULT NULL,
  `bank_gb` varchar(1) DEFAULT NULL,
  `bankmoney` int DEFAULT NULL,
  `bank_info` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`number`,`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_bank`
--

LOCK TABLES `user_bank` WRITE;
/*!40000 ALTER TABLE `user_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_info`
--

DROP TABLE IF EXISTS `user_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_info` (
  `user_num` int unsigned NOT NULL AUTO_INCREMENT COMMENT '유저번호\n',
  `user_name` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL COMMENT '유저 아이디',
  `user_pw` varchar(20) NOT NULL COMMENT '유저 비밀번호',
  `user_phone` int NOT NULL COMMENT '전화번호',
  `user_email` varchar(50) NOT NULL COMMENT '이메일',
  `user_birth` date NOT NULL COMMENT '생일',
  `user_account` varchar(20) NOT NULL,
  `user_account_num` int NOT NULL,
  `last_login_day` datetime NOT NULL COMMENT '마지막 로그인 날짜',
  `reg_day` datetime NOT NULL COMMENT '가입 날짜',
  `status` char(1) DEFAULT 'n' COMMENT '회원 탈퇴 체크  y=탈퇴',
  PRIMARY KEY (`user_num`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='회원정보\n';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_info`
--

LOCK TABLES `user_info` WRITE;
/*!40000 ALTER TABLE `user_info` DISABLE KEYS */;
INSERT INTO `user_info` VALUES (1,'이현민','item1','123',111,'item1@item.com','1999-09-09','농협',4586413,'0000-00-00 00:00:00','2022-12-07 14:56:07','n'),(2,'김현우','item2','123',1111,'item2@item.com','1998-02-09','농협',4586416,'2022-12-07 21:32:14','2022-12-07 21:31:50','n'),(3,'관리이','item3','123',111,'item3@item.com','1998-08-02','농협',458641,'2022-12-07 21:54:15','2022-12-07 21:54:09','n'),(4,'관리이','item4','123',11,'item4@item.com','1985-04-19','농협',4586,'2022-12-07 22:00:43','2022-12-07 22:00:36','n'),(5,'이이이','item5','123',15153,'item5@item.com','2000-02-09','농협',156163,'2022-12-07 22:02:41','2022-12-07 22:02:33','n');
/*!40000 ALTER TABLE `user_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_mile_change_list`
--

DROP TABLE IF EXISTS `user_mile_change_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_mile_change_list` (
  `seq` int unsigned NOT NULL AUTO_INCREMENT COMMENT '시퀀스',
  `user_num` int unsigned NOT NULL,
  `account_code` char(5) NOT NULL COMMENT '장부코드',
  `trade_code` varchar(20) NOT NULL COMMENT '결제번호',
  `before_mile` int NOT NULL DEFAULT '0' COMMENT '이전 보유 마일리지',
  `change_mile` int NOT NULL DEFAULT '0' COMMENT '변동금액',
  `after_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '현재 보유 마일리지',
  `change_text` varchar(50) DEFAULT '' COMMENT '변동이유',
  `change_day` datetime NOT NULL COMMENT '변동날짜',
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='마일리지 변동내역';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_mile_change_list`
--

LOCK TABLES `user_mile_change_list` WRITE;
/*!40000 ALTER TABLE `user_mile_change_list` DISABLE KEYS */;
INSERT INTO `user_mile_change_list` VALUES (2,2,'ca01t','20221207ca01t2',18129,1110,19239,'','2022-12-07 21:52:08'),(3,2,'ca01t','20221207ca01t2',21021,2892,23913,'','2022-12-07 21:52:11'),(4,2,'ca01t','20221207ca01t2',23839,2818,26657,'','2022-12-07 21:53:33'),(5,3,'ca01t','20221207ca01t3',110981,110981,221962,'','2022-12-07 21:54:20'),(6,3,'ca01t','20221207ca01t3',122063,11082,133145,'','2022-12-07 21:58:56'),(7,3,'ca01t','20221207ca01t3',123171,1108,124279,'','2022-12-07 22:00:05'),(8,4,'ca01t','20221207ca01t4',2811,2811,5622,'','2022-12-07 22:00:48');
/*!40000 ALTER TABLE `user_mile_change_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_mileage`
--

DROP TABLE IF EXISTS `user_mileage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_mileage` (
  `user_num` int NOT NULL,
  `account_code` char(5) NOT NULL,
  `own_mile` int unsigned NOT NULL DEFAULT '0' COMMENT '보유중인 마일리지',
  `change_day` datetime NOT NULL COMMENT '마지막 변동일',
  PRIMARY KEY (`user_num`,`account_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='사용자 마일리지';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_mileage`
--

LOCK TABLES `user_mileage` WRITE;
/*!40000 ALTER TABLE `user_mileage` DISABLE KEYS */;
INSERT INTO `user_mileage` VALUES (1,'ca01a',2891850,'2022-12-07 17:35:28'),(1,'ca01t',36149953,'2022-12-07 21:28:54'),(1,'ca02t',54703,'2022-12-07 20:42:29'),(2,'ca01t',20028,'2022-12-07 21:53:33'),(2,'ca02t',3811,'2022-12-07 21:40:16'),(3,'ca01t',123171,'2022-12-07 22:00:05'),(4,'ca01t',2811,'2022-12-07 22:00:48');
/*!40000 ALTER TABLE `user_mileage` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-07 22:10:28
