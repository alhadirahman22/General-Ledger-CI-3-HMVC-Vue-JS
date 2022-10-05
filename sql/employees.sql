/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-05 09:05:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for employees
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `employee_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `no_hp` varchar(30) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employees
-- ----------------------------
INSERT INTO `employees` VALUES ('2', '20220001', 'Alhadi Rahman', 'alhadirahman22@gmail.com', '6282170200684', 'male', '1', '2022-10-05 08:40:44', '1', null, null);
INSERT INTO `employees` VALUES ('3', '20220002', 'Ridho Ramadhan Osmon', 'ridho@gmail.com', '62', 'male', '1', '2022-10-05 08:44:14', '1', null, null);
INSERT INTO `employees` VALUES ('4', '20220003', 'Tommy Indra Wisata', 'tommy@gmail.com', '62', 'male', '1', '2022-10-05 08:45:10', '1', '2022-10-05 08:46:26', '1');
INSERT INTO `employees` VALUES ('5', '20220004', 'Arif Tri Yanto', 'arif@gmail.com', '62', 'male', '1', '2022-10-05 08:45:58', '1', null, null);
