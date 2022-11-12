/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-05 09:07:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for jabatan
-- ----------------------------
DROP TABLE IF EXISTS `jabatan`;
CREATE TABLE `jabatan` (
  `jabatan_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level_jabatan_id` bigint(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint(20) NOT NULL DEFAULT '1',
  `company_id` bigint(20) NOT NULL DEFAULT '1',
  PRIMARY KEY (`jabatan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of jabatan
-- ----------------------------
INSERT INTO `jabatan` VALUES ('1', 'Direktur Utama', '1', '1', null, '2022-10-05 08:18:08', null, null, '1', '1');
INSERT INTO `jabatan` VALUES ('2', 'Komisaris', '2', '1', null, '2022-10-05 08:22:54', null, null, '1', '1');
INSERT INTO `jabatan` VALUES ('3', 'Manager', '5', '1', null, '2022-10-05 08:23:14', null, null, '2', '1');
INSERT INTO `jabatan` VALUES ('4', 'Junior Staff', '8', '1', null, '2022-10-05 08:23:48', null, null, '2', '1');
INSERT INTO `jabatan` VALUES ('5', 'Manager', '5', '1', null, '2022-10-05 08:24:50', null, null, '3', '1');
INSERT INTO `jabatan` VALUES ('6', 'Junior Staff', '8', '1', null, '2022-10-05 08:25:17', null, null, '3', '1');
INSERT INTO `jabatan` VALUES ('7', 'Manager', '5', '1', null, '2022-10-05 08:25:37', null, null, '4', '1');
INSERT INTO `jabatan` VALUES ('8', 'Junior Staff', '8', '1', null, '2022-10-05 08:26:02', null, null, '4', '1');
