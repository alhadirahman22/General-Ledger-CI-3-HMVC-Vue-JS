/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-05 09:04:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `department_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `descriptions` text,
  `active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES ('1', '1', 'Direksi', 'Department untuk para direksi atau jajaran management perusahaan', '1', '2022-10-05 07:24:35', '1', '2022-10-05 07:25:09', '1');
INSERT INTO `departments` VALUES ('2', '1', 'Finance', '', '1', '2022-10-05 07:25:44', '1', null, null);
INSERT INTO `departments` VALUES ('3', '1', 'Administrasi', '', '1', '2022-10-05 07:31:01', '1', '2022-10-05 07:33:21', '1');
INSERT INTO `departments` VALUES ('4', '1', 'Operasional', '', '1', '2022-10-05 07:34:03', '1', null, null);
