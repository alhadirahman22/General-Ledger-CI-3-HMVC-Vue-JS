/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-05 09:04:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for company_bank
-- ----------------------------
DROP TABLE IF EXISTS `company_bank`;
CREATE TABLE `company_bank` (
  `company_bank_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) NOT NULL,
  `bank_id` bigint(20) NOT NULL,
  `account_number` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `account_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `swift_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`company_bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of company_bank
-- ----------------------------
INSERT INTO `company_bank` VALUES ('1', '1', '3', '1110020273435', 'Bank Mandiri KCP Solok', 'PT Sanak Maju Bersama', null);
