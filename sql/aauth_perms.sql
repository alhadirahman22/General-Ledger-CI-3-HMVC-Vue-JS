/*
Navicat MySQL Data Transfer

Source Server         : smclinode
Source Server Version : 100328
Source Host           : 116.251.216.201:3306
Source Database       : museum

Target Server Type    : MYSQL
Target Server Version : 100328
File Encoding         : 65001

Date: 2022-10-15 15:33:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for aauth_perms
-- ----------------------------
DROP TABLE IF EXISTS `aauth_perms`;
CREATE TABLE `aauth_perms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `definition` text DEFAULT NULL,
  `help_uri` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=337 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aauth_perms
-- ----------------------------
INSERT INTO `aauth_perms` VALUES ('1', 'administration/settings', 'Administration - Settings', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('3', 'administration/user_management/permissions', 'Administration - Permissions', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('4', 'administration/user_management/permissions/edit', 'Administration - Permissions -  Edit', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('5', 'administration/user_management/permissions/add', 'Administration - Permissions -  Add', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('6', 'administration/user_management/permissions/delete', 'Administration - Permissions -  Delete', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('7', 'administration/user_management/roles', 'Administration - Roles', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('8', 'administration/user_management/roles/edit', 'Administration - Roles - Edit', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('9', 'administration/user_management/roles/add', 'Administration - Roles - Add', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('10', 'administration/user_management/roles/delete', 'Administration - Roles - Delete', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('11', 'administration/user_management/users', 'Administration - Users', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('12', 'administration/user_management/users/edit', 'Administration - Users - Edit', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('13', 'administration/user_management/users/add', 'Administration - Users - Add', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('14', 'administration/user_management/users/delete', 'Administration - Users - Delete', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('15', 'administration', 'Administration', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('16', 'administration/user_management', 'Administration - User Management', null, '2022-03-10 10:45:47', '1', '2022-03-10 10:45:47', '1');
INSERT INTO `aauth_perms` VALUES ('221', 'administration/user_management/museum', 'Administration - User Management - Museum', null, '2022-06-19 17:06:12', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('222', 'administration/user_management/museum/add', 'Administration - User Management - Museum - Add', null, '2022-06-19 17:06:12', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('223', 'administration/user_management/museum/delete', 'Administration - User Management - Museum - Delete', null, '2022-06-19 17:06:12', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('224', 'administration/user_management/museum/edit', 'Administration - User Management - Museum - Edit', null, '2022-06-19 17:06:12', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('225', 'administration/user_management/departments', 'Administration - User Management - Departments', null, '2022-06-19 17:06:23', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('226', 'administration/user_management/departments/add', 'Administration - User Management - Departments - Add', null, '2022-06-19 17:06:23', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('227', 'administration/user_management/departments/delete', 'Administration - User Management - Departments - Delete', null, '2022-06-19 17:06:23', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('228', 'administration/user_management/departments/edit', 'Administration - User Management - Departments - Edit', null, '2022-06-19 17:06:23', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('229', 'administration/user_management/employees', 'Administration - User Management - Employees', null, '2022-06-19 17:06:36', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('230', 'administration/user_management/employees/add', 'Administration - User Management - Employees - Add', null, '2022-06-19 17:06:36', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('231', 'administration/user_management/employees/delete', 'Administration - User Management - Employees - Delete', null, '2022-06-19 17:06:36', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('232', 'administration/user_management/employees/edit', 'Administration - User Management - Employees - Edit', null, '2022-06-19 17:06:36', '1', null, null);
INSERT INTO `aauth_perms` VALUES ('233', 'master', 'Master', null, '2022-06-26 01:28:48', '1', null, null);
