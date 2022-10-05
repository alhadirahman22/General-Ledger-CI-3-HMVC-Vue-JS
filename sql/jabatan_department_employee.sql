/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-05 09:07:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for jabatan_department_employee
-- ----------------------------
DROP TABLE IF EXISTS `jabatan_department_employee`;
CREATE TABLE `jabatan_department_employee` (
  `jabatan_department_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` bigint(20) NOT NULL,
  `jabatan_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `company_id` bigint(20) NOT NULL DEFAULT '1',
  PRIMARY KEY (`jabatan_department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of jabatan_department_employee
-- ----------------------------
INSERT INTO `jabatan_department_employee` VALUES ('1', '1', '1', '2', '1');
INSERT INTO `jabatan_department_employee` VALUES ('2', '1', '2', '3', '1');
INSERT INTO `jabatan_department_employee` VALUES ('4', '4', '7', '5', '1');
INSERT INTO `jabatan_department_employee` VALUES ('5', '2', '3', '4', '1');
INSERT INTO `jabatan_department_employee` VALUES ('6', '3', '5', '4', '1');
