/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-01 19:36:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for aauth_department_ro_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_department_ro_group`;
CREATE TABLE `aauth_department_ro_group` (
  `department_id` bigint(20) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aauth_department_ro_group
-- ----------------------------

-- ----------------------------
-- Table structure for aauth_department_to_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_department_to_group`;
CREATE TABLE `aauth_department_to_group` (
  `museum_id` bigint(20) DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of aauth_department_to_group
-- ----------------------------

-- ----------------------------
-- Table structure for aauth_groups
-- ----------------------------
DROP TABLE IF EXISTS `aauth_groups`;
CREATE TABLE `aauth_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `definition` text,
  `dashboard` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_groups
-- ----------------------------
INSERT INTO `aauth_groups` VALUES ('1', 'superadmin', 'Super Admin', null, null, null, null, null);

-- ----------------------------
-- Table structure for aauth_group_to_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_group_to_group`;
CREATE TABLE `aauth_group_to_group` (
  `group_id` int(10) unsigned NOT NULL,
  `subgroup_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`subgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_group_to_group
-- ----------------------------

-- ----------------------------
-- Table structure for aauth_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `aauth_login_attempts`;
CREATE TABLE `aauth_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(39) CHARACTER SET latin1 DEFAULT '0',
  `timestamp` datetime DEFAULT NULL,
  `login_attempts` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_login_attempts
-- ----------------------------

-- ----------------------------
-- Table structure for aauth_perms
-- ----------------------------
DROP TABLE IF EXISTS `aauth_perms`;
CREATE TABLE `aauth_perms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `definition` text,
  `help_uri` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_perms
-- ----------------------------

-- ----------------------------
-- Table structure for aauth_perm_to_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_perm_to_group`;
CREATE TABLE `aauth_perm_to_group` (
  `perm_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`perm_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_perm_to_group
-- ----------------------------
INSERT INTO `aauth_perm_to_group` VALUES ('1', '1');

-- ----------------------------
-- Table structure for aauth_perm_to_user
-- ----------------------------
DROP TABLE IF EXISTS `aauth_perm_to_user`;
CREATE TABLE `aauth_perm_to_user` (
  `perm_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`perm_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_perm_to_user
-- ----------------------------
INSERT INTO `aauth_perm_to_user` VALUES ('1', '1');

-- ----------------------------
-- Table structure for aauth_users
-- ----------------------------
DROP TABLE IF EXISTS `aauth_users`;
CREATE TABLE `aauth_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `pass` text NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `banned` tinyint(1) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `forgot_exp` text,
  `remember_time` datetime DEFAULT NULL,
  `remember_exp` text,
  `verification_code` text,
  `totp_secret` varchar(16) DEFAULT NULL,
  `ip_address` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_users
-- ----------------------------
INSERT INTO `aauth_users` VALUES ('1', 'alhadirahman22@gmail.com', '$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki', 'admin', null, '0', '2022-10-01 19:26:39', '2022-10-01 19:31:23', null, null, null, null, null, null, '127.0.0.1', null, null, '2022-06-20 11:52:02', '1');

-- ----------------------------
-- Table structure for aauth_user_to_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_user_to_group`;
CREATE TABLE `aauth_user_to_group` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_user_to_group
-- ----------------------------
INSERT INTO `aauth_user_to_group` VALUES ('1', '1');

-- ----------------------------
-- Table structure for aauth_user_variables
-- ----------------------------
DROP TABLE IF EXISTS `aauth_user_variables`;
CREATE TABLE `aauth_user_variables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `data_key` varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `data_key` (`data_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_user_variables
-- ----------------------------

-- ----------------------------
-- Table structure for change_password
-- ----------------------------
DROP TABLE IF EXISTS `change_password`;
CREATE TABLE `change_password` (
  `change_password_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) NOT NULL,
  `nik` varchar(200) DEFAULT NULL,
  `email` text,
  `token` text,
  `due_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`change_password_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of change_password
-- ----------------------------
INSERT INTO `change_password` VALUES ('1', '57', '21006002', 'nndg.ace3@gmail.com', '150cc51144b0a0899a83f4ee5cabe771', '2022-04-22', '1', '2022-04-22 09:51:42', null);
INSERT INTO `change_password` VALUES ('2', '57', '21006002', 'nndg.ace3@gmail.com', '64207907c70778c19fd511748c7bc467', '2022-04-23', '0', '2022-04-22 14:24:01', null);
INSERT INTO `change_password` VALUES ('3', '57', '21006002', 'nndg.ace3@gmail.com', 'c610f05b06398c40a1715ae77f55e85e', '2022-04-23', '1', '2022-04-22 14:30:20', null);
INSERT INTO `change_password` VALUES ('4', '57', '21006002', 'nndg.ace3@gmail.com', '8b978c18b60973631500287d373b8f56', '2022-04-26', '1', '2022-04-25 09:51:43', null);
INSERT INTO `change_password` VALUES ('5', '57', '21006002', 'nndg.ace3@gmail.com', '71397a32dc8486078310a70075e06499', '2022-04-27', '1', '2022-04-26 09:37:57', null);
INSERT INTO `change_password` VALUES ('6', '57', '21006002', 'nndg.ace3@gmail.com', 'ce1524dc2add335809aca23c6d015a19', '2022-04-27', '0', '2022-04-26 10:57:08', null);
INSERT INTO `change_password` VALUES ('7', '57', '21006002', 'nndg.ace3@gmail.com', '8272bfaed3a798148d453f0fe48a792a', '2022-04-27', '0', '2022-04-26 11:26:46', null);

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `department_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `museum_id` bigint(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `descriptions` text,
  `active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of departments
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employees
-- ----------------------------

-- ----------------------------
-- Table structure for jabatan
-- ----------------------------
DROP TABLE IF EXISTS `jabatan`;
CREATE TABLE `jabatan` (
  `jabatan_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint(20) NOT NULL DEFAULT '1',
  `museum_id` bigint(20) NOT NULL DEFAULT '1',
  PRIMARY KEY (`jabatan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of jabatan
-- ----------------------------

-- ----------------------------
-- Table structure for jabatan_department_employee
-- ----------------------------
DROP TABLE IF EXISTS `jabatan_department_employee`;
CREATE TABLE `jabatan_department_employee` (
  `jabatan_department_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` bigint(20) NOT NULL,
  `jabatan_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `museum_id` bigint(20) NOT NULL DEFAULT '1',
  PRIMARY KEY (`jabatan_department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of jabatan_department_employee
-- ----------------------------

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_aauth_users` int(11) unsigned NOT NULL,
  `uri` text,
  `request` text,
  `header` text,
  `accessOn` datetime DEFAULT NULL,
  `ip1` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of log
-- ----------------------------

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('phqg9oev6qohthb23p13vnsvom1ihsco', '127.0.0.1', '1657963585', '__ci_last_regenerate|i:1657962696;getsSettings|a:33:{s:16:\"app_company_name\";s:5:\"Rimau\";s:8:\"app_name\";s:6:\"Julain\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:0:\"\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:0:\"\";s:25:\"company_head_office_phone\";s:0:\"\";s:12:\"company_name\";s:0:\"\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:11:\"development\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:52:\"http://museum.test//assets/images/62d186ce47100.jpeg\";s:10:\"log_active\";s:1:\"1\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"3\";username|s:6:\"alhadi\";email|s:16:\"alhadi@gmail.com\";loggedin|b:1;level_user|s:11:\"user-mutasi\";user|O:8:\"stdClass\":22:{s:2:\"id\";s:1:\"3\";s:5:\"email\";s:16:\"alhadi@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$clLnxktWWPAjKn/xN5jt1eeVzDEt6xl/qO6mz08owa17jOWj3hOgS\";s:8:\"username\";s:6:\"alhadi\";s:11:\"employee_id\";s:1:\"2\";s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-07-16 16:11:43\";s:13:\"last_activity\";s:19:\"2022-07-16 16:11:43\";s:12:\"date_created\";s:19:\"2022-07-16 14:17:26\";s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";s:19:\"2022-07-16 14:17:26\";s:10:\"created_by\";s:1:\"1\";s:10:\"updated_at\";s:19:\"2022-07-16 16:11:19\";s:10:\"updated_by\";s:1:\"3\";s:13:\"data_employee\";O:8:\"stdClass\":11:{s:11:\"employee_id\";s:1:\"2\";s:3:\"nip\";s:3:\"898\";s:4:\"name\";s:6:\"Alhadi\";s:5:\"email\";s:16:\"alhadi@gmail.com\";s:5:\"no_hp\";s:3:\"024\";s:6:\"gender\";s:4:\"male\";s:6:\"active\";s:1:\"1\";s:10:\"created_at\";s:19:\"2022-07-03 14:00:29\";s:10:\"created_by\";s:1:\"1\";s:10:\"updated_at\";s:19:\"2022-07-16 14:20:33\";s:10:\"updated_by\";s:1:\"3\";}s:7:\"museums\";a:2:{i:0;O:8:\"stdClass\":4:{s:9:\"museum_id\";s:1:\"1\";s:13:\"department_id\";s:1:\"1\";s:8:\"group_id\";s:2:\"11\";s:11:\"museum_name\";s:8:\"Museum 1\";}i:1;O:8:\"stdClass\":4:{s:9:\"museum_id\";s:1:\"2\";s:13:\"department_id\";s:1:\"4\";s:8:\"group_id\";s:2:\"11\";s:11:\"museum_name\";s:8:\"Museum 2\";}}s:11:\"departments\";a:4:{i:0;O:8:\"stdClass\":4:{s:9:\"museum_id\";s:1:\"1\";s:13:\"department_id\";s:1:\"1\";s:8:\"group_id\";s:2:\"11\";s:15:\"department_name\";s:7:\"Finance\";}i:1;O:8:\"stdClass\":4:{s:9:\"museum_id\";s:1:\"1\";s:13:\"department_id\";s:1:\"2\";s:8:\"group_id\";s:2:\"11\";s:15:\"department_name\";s:2:\"IT\";}i:2;O:8:\"stdClass\":4:{s:9:\"museum_id\";s:1:\"1\";s:13:\"department_id\";s:1:\"3\";s:8:\"group_id\";s:2:\"11\";s:15:\"department_name\";s:6:\"Gudang\";}i:3;O:8:\"stdClass\":4:{s:9:\"museum_id\";s:1:\"2\";s:13:\"department_id\";s:1:\"4\";s:8:\"group_id\";s:2:\"11\";s:15:\"department_name\";s:2:\"HR\";}}}table_filter_mutasi_benda_model|a:3:{i:1;s:0:\"\";i:4;s:0:\"\";i:6;s:0:\"\";}table_filter_jenis_mutasi_model|a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}table_filter_jenis_mutasi_model_2|a:3:{s:5:\"start\";s:1:\"0\";s:6:\"length\";s:2:\"10\";s:4:\"page\";i:0;}table_filter_management_persetujuan_model|a:1:{i:1;s:0:\"\";}');
INSERT INTO `sessions` VALUES ('bunrnie8nkp5dmlll48hek8cb2pdaj7u', '127.0.0.1', '1657962983', '__ci_last_regenerate|i:1657962899;getsSettings|a:33:{s:16:\"app_company_name\";s:5:\"Rimau\";s:8:\"app_name\";s:6:\"Julain\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:0:\"\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:0:\"\";s:25:\"company_head_office_phone\";s:0:\"\";s:12:\"company_name\";s:0:\"\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:11:\"development\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:52:\"http://museum.test//assets/images/62d186ce47100.jpeg\";s:10:\"log_active\";s:1:\"1\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"1\";username|s:5:\"admin\";email|s:17:\"nndg.xa@gmail.com\";loggedin|b:1;level_user|s:11:\"Super Admin\";user|O:8:\"stdClass\":22:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:17:\"nndg.xa@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki\";s:8:\"username\";s:5:\"admin\";s:11:\"employee_id\";N;s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-07-16 16:15:02\";s:13:\"last_activity\";s:19:\"2022-07-16 16:15:02\";s:12:\"date_created\";N;s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";N;s:10:\"created_by\";N;s:10:\"updated_at\";s:19:\"2022-06-20 11:52:02\";s:10:\"updated_by\";s:1:\"1\";s:13:\"data_employee\";N;s:7:\"museums\";a:0:{}s:11:\"departments\";a:0:{}}table_filter_setting_user|a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";}table_filter_setting_role|a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}table_filter_setting_permission|a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}');
INSERT INTO `sessions` VALUES ('6p7kk8han4a6hmtcfi95gogbbvuckdu2', '127.0.0.1', '1658557538', '__ci_last_regenerate|i:1658549162;getsSettings|a:33:{s:16:\"app_company_name\";s:28:\"Museum Nasional @1778 - 2022\";s:8:\"app_name\";s:9:\"MuseumApp\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:293:\"<p><span xss=removed>Museum Nasional Republik Indonesia atau Museum Gajah, adalah sebuah museum arkeologi, sejarah, etnografi, dan geografi yang terletak di Jakarta Pusat dan persisnya di Jalan Merdeka Barat 12. Museum ini merupakan museum pertama dan terbesar di Asia Tenggara.</span><br></p>\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:112:\"Jl. Medan Merdeka Barat No.12, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110\";s:25:\"company_head_office_phone\";s:13:\"(021) 3868172\";s:12:\"company_name\";s:15:\"Museum Nasional\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:10:\"production\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:59:\"http://museum.qinerja.com//assets/images/62cb843de1bbc.jpeg\";s:10:\"log_active\";s:1:\"0\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"1\";username|s:5:\"admin\";email|s:17:\"nndg.xa@gmail.com\";loggedin|b:1;level_user|s:11:\"Super Admin\";user|O:8:\"stdClass\":22:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:17:\"nndg.xa@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki\";s:8:\"username\";s:5:\"admin\";s:11:\"employee_id\";N;s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-07-23 11:06:07\";s:13:\"last_activity\";s:19:\"2022-07-23 11:06:07\";s:12:\"date_created\";N;s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";N;s:10:\"created_by\";N;s:10:\"updated_at\";s:19:\"2022-06-20 11:52:02\";s:10:\"updated_by\";s:1:\"1\";s:13:\"data_employee\";N;s:7:\"museums\";a:0:{}s:11:\"departments\";a:0:{}}table_filter_setting_audit_trails|a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:4;s:0:\"\";i:5;s:0:\"\";}table_filter_mutasi_benda_model|a:3:{i:1;s:0:\"\";i:4;s:0:\"\";i:6;s:0:\"\";}table_filter_api_method_model|a:1:{i:0;s:0:\"\";}table_filter_employees_model|a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}table_filter_jenis_mutasi_model|a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}table_filter_jenis_mutasi_model_2|a:3:{s:5:\"start\";s:1:\"0\";s:6:\"length\";s:2:\"10\";s:4:\"page\";i:0;}table_filter_museum_model|a:2:{i:0;s:0:\"\";i:1;s:0:\"\";}');
INSERT INTO `sessions` VALUES ('3urjrbrlp7n3kng9bpuic091eb390uee', '127.0.0.1', '1659759480', '__ci_last_regenerate|i:1659754156;getsSettings|a:33:{s:16:\"app_company_name\";s:28:\"Museum Nasional @1778 - 2022\";s:8:\"app_name\";s:9:\"MuseumApp\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:293:\"<p><span xss=removed>Museum Nasional Republik Indonesia atau Museum Gajah, adalah sebuah museum arkeologi, sejarah, etnografi, dan geografi yang terletak di Jakarta Pusat dan persisnya di Jalan Merdeka Barat 12. Museum ini merupakan museum pertama dan terbesar di Asia Tenggara.</span><br></p>\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:112:\"Jl. Medan Merdeka Barat No.12, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110\";s:25:\"company_head_office_phone\";s:13:\"(021) 3868172\";s:12:\"company_name\";s:15:\"Museum Nasional\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:10:\"production\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:59:\"http://museum.qinerja.com//assets/images/62cb843de1bbc.jpeg\";s:10:\"log_active\";s:1:\"0\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"1\";username|s:5:\"admin\";email|s:17:\"nndg.xa@gmail.com\";loggedin|b:1;level_user|s:11:\"Super Admin\";user|O:8:\"stdClass\":22:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:17:\"nndg.xa@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki\";s:8:\"username\";s:5:\"admin\";s:11:\"employee_id\";N;s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-08-06 09:53:55\";s:13:\"last_activity\";s:19:\"2022-08-06 09:53:55\";s:12:\"date_created\";N;s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";N;s:10:\"created_by\";N;s:10:\"updated_at\";s:19:\"2022-06-20 11:52:02\";s:10:\"updated_by\";s:1:\"1\";s:13:\"data_employee\";N;s:7:\"museums\";a:0:{}s:11:\"departments\";a:0:{}}');
INSERT INTO `sessions` VALUES ('13kum4tfo361jqo2a9u4mn08n1ipjhkf', '127.0.0.1', '1659770221', '__ci_last_regenerate|i:1659762018;getsSettings|a:33:{s:16:\"app_company_name\";s:28:\"Museum Nasional @1778 - 2022\";s:8:\"app_name\";s:9:\"MuseumApp\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:293:\"<p><span xss=removed>Museum Nasional Republik Indonesia atau Museum Gajah, adalah sebuah museum arkeologi, sejarah, etnografi, dan geografi yang terletak di Jakarta Pusat dan persisnya di Jalan Merdeka Barat 12. Museum ini merupakan museum pertama dan terbesar di Asia Tenggara.</span><br></p>\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:112:\"Jl. Medan Merdeka Barat No.12, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110\";s:25:\"company_head_office_phone\";s:13:\"(021) 3868172\";s:12:\"company_name\";s:15:\"Museum Nasional\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:11:\"development\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:59:\"http://museum.qinerja.com//assets/images/62cb843de1bbc.jpeg\";s:10:\"log_active\";s:1:\"0\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"1\";username|s:5:\"admin\";email|s:17:\"nndg.xa@gmail.com\";loggedin|b:1;level_user|s:11:\"Super Admin\";user|O:8:\"stdClass\":22:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:17:\"nndg.xa@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki\";s:8:\"username\";s:5:\"admin\";s:11:\"employee_id\";N;s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-08-06 12:00:22\";s:13:\"last_activity\";s:19:\"2022-08-06 12:00:22\";s:12:\"date_created\";N;s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";N;s:10:\"created_by\";N;s:10:\"updated_at\";s:19:\"2022-06-20 11:52:02\";s:10:\"updated_by\";s:1:\"1\";s:13:\"data_employee\";N;s:7:\"museums\";a:0:{}s:11:\"departments\";a:0:{}}');
INSERT INTO `sessions` VALUES ('upptfmej3qem62as16uippk24phkd91m', '127.0.0.1', '1659775675', '__ci_last_regenerate|i:1659770229;getsSettings|a:33:{s:16:\"app_company_name\";s:28:\"Museum Nasional @1778 - 2022\";s:8:\"app_name\";s:9:\"MuseumApp\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:293:\"<p><span xss=removed>Museum Nasional Republik Indonesia atau Museum Gajah, adalah sebuah museum arkeologi, sejarah, etnografi, dan geografi yang terletak di Jakarta Pusat dan persisnya di Jalan Merdeka Barat 12. Museum ini merupakan museum pertama dan terbesar di Asia Tenggara.</span><br></p>\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:112:\"Jl. Medan Merdeka Barat No.12, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110\";s:25:\"company_head_office_phone\";s:13:\"(021) 3868172\";s:12:\"company_name\";s:15:\"Museum Nasional\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:11:\"development\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:59:\"http://museum.qinerja.com//assets/images/62cb843de1bbc.jpeg\";s:10:\"log_active\";s:1:\"0\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"1\";username|s:5:\"admin\";email|s:17:\"nndg.xa@gmail.com\";loggedin|b:1;level_user|s:11:\"Super Admin\";user|O:8:\"stdClass\":22:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:17:\"nndg.xa@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki\";s:8:\"username\";s:5:\"admin\";s:11:\"employee_id\";N;s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-08-06 14:17:14\";s:13:\"last_activity\";s:19:\"2022-08-06 14:17:14\";s:12:\"date_created\";N;s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";N;s:10:\"created_by\";N;s:10:\"updated_at\";s:19:\"2022-06-20 11:52:02\";s:10:\"updated_by\";s:1:\"1\";s:13:\"data_employee\";N;s:7:\"museums\";a:0:{}s:11:\"departments\";a:0:{}}table_filter_setting_audit_trails|a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:4;s:0:\"\";i:5;s:0:\"\";}table_filter_setting_user|a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";}table_filter_setting_role|a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}');
INSERT INTO `sessions` VALUES ('u2mjp3h2u8s9m5gohud85g9131ef41r4', '127.0.0.1', '1659943917', '__ci_last_regenerate|i:1659942111;getsSettings|a:33:{s:16:\"app_company_name\";s:28:\"Museum Nasional @1778 - 2022\";s:8:\"app_name\";s:9:\"MuseumApp\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:293:\"<p><span xss=removed>Museum Nasional Republik Indonesia atau Museum Gajah, adalah sebuah museum arkeologi, sejarah, etnografi, dan geografi yang terletak di Jakarta Pusat dan persisnya di Jalan Merdeka Barat 12. Museum ini merupakan museum pertama dan terbesar di Asia Tenggara.</span><br></p>\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:112:\"Jl. Medan Merdeka Barat No.12, Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110\";s:25:\"company_head_office_phone\";s:13:\"(021) 3868172\";s:12:\"company_name\";s:15:\"Museum Nasional\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:11:\"development\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:59:\"http://museum.qinerja.com//assets/images/62cb843de1bbc.jpeg\";s:10:\"log_active\";s:1:\"0\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"1\";username|s:5:\"admin\";email|s:17:\"nndg.xa@gmail.com\";loggedin|b:1;level_user|s:11:\"Super Admin\";user|O:8:\"stdClass\":22:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:17:\"nndg.xa@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki\";s:8:\"username\";s:5:\"admin\";s:11:\"employee_id\";N;s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-08-08 14:01:57\";s:13:\"last_activity\";s:19:\"2022-08-08 14:01:57\";s:12:\"date_created\";N;s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";N;s:10:\"created_by\";N;s:10:\"updated_at\";s:19:\"2022-06-20 11:52:02\";s:10:\"updated_by\";s:1:\"1\";s:13:\"data_employee\";N;s:7:\"museums\";a:0:{}s:11:\"departments\";a:0:{}}');
INSERT INTO `sessions` VALUES ('ftbqt3jnsu248kkm4p53ig2taafd65g9', '127.0.0.1', '1664627483', '__ci_last_regenerate|i:1664627197;getsSettings|a:33:{s:16:\"app_company_name\";s:21:\"PT Sanak Maju Bersama\";s:8:\"app_name\";s:8:\"Sanaksys\";s:11:\"app_version\";s:3:\"1.0\";s:7:\"cdn_url\";s:8:\"https://\";s:29:\"company_branch_office_address\";s:0:\"\";s:27:\"company_branch_office_phone\";s:0:\"\";s:25:\"company_brief_information\";s:11:\"<p><br></p>\";s:21:\"company_employee_size\";s:0:\"\";s:27:\"company_head_office_address\";s:0:\"\";s:25:\"company_head_office_phone\";s:0:\"\";s:12:\"company_name\";s:0:\"\";s:12:\"company_npwp\";s:0:\"\";s:23:\"company_registrant_name\";s:0:\"\";s:8:\"currency\";s:2:\"Rp\";s:11:\"date_format\";s:5:\"d M Y\";s:9:\"env_debug\";s:11:\"development\";s:7:\"jwt_key\";s:32:\"ce9b1275760bb87f0cc24d1bf408b62d\";s:8:\"key_auth\";s:44:\"#@b51acd3fbf642cf3659e5b895db00a1d4c3ed1c7!@\";s:8:\"language\";s:9:\"indonesia\";s:8:\"logo_url\";s:55:\"http://sanaksysv1.test//assets/images/63382ffc6c8c9.png\";s:10:\"log_active\";s:1:\"0\";s:10:\"manager_id\";s:1:\"5\";s:18:\"max_license_number\";s:3:\"100\";s:17:\"number_of_decimal\";s:1:\"0\";s:17:\"separator_decimal\";s:1:\",\";s:18:\"separator_thousand\";s:1:\".\";s:16:\"shipper_base_url\";s:40:\"https://merchant-api-sandbox.shipper.id/\";s:11:\"shipper_key\";s:64:\"neyQovDp2qCdIPwBfnDEotGxQ7wYDg4yjKLidlp1rkT0CpqOIrtxTVmc3IvAhfVm\";s:15:\"time_in_tahunan\";s:8:\"08:45:00\";s:16:\"time_out_tahunan\";s:8:\"17:30:00\";s:13:\"twilio_number\";s:12:\"+18646592048\";s:10:\"twilio_sid\";s:34:\"AC0da48c7aee2ff0c3ac908537bf26f970\";s:12:\"twilio_token\";s:32:\"38d5801abf4de365329bf0e8a4d2ae2b\";}id|s:1:\"1\";username|s:5:\"admin\";email|s:24:\"alhadirahman22@gmail.com\";loggedin|b:1;level_user|s:11:\"Super Admin\";user|O:8:\"stdClass\":20:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:24:\"alhadirahman22@gmail.com\";s:4:\"pass\";s:60:\"$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki\";s:8:\"username\";s:5:\"admin\";s:11:\"employee_id\";N;s:6:\"banned\";s:1:\"0\";s:10:\"last_login\";s:19:\"2022-10-01 19:26:39\";s:13:\"last_activity\";s:19:\"2022-10-01 19:26:39\";s:12:\"date_created\";N;s:10:\"forgot_exp\";N;s:13:\"remember_time\";N;s:12:\"remember_exp\";N;s:17:\"verification_code\";N;s:11:\"totp_secret\";N;s:10:\"ip_address\";s:9:\"127.0.0.1\";s:10:\"created_at\";N;s:10:\"created_by\";N;s:10:\"updated_at\";s:19:\"2022-06-20 11:52:02\";s:10:\"updated_by\";s:1:\"1\";s:13:\"data_employee\";N;}table_filter_api_method_model|a:1:{i:0;s:0:\"\";}table_filter_departments_model|a:2:{i:1;s:0:\"\";i:2;s:0:\"\";}table_filter_setting_permission|a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}table_filter_setting_role|a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}table_filter_setting_user|a:4:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";}');

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
INSERT INTO `settings` VALUES ('company_branch_office_address', '', '2');
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
