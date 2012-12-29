-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.16-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema timex
--

CREATE DATABASE IF NOT EXISTS timex;
USE timex;

--
-- Definition of table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `departmentCode` char(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `state` varchar(2) NOT NULL,
  PRIMARY KEY (`departmentCode`),
  UNIQUE KEY `DepartmentCodeIndex` (`departmentCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` (`departmentCode`,`name`,`state`) VALUES 
 ('AC','Accounting','FL'),
 ('CS','Customer Support','GA'),
 ('HR','Human Resources','FL'),
 ('IT','Information Technology','FL');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;


--
-- Definition of table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(45) NOT NULL,
  `employeeType` char(1) NOT NULL,
  `password` varchar(40) NOT NULL,
  `managerId` int(10) NOT NULL,
  `employeeId` varchar(11) NOT NULL,
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(2) NOT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `payrate` double unsigned NOT NULL,
  `taxrate` double unsigned NOT NULL,
  `registrationDate` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` (`id`,`name`,`email`,`employeeType`,`password`,`managerId`,`employeeId`,`address`,`city`,`state`,`zipcode`,`payrate`,`taxrate`,`registrationDate`) VALUES 
 (1,'Mike Dover','cjacob@nova.edu','H','8cb2237d0679ca88db6464eac60da96345513964',3,'123-45-6789','123 Main St','Davie','FL','33314',35.68,20,'2006-07-23 15:00:00'),
 (2,'Ajay Kumar','sbutcher@nova.edu','H','8cb2237d0679ca88db6464eac60da96345513964',3,'123-67-1234','234 Main St','Davie','FL','33314',35.68,18,'2006-07-28 19:00:00'),
 (3,'Teresa Walker','cw769@nova.edu','M','8cb2237d0679ca88db6464eac60da96345513964',4,'123-89-4321','567 Main St','Davie','FL','33314',120000,16,'2007-01-02 16:00:00'),
 (4,'Tom Brady','ealvaro@nova.edu','E','8cb2237d0679ca88db6464eac60da96345513964',4,'123-09-3456','989 Main St','Davie','FL','33314',275000,21,'2007-04-01 08:00:00'),
 (5,'Alvaro E. Escobar','alvaroescobar@live.com','A','8cb2237d0679ca88db6464eac60da96345513964',4,'123-12-7654','999 Main St','Davie','FL','33314',90000,23,'2010-10-27 08:00:00'),
 (9,'John Smith','jsmith@acme.com','H','8cb2237d0679ca88db6464eac60da96345513964',3,'209-56-4854','','','FL','',0,0,'2012-10-27 12:31:42'),
 (10,'Joe Smith','jsmith@acme.com','H','8cb2237d0679ca88db6464eac60da96345513964',3,'209-56-4854','','','FL','',0,0,'2012-10-29 19:36:04'),
 (11,'Jane Smith','jsmith@acme.com','M','8cb2237d0679ca88db6464eac60da96345513964',4,'209-45-8356','','','GA','',0,0,'2012-10-29 20:15:28');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;


--
-- Definition of table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regularRate` double NOT NULL,
  `overtimeRate` double NOT NULL,
  `taxPercent` double NOT NULL,
  `netPay` double NOT NULL,
  `timesheetId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_payment_1` (`timesheetId`),
  CONSTRAINT `FK_payment_1` FOREIGN KEY (`timesheetId`) REFERENCES `timesheet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` (`id`,`regularRate`,`overtimeRate`,`taxPercent`,`netPay`,`timesheetId`) VALUES 
 (1,35.68,53.52,20,1084.67,1),
 (2,35.68,53.52,18,1143.32,2),
 (3,57.69,57.69,16,1938.46,6),
 (4,132.21,132.21,21,4177.88,10),
 (5,43.27,43.27,23,1332.69,19),
 (6,35.68,53.52,18,1385.45,3);
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;


--
-- Definition of table `timesheet`
--

DROP TABLE IF EXISTS `timesheet`;
CREATE TABLE `timesheet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employeeId` int(10) NOT NULL,
  `statuscode` char(1) NOT NULL,
  `periodEndingDate` date NOT NULL,
  `departmentCode` char(4) NOT NULL,
  `minutesMon` int(10) unsigned DEFAULT NULL,
  `minutesTue` int(10) unsigned DEFAULT NULL,
  `minutesWed` int(10) unsigned DEFAULT NULL,
  `minutesThu` int(10) unsigned DEFAULT NULL,
  `minutesFri` int(10) unsigned DEFAULT NULL,
  `minutesSat` int(10) unsigned DEFAULT NULL,
  `minutesSun` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_timesheet_1` (`employeeId`),
  KEY `FK_timesheet_2` (`departmentCode`),
  CONSTRAINT `FK_timesheet_1` FOREIGN KEY (`employeeId`) REFERENCES `employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_timesheet_2` FOREIGN KEY (`departmentCode`) REFERENCES `department` (`departmentCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `timesheet`
--

/*!40000 ALTER TABLE `timesheet` DISABLE KEYS */;
INSERT INTO `timesheet` (`id`,`employeeId`,`statuscode`,`periodEndingDate`,`departmentCode`,`minutesMon`,`minutesTue`,`minutesWed`,`minutesThu`,`minutesFri`,`minutesSat`,`minutesSun`) VALUES 
 (1,2,'C','2006-08-19','IT',480,480,360,480,480,0,0),
 (2,1,'C','2006-08-19','HR',480,480,480,480,480,0,0),
 (3,1,'C','2010-01-31','CS',480,480,480,600,600,0,0),
 (4,1,'A','2010-02-21','AC',480,480,480,240,240,0,0),
 (5,1,'A','2010-02-28','IT',420,360,480,360,360,420,0),
 (6,3,'C','2010-03-21','CS',480,480,480,480,480,0,0),
 (7,1,'A','2010-03-21','IT',480,480,480,240,420,120,0),
 (8,1,'A','2010-03-28','IT',480,480,480,480,480,0,0),
 (9,2,'A','2010-03-28','AC',480,480,48,480,480,0,0),
 (10,4,'C','2010-05-02','CS',480,480,480,480,480,0,0),
 (11,3,'A','2010-05-02','CS',300,300,300,300,300,0,0),
 (18,1,'D','2012-02-26','IT',360,360,240,360,0,0,0),
 (19,5,'C','2012-03-25','IT',480,480,480,480,480,240,0),
 (20,1,'D','2012-04-08','IT',240,240,540,540,0,0,0),
 (37,3,'P','2012-04-08','HR',420,480,480,420,0,0,0),
 (38,2,'A','2012-04-08','CS',420,360,300,240,0,0,0),
 (39,1,'P','2012-12-09','AC',480,480,480,480,480,240,240),
 (40,1,'P','2012-12-23','HR',420,480,480,480,0,0,0);
/*!40000 ALTER TABLE `timesheet` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
