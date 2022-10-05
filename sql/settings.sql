/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-05 09:07:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `key` varchar(100) NOT NULL,
  `value` text,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : APP, 2 : Company',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('app_company_name', 'PT Sanak Maju Bersama', '1');
INSERT INTO `settings` VALUES ('app_name', 'Sanaksys', '1');
INSERT INTO `settings` VALUES ('app_version', '1.0', '1');
INSERT INTO `settings` VALUES ('cdn_url', 'https://', '1');
INSERT INTO `settings` VALUES ('company_branch_office_address', 'JLN Putaran Sawah Sianik RT 001 RW 001 Nan Balimo Tanjung  Harapan Kota Solok Sumatera Barat', '2');
INSERT INTO `settings` VALUES ('company_branch_office_phone', '', '2');
INSERT INTO `settings` VALUES ('company_brief_information', '<p><br></p>', '2');
INSERT INTO `settings` VALUES ('company_employee_size', '', '2');
INSERT INTO `settings` VALUES ('company_head_office_address', '', '2');
INSERT INTO `settings` VALUES ('company_head_office_phone', '', '2');
INSERT INTO `settings` VALUES ('company_name', '', '2');
INSERT INTO `settings` VALUES ('company_npwp', '', '2');
INSERT INTO `settings` VALUES ('company_registrant_name', '', '2');
INSERT INTO `settings` VALUES ('currency', 'Rp', '1');
INSERT INTO `settings` VALUES ('date_format', 'd M Y', '1');
INSERT INTO `settings` VALUES ('env_debug', 'development', '1');
INSERT INTO `settings` VALUES ('jwt_key', 'ce9b1275760bb87f0cc24d1bf408b62d', '1');
INSERT INTO `settings` VALUES ('key_auth', '#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@', '1');
INSERT INTO `settings` VALUES ('language', 'indonesia', '1');
INSERT INTO `settings` VALUES ('logo_url', 'http://sanaksysv1.test//assets/images/63382ffc6c8c9.png', '1');
INSERT INTO `settings` VALUES ('log_active', '0', '1');
INSERT INTO `settings` VALUES ('manager_id', '5', '1');
INSERT INTO `settings` VALUES ('max_license_number', '100', '2');
INSERT INTO `settings` VALUES ('number_of_decimal', '0', '1');
INSERT INTO `settings` VALUES ('separator_decimal', ',', '1');
INSERT INTO `settings` VALUES ('separator_thousand', '.', '1');
INSERT INTO `settings` VALUES ('shipper_base_url', 'https://merchant-api-sandbox.shipper.id/', '1');
INSERT INTO `settings` VALUES ('shipper_key', 'neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm', '1');
INSERT INTO `settings` VALUES ('time_in_tahunan', '08:45:00', '1');
INSERT INTO `settings` VALUES ('time_out_tahunan', '17:30:00', '1');
INSERT INTO `settings` VALUES ('twilio_number', '+18646592048', '1');
INSERT INTO `settings` VALUES ('twilio_sid', 'AC0da48c7aee2ff0c3ac908537bf26f970', '1');
INSERT INTO `settings` VALUES ('twilio_token', '38d5801abf4de365329bf0e8a4d2ae2b', '1');
