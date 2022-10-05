/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-10-05 07:20:11
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_users
-- ----------------------------
INSERT INTO `aauth_users` VALUES ('1', 'alhadirahman22@gmail.com', '$2y$10$jkdi1E4AEIMx2PqdvN7QAOB4snIOybhGkNy0qfbjvNVLIh/V0tvki', 'admin', null, '0', '2022-10-05 07:14:05', '2022-10-05 07:19:45', null, null, null, null, null, null, '127.0.0.1', null, null, '2022-06-20 11:52:02', '1');

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
-- Table structure for bank
-- ----------------------------
DROP TABLE IF EXISTS `bank`;
CREATE TABLE `bank` (
  `bank_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('1','2') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Dalam Negri , 2 = Luar Negri',
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of bank
-- ----------------------------
INSERT INTO `bank` VALUES ('1', 'BANK BRI', '002', '1');
INSERT INTO `bank` VALUES ('2', 'BANK EKSPOR INDONESIA', '003', '1');
INSERT INTO `bank` VALUES ('3', 'BANK MANDIRI', '008', '1');
INSERT INTO `bank` VALUES ('4', 'BANK BNI', '009', '1');
INSERT INTO `bank` VALUES ('5', 'BANK DANAMON', '011', '1');
INSERT INTO `bank` VALUES ('6', 'PERMATA BANK', '013', '1');
INSERT INTO `bank` VALUES ('7', 'BANK BCA', '014', '1');
INSERT INTO `bank` VALUES ('8', 'BANK BII', '016', '1');
INSERT INTO `bank` VALUES ('9', 'BANK PANIN', '019', '1');
INSERT INTO `bank` VALUES ('10', 'BANK ARTA NIAGA KENCANA', '020', '1');
INSERT INTO `bank` VALUES ('11', 'BANK NIAGA', '022', '1');
INSERT INTO `bank` VALUES ('12', 'BANK BUANA IND', '023', '1');
INSERT INTO `bank` VALUES ('13', 'BANK LIPPO', '026', '1');
INSERT INTO `bank` VALUES ('14', 'BANK NISP', '028', '1');
INSERT INTO `bank` VALUES ('15', 'AMERICAN EXPRESS BANK LTD', '030', '1');
INSERT INTO `bank` VALUES ('16', 'CITIBANK N.A.', '031', '1');
INSERT INTO `bank` VALUES ('17', 'JP. MORGAN CHASE BANK, N.A.', '032', '1');
INSERT INTO `bank` VALUES ('18', 'BANK OF AMERICA, N.A', '033', '1');
INSERT INTO `bank` VALUES ('19', 'ING INDONESIA BANK', '034', '1');
INSERT INTO `bank` VALUES ('20', 'BANK MULTICOR TBK.', '036', '1');
INSERT INTO `bank` VALUES ('21', 'BANK ARTHA GRAHA', '037', '1');
INSERT INTO `bank` VALUES ('22', 'BANK CREDIT AGRICOLE INDOSUEZ', '039', '1');
INSERT INTO `bank` VALUES ('23', 'THE BANGKOK BANK COMP. LTD', '040', '1');
INSERT INTO `bank` VALUES ('24', 'THE HONGKONG & SHANGHAI B.C.', '041', '1');
INSERT INTO `bank` VALUES ('25', 'THE BANK OF TOKYO MITSUBISHI UFJ LTD', '042', '1');
INSERT INTO `bank` VALUES ('26', 'BANK SUMITOMO MITSUI INDONESIA', '045', '1');
INSERT INTO `bank` VALUES ('27', 'BANK DBS INDONESIA', '046', '1');
INSERT INTO `bank` VALUES ('28', 'BANK RESONA PERDANIA', '047', '1');
INSERT INTO `bank` VALUES ('29', 'BANK MIZUHO INDONESIA', '048', '1');
INSERT INTO `bank` VALUES ('30', 'STANDARD CHARTERED BANK', '050', '1');
INSERT INTO `bank` VALUES ('31', 'BANK ABN AMRO', '052', '1');
INSERT INTO `bank` VALUES ('32', 'BANK KEPPEL TATLEE BUANA', '053', '1');
INSERT INTO `bank` VALUES ('33', 'BANK CAPITAL INDONESIA, TBK.', '054', '1');
INSERT INTO `bank` VALUES ('34', 'BANK BNP PARIBAS INDONESIA', '057', '1');
INSERT INTO `bank` VALUES ('35', 'BANK UOB INDONESIA', '058', '1');
INSERT INTO `bank` VALUES ('36', 'KOREA EXCHANGE BANK DANAMON', '059', '1');
INSERT INTO `bank` VALUES ('37', 'RABOBANK INTERNASIONAL INDONESIA', '060', '1');
INSERT INTO `bank` VALUES ('38', 'ANZ PANIN BANK', '061', '1');
INSERT INTO `bank` VALUES ('39', 'DEUTSCHE BANK AG.', '067', '1');
INSERT INTO `bank` VALUES ('40', 'BANK WOORI INDONESIA', '068', '1');
INSERT INTO `bank` VALUES ('41', 'BANK OF CHINA LIMITED', '069', '1');
INSERT INTO `bank` VALUES ('42', 'BANK BUMI ARTA', '076', '1');
INSERT INTO `bank` VALUES ('43', 'BANK EKONOMI', '087', '1');
INSERT INTO `bank` VALUES ('44', 'BANK ANTARDAERAH', '088', '1');
INSERT INTO `bank` VALUES ('45', 'BANK HAGA', '089', '1');
INSERT INTO `bank` VALUES ('46', 'BANK IFI', '093', '1');
INSERT INTO `bank` VALUES ('47', 'BANK CENTURY, TBK.', '095', '1');
INSERT INTO `bank` VALUES ('48', 'BANK MAYAPADA', '097', '1');
INSERT INTO `bank` VALUES ('49', 'BANK JABAR', '110', '1');
INSERT INTO `bank` VALUES ('50', 'BANK DKI', '111', '1');
INSERT INTO `bank` VALUES ('51', 'BPD DIY', '112', '1');
INSERT INTO `bank` VALUES ('52', 'BANK JATENG', '113', '1');
INSERT INTO `bank` VALUES ('53', 'BANK JATIM', '114', '1');
INSERT INTO `bank` VALUES ('54', 'BPD JAMBI', '115', '1');
INSERT INTO `bank` VALUES ('55', 'BPD ACEH', '116', '1');
INSERT INTO `bank` VALUES ('56', 'BANK SUMUT', '117', '1');
INSERT INTO `bank` VALUES ('57', 'BANK NAGARI', '118', '1');
INSERT INTO `bank` VALUES ('58', 'BANK RIAU', '119', '1');
INSERT INTO `bank` VALUES ('59', 'BANK SUMSEL', '120', '1');
INSERT INTO `bank` VALUES ('60', 'BANK LAMPUNG', '121', '1');
INSERT INTO `bank` VALUES ('61', 'BPD KALSEL', '122', '1');
INSERT INTO `bank` VALUES ('62', 'BPD KALIMANTAN BARAT', '123', '1');
INSERT INTO `bank` VALUES ('63', 'BPD KALTIM', '124', '1');
INSERT INTO `bank` VALUES ('64', 'BPD KALTENG', '125', '1');
INSERT INTO `bank` VALUES ('65', 'BPD SULSEL', '126', '1');
INSERT INTO `bank` VALUES ('66', 'BANK SULUT', '127', '1');
INSERT INTO `bank` VALUES ('67', 'BPD NTB', '128', '1');
INSERT INTO `bank` VALUES ('68', 'BPD BALI', '129', '1');
INSERT INTO `bank` VALUES ('69', 'BANK NTT', '130', '1');
INSERT INTO `bank` VALUES ('70', 'BANK MALUKU', '131', '1');
INSERT INTO `bank` VALUES ('71', 'BPD PAPUA', '132', '1');
INSERT INTO `bank` VALUES ('72', 'BANK BENGKULU', '133', '1');
INSERT INTO `bank` VALUES ('73', 'BPD SULAWESI TENGAH', '134', '1');
INSERT INTO `bank` VALUES ('74', 'BANK SULTRA', '135', '1');
INSERT INTO `bank` VALUES ('75', 'BANK NUSANTARA PARAHYANGAN', '145', '1');
INSERT INTO `bank` VALUES ('76', 'BANK SWADESI', '146', '1');
INSERT INTO `bank` VALUES ('77', 'BANK MUAMALAT', '147', '1');
INSERT INTO `bank` VALUES ('78', 'BANK MESTIKA', '151', '1');
INSERT INTO `bank` VALUES ('79', 'BANK METRO EXPRESS', '152', '1');
INSERT INTO `bank` VALUES ('80', 'BANK SHINTA INDONESIA', '153', '1');
INSERT INTO `bank` VALUES ('81', 'BANK MASPION', '157', '1');
INSERT INTO `bank` VALUES ('82', 'BANK HAGAKITA', '159', '1');
INSERT INTO `bank` VALUES ('83', 'BANK GANESHA', '161', '1');
INSERT INTO `bank` VALUES ('84', 'BANK WINDU KENTJANA', '162', '1');
INSERT INTO `bank` VALUES ('85', 'HALIM INDONESIA BANK', '164', '1');
INSERT INTO `bank` VALUES ('86', 'BANK HARMONI INTERNATIONAL', '166', '1');
INSERT INTO `bank` VALUES ('87', 'BANK KESAWAN', '167', '1');
INSERT INTO `bank` VALUES ('88', 'BANK TABUNGAN NEGARA (PERSERO)', '200', '1');
INSERT INTO `bank` VALUES ('89', 'BANK HIMPUNAN SAUDARA 1906, TBK .', '212', '1');
INSERT INTO `bank` VALUES ('90', 'BANK TABUNGAN PENSIUNAN NASIONAL', '213', '1');
INSERT INTO `bank` VALUES ('91', 'BANK SWAGUNA', '405', '1');
INSERT INTO `bank` VALUES ('92', 'BANK JASA ARTA', '422', '1');
INSERT INTO `bank` VALUES ('93', 'BANK MEGA', '426', '1');
INSERT INTO `bank` VALUES ('94', 'BANK JASA JAKARTA', '427', '1');
INSERT INTO `bank` VALUES ('95', 'BANK BUKOPIN', '441', '1');
INSERT INTO `bank` VALUES ('96', 'BANK SYARIAH MANDIRI', '451', '1');
INSERT INTO `bank` VALUES ('97', 'BANK BISNIS INTERNASIONAL', '459', '1');
INSERT INTO `bank` VALUES ('98', 'BANK SRI PARTHA', '466', '1');
INSERT INTO `bank` VALUES ('99', 'BANK JASA JAKARTA', '472', '1');
INSERT INTO `bank` VALUES ('100', 'BANK BINTANG MANUNGGAL', '484', '1');
INSERT INTO `bank` VALUES ('101', 'BANK BUMIPUTERA', '485', '1');
INSERT INTO `bank` VALUES ('102', 'BANK YUDHA BHAKTI', '490', '1');
INSERT INTO `bank` VALUES ('103', 'BANK MITRANIAGA', '491', '1');
INSERT INTO `bank` VALUES ('104', 'BANK AGRO NIAGA', '494', '1');
INSERT INTO `bank` VALUES ('105', 'BANK INDOMONEX', '498', '1');
INSERT INTO `bank` VALUES ('106', 'BANK ROYAL INDONESIA', '501', '1');
INSERT INTO `bank` VALUES ('107', 'BANK ALFINDO', '503', '1');
INSERT INTO `bank` VALUES ('108', 'BANK SYARIAH MEGA', '506', '1');
INSERT INTO `bank` VALUES ('109', 'BANK INA PERDANA', '513', '1');
INSERT INTO `bank` VALUES ('110', 'BANK HARFA', '517', '1');
INSERT INTO `bank` VALUES ('111', 'PRIMA MASTER BANK', '520', '1');
INSERT INTO `bank` VALUES ('112', 'BANK PERSYARIKATAN INDONESIA', '521', '1');
INSERT INTO `bank` VALUES ('113', 'BANK AKITA', '525', '1');
INSERT INTO `bank` VALUES ('114', 'LIMAN INTERNATIONAL BANK', '526', '1');
INSERT INTO `bank` VALUES ('115', 'ANGLOMAS INTERNASIONAL BANK', '531', '1');
INSERT INTO `bank` VALUES ('116', 'BANK DIPO INTERNATIONAL', '523', '1');
INSERT INTO `bank` VALUES ('117', 'BANK KESEJAHTERAAN EKONOMI', '535', '1');
INSERT INTO `bank` VALUES ('118', 'BANK UIB', '536', '1');
INSERT INTO `bank` VALUES ('119', 'BANK ARTOS IND', '542', '1');
INSERT INTO `bank` VALUES ('120', 'BANK PURBA DANARTA', '547', '1');
INSERT INTO `bank` VALUES ('121', 'BANK MULTI ARTA SENTOSA', '548', '1');
INSERT INTO `bank` VALUES ('122', 'BANK MAYORA', '553', '1');
INSERT INTO `bank` VALUES ('123', 'BANK INDEX SELINDO', '555', '1');
INSERT INTO `bank` VALUES ('124', 'BANK VICTORIA INTERNATIONAL', '566', '1');
INSERT INTO `bank` VALUES ('125', 'BANK EKSEKUTIF', '558', '1');
INSERT INTO `bank` VALUES ('126', 'CENTRATAMA NASIONAL BANK', '559', '1');
INSERT INTO `bank` VALUES ('127', 'BANK FAMA INTERNASIONAL', '562', '1');
INSERT INTO `bank` VALUES ('128', 'BANK SINAR HARAPAN BALI', '564', '1');
INSERT INTO `bank` VALUES ('129', 'BANK HARDA', '567', '1');
INSERT INTO `bank` VALUES ('130', 'BANK FINCONESIA', '945', '1');
INSERT INTO `bank` VALUES ('131', 'BANK MERINCORP', '946', '1');
INSERT INTO `bank` VALUES ('132', 'BANK MAYBANK INDOCORP', '947', '1');
INSERT INTO `bank` VALUES ('133', 'BANK OCBC – INDONESIA', '948', '1');
INSERT INTO `bank` VALUES ('134', 'BANK CHINA TRUST INDONESIA', '949', '1');
INSERT INTO `bank` VALUES ('135', 'BANK COMMONWEALTH', '950', '1');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of change_password
-- ----------------------------


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
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of migrations
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


