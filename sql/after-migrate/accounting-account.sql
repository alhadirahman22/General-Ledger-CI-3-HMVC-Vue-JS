/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : sanaksys

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-11-13 16:41:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for fin_coa
-- ----------------------------
DROP TABLE IF EXISTS `fin_coa`;
CREATE TABLE `fin_coa` (
  `fin_coa_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fin_coa_group_id` bigint(20) NOT NULL,
  `fin_coa_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'code',
  `fin_coa_code_inc` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `fin_coa_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('D','C') COLLATE utf8_unicode_ci NOT NULL COMMENT 'D = Debit, C= Credit',
  `status` enum('A','T') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A = Aktif, T= Tidak Aktif',
  `desc` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`fin_coa_id`),
  UNIQUE KEY `fin_coa_fin_coa_code_unique` (`fin_coa_code`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of fin_coa
-- ----------------------------
INSERT INTO `fin_coa` VALUES ('1', '1', 'A10101', '01', 'Kas Bank Mandiri (1110020273435)', 'D', 'A', 'Rek Bank Mandiri KCP Solok 1110020273435', '2022-11-12 19:29:24', null, '1', null);
INSERT INTO `fin_coa` VALUES ('2', '1', 'A10102', '02', 'Kas Bank Nagari (Tommy)', 'D', 'A', '', '2022-11-12 19:30:18', null, '1', null);
INSERT INTO `fin_coa` VALUES ('3', '5', 'M0101', '01', 'Modal Investor Agung Pratama Osmond', 'C', 'A', '', '2022-11-12 22:04:36', '2022-11-12 22:20:02', '1', '1');
INSERT INTO `fin_coa` VALUES ('4', '5', 'M0102', '02', 'Modal PT ADG Group', 'C', 'A', '', '2022-11-12 22:05:13', null, '1', null);
INSERT INTO `fin_coa` VALUES ('5', '11', 'K30101', '01', 'Hutang Operasional', 'C', 'A', '', '2022-11-12 22:10:35', '2022-11-12 22:18:27', '1', '1');
INSERT INTO `fin_coa` VALUES ('6', '11', 'K30102', '02', 'Hutang Perjalanan Dinas', 'C', 'A', '', '2022-11-12 22:19:45', null, '1', null);
INSERT INTO `fin_coa` VALUES ('7', '11', 'K30103', '03', 'Hutang Jasa Vendor Cable', 'C', 'A', '', '2022-11-12 22:20:48', null, '1', null);
INSERT INTO `fin_coa` VALUES ('8', '8', 'A30101', '01', 'Sewa Mobil Bayar di Muka', 'D', 'A', '', '2022-11-12 22:21:37', '2022-11-13 00:12:26', '1', '1');
INSERT INTO `fin_coa` VALUES ('9', '8', 'A30102', '02', 'IT Operasional Bayar di Muka', 'D', 'A', '', '2022-11-12 22:23:42', '2022-11-13 10:46:08', '1', '1');
INSERT INTO `fin_coa` VALUES ('10', '11', 'K30104', '04', 'Hutang Entertainment', 'C', 'A', '', '2022-11-12 22:24:20', null, '1', null);
INSERT INTO `fin_coa` VALUES ('11', '11', 'K30105', '05', 'Hutang Sewa Mobil', 'C', 'A', '', '2022-11-12 22:24:38', null, '1', null);
INSERT INTO `fin_coa` VALUES ('12', '2', 'K10101', '01', 'Hutang Gaji', 'C', 'A', '', '2022-11-12 22:25:30', null, '1', null);
INSERT INTO `fin_coa` VALUES ('13', '2', 'K10102', '02', 'Hutang Beban Bank', 'C', 'A', '', '2022-11-12 22:25:55', null, '1', null);

-- ----------------------------
-- Table structure for fin_coa_aktiva_passiva
-- ----------------------------
DROP TABLE IF EXISTS `fin_coa_aktiva_passiva`;
CREATE TABLE `fin_coa_aktiva_passiva` (
  `fin_coa_aktiva_passiva_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`fin_coa_aktiva_passiva_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of fin_coa_aktiva_passiva
-- ----------------------------
INSERT INTO `fin_coa_aktiva_passiva` VALUES ('1', 'Aktiva');
INSERT INTO `fin_coa_aktiva_passiva` VALUES ('2', 'Passiva');
INSERT INTO `fin_coa_aktiva_passiva` VALUES ('3', 'Non Aktiva Passiva');

-- ----------------------------
-- Table structure for fin_coa_aktiva_passiva_sub
-- ----------------------------
DROP TABLE IF EXISTS `fin_coa_aktiva_passiva_sub`;
CREATE TABLE `fin_coa_aktiva_passiva_sub` (
  `fin_coa_aktiva_passiva_sub_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fin_coa_aktiva_passiva_id` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`fin_coa_aktiva_passiva_sub_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of fin_coa_aktiva_passiva_sub
-- ----------------------------
INSERT INTO `fin_coa_aktiva_passiva_sub` VALUES ('1', '1', 'Aktiva Lancar');
INSERT INTO `fin_coa_aktiva_passiva_sub` VALUES ('2', '1', 'Aktiva Tetap');
INSERT INTO `fin_coa_aktiva_passiva_sub` VALUES ('3', '2', 'Hutang Lancar');
INSERT INTO `fin_coa_aktiva_passiva_sub` VALUES ('4', '2', 'Hutang Jangka Panjang');
INSERT INTO `fin_coa_aktiva_passiva_sub` VALUES ('5', '2', 'Modal');
INSERT INTO `fin_coa_aktiva_passiva_sub` VALUES ('6', '3', 'Non Aktiva Passiva');

-- ----------------------------
-- Table structure for fin_coa_group
-- ----------------------------
DROP TABLE IF EXISTS `fin_coa_group`;
CREATE TABLE `fin_coa_group` (
  `fin_coa_group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fin_coa_aktiva_passiva_sub_id` bigint(20) DEFAULT NULL,
  `fin_coa_group_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'code',
  `fin_coa_group_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fin_coa_group_prefix` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `fin_coa_group_inc` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`fin_coa_group_id`),
  UNIQUE KEY `fin_coa_group_fin_coa_group_code_unique` (`fin_coa_group_code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of fin_coa_group
-- ----------------------------
INSERT INTO `fin_coa_group` VALUES ('1', '1', 'A101', 'Kas', 'A1', '01', '', '2022-11-10 23:53:10', '2022-11-10 23:54:28', '1', '1');
INSERT INTO `fin_coa_group` VALUES ('2', '3', 'K101', 'Hutang Biaya', 'K1', '01', '', '2022-11-11 00:02:19', '2022-11-11 00:15:24', '1', '1');
INSERT INTO `fin_coa_group` VALUES ('3', '3', 'K201', 'Hutang Pajak', 'K2', '01', '', '2022-11-11 00:03:28', null, '1', null);
INSERT INTO `fin_coa_group` VALUES ('5', '5', 'M01', 'Modal Perusahaan', 'M', '01', '', '2022-11-11 00:06:49', null, '1', null);
INSERT INTO `fin_coa_group` VALUES ('6', '5', 'M02', 'Saldo Laba (Rugi)', 'M', '02', '', '2022-11-11 00:07:05', '2022-11-11 00:15:38', '1', '1');
INSERT INTO `fin_coa_group` VALUES ('7', '1', 'A201', 'Panjar Pembelian', 'A2', '01', '', '2022-11-11 00:16:55', null, '1', null);
INSERT INTO `fin_coa_group` VALUES ('8', '1', 'A301', 'Biaya Bayar di Muka (DP)', 'A3', '01', '', '2022-11-11 00:17:30', null, '1', null);
INSERT INTO `fin_coa_group` VALUES ('9', '1', 'A401', 'Piutang Perusahaan', 'A4', '01', '', '2022-11-11 00:19:16', null, '1', null);
INSERT INTO `fin_coa_group` VALUES ('10', '1', 'A402', 'Pinjaman Karyawan', 'A4', '02', '', '2022-11-11 00:20:04', null, '1', null);
INSERT INTO `fin_coa_group` VALUES ('11', '3', 'K301', 'Hutang Perusahaan', 'K3', '01', '', '2022-11-11 00:23:10', null, '1', null);
INSERT INTO `fin_coa_group` VALUES ('12', '3', 'K401', 'Pendapatan diterima dimuka', 'K4', '01', '', '2022-11-11 00:25:31', null, '1', null);

-- ----------------------------
-- Table structure for fin_jurnal_voucher
-- ----------------------------
DROP TABLE IF EXISTS `fin_jurnal_voucher`;
CREATE TABLE `fin_jurnal_voucher` (
  `fin_jurnal_voucher_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fin_jurnal_voucher_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'code',
  `fin_jurnal_voucher_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fin_jurnal_voucher_prefix` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `fin_jurnal_voucher_inc` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`fin_jurnal_voucher_id`),
  UNIQUE KEY `fin_jurnal_voucher_fin_jurnal_voucher_code_unique` (`fin_jurnal_voucher_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of fin_jurnal_voucher
-- ----------------------------
INSERT INTO `fin_jurnal_voucher` VALUES ('1', '30101', 'Penerimaan Bank', '301', '01', '', '2022-11-12 22:58:41', null, '1', null);
INSERT INTO `fin_jurnal_voucher` VALUES ('2', '30201', 'Pengeluaran Bank', '302', '01', '', '2022-11-12 22:58:53', null, '1', null);
