/*
 Navicat Premium Data Transfer

 Source Server         : 本机
 Source Server Type    : MySQL
 Source Server Version : 50649
 Source Host           : 192.168.33.10:3306
 Source Schema         : third-party

 Target Server Type    : MySQL
 Target Server Version : 50649
 File Encoding         : 65001

 Date: 01/12/2020 09:16:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tp_admin
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin`;
CREATE TABLE `tp_admin`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `nick_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '添加时间',
  `last_login_time` int(10) NULL DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '员工表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_admin
-- ----------------------------
INSERT INTO `tp_admin` VALUES (2, 'admin', '管理员', 'https://dss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=1819216937,2118754409&fm=26&gp=0.jpg', '$2y$10$TgcD8QbG7lwh74heWutUPeGJZ21VE/askPEgs3q3aQEemUAYqDecK', 1605338840, 1606728297);
INSERT INTO `tp_admin` VALUES (3, 'ceshi', '测试', NULL, '$2y$10$02FR.d.lH92H/EIhmm7eE.YXSE7QSotKZ7Z/Tu5481OJt914pPwRe', 1606703719, 1606726502);
INSERT INTO `tp_admin` VALUES (4, 'cececec', '阿萨德', NULL, '$2y$10$E47DzaD41w0ra03eD13kEuDXFNIFTah5YS6API39INVEWVcLke9em', 1606727239, NULL);
INSERT INTO `tp_admin` VALUES (5, 'svvvvv', '安达市多', NULL, '$2y$10$a1VZyJfg8Otp4UIZy8LAGetCCxupsi/Ni8xaTdkH9cb51.MJ2s6xS', 1606727398, NULL);
INSERT INTO `tp_admin` VALUES (6, 'ffff', '打打杀杀多', NULL, '$2y$10$loQ5AzulcOSBVqI6tS7tQ.Coxx11uwG3AVZ3x01mvDRGsHFw6lRSq', 1606727448, NULL);
INSERT INTO `tp_admin` VALUES (8, 'sfddsfsd', '达到', NULL, '$2y$10$sFC6bLFNGTJQKTNYqaI/CeYMGYqY2FzC/qUNiZQoP.OKqDwARsd4G', 1606727621, NULL);

-- ----------------------------
-- Table structure for tp_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_role`;
CREATE TABLE `tp_admin_role`  (
  `admin_id` mediumint(9) NOT NULL,
  `role_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`admin_id`, `role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色关联表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_admin_role
-- ----------------------------
INSERT INTO `tp_admin_role` VALUES (2, 1);
INSERT INTO `tp_admin_role` VALUES (3, 7);
INSERT INTO `tp_admin_role` VALUES (3, 15);

-- ----------------------------
-- Table structure for tp_power
-- ----------------------------
DROP TABLE IF EXISTS `tp_power`;
CREATE TABLE `tp_power`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '接口名称',
  `permission` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限唯一Key',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '接口地址',
  `pid` mediumint(9) UNSIGNED NULL DEFAULT 0 COMMENT '上级ID',
  `menu_type` tinyint(3) UNSIGNED NULL DEFAULT NULL COMMENT '菜单类型',
  `sort` smallint(6) UNSIGNED NULL DEFAULT 0 COMMENT '排序',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限路径',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 75 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_power
-- ----------------------------
INSERT INTO `tp_power` VALUES (20, '权限模块', 'power', '', 0, 0, 0, '20', 1605322265);
INSERT INTO `tp_power` VALUES (21, '管理员设置', 'powerAdmin', '', 20, 0, 0, '20/21', 1605322286);
INSERT INTO `tp_power` VALUES (22, '新增管理员', 'powerAdminListCreate', 'power/admin/create', 63, 1, 0, '20/21/63/22', 1605322376);
INSERT INTO `tp_power` VALUES (51, '权限设置', 'powerPower', '', 20, 0, 0, '20/51', 1606199922);
INSERT INTO `tp_power` VALUES (52, '权限列表', 'powerPowerList', 'power/power/index', 51, 0, 0, '20/51/52', 1606199953);
INSERT INTO `tp_power` VALUES (53, '新增权限', 'powerPowerListCreate', 'power/power/create', 52, 1, 0, '20/51/52/53', 1606200056);
INSERT INTO `tp_power` VALUES (61, '删除权限', 'powerPowerListDelete', 'power/power/delete', 52, 1, 0, '20/51/52/61', 1606203858);
INSERT INTO `tp_power` VALUES (62, '编辑权限', 'powerPowerListUpdate', 'power/power/update', 52, 1, 0, '20/51/52/62', 1606208022);
INSERT INTO `tp_power` VALUES (63, '管理员列表', 'powerAdminList', 'power/admin/index', 21, 0, 0, '20/21/63', 1606208089);
INSERT INTO `tp_power` VALUES (64, '仪表盘', 'dashboard', NULL, 0, 0, 65535, '64', 1606208089);
INSERT INTO `tp_power` VALUES (65, '数据汇总', 'analysis', NULL, 64, 0, 65535, '64/65', 1606208089);
INSERT INTO `tp_power` VALUES (66, '编辑管理员', 'powerAdminListUpdate', 'power/admin/update', 63, 1, 0, '20/21/63/66', 1606532903);
INSERT INTO `tp_power` VALUES (67, '删除管理员', 'powerAdminListDelete', 'power/admin/delete', 63, 1, 0, '20/21/63/67', 1606532939);
INSERT INTO `tp_power` VALUES (68, '角色设置', 'powerRole', '', 20, 0, 0, '20/68', 1606533622);
INSERT INTO `tp_power` VALUES (69, '角色列表', 'powerRoleList', 'power/role/index', 68, 0, 0, '20/68/69', 1606533671);
INSERT INTO `tp_power` VALUES (70, '新增角色', 'powerRoleListCreate', 'power/role/create', 69, 1, 0, '20/68/69/70', 1606544130);
INSERT INTO `tp_power` VALUES (71, '编辑角色', 'powerRoleListUpdate', 'power/role/update', 69, 1, 0, '20/68/69/71', 1606544164);
INSERT INTO `tp_power` VALUES (72, '删除角色', 'powerRoleListDelete', 'power/role/delete', 69, 1, 0, '20/68/69/72', 1606544194);
INSERT INTO `tp_power` VALUES (73, '权限设置', 'powerRoleListSetPower', 'power/role/setpower', 69, 1, 0, '20/68/69/73', 1606546262);
INSERT INTO `tp_power` VALUES (74, '角色设置', 'powerAdminListSetRole', 'power/admin/setrole', 63, 1, 0, '20/21/63/74', 1606554409);

-- ----------------------------
-- Table structure for tp_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_role
-- ----------------------------
INSERT INTO `tp_role` VALUES (1, '超级管理员', NULL);
INSERT INTO `tp_role` VALUES (7, '测试角色', 1606546357);
INSERT INTO `tp_role` VALUES (15, '测试2', 1606706808);

-- ----------------------------
-- Table structure for tp_role_power
-- ----------------------------
DROP TABLE IF EXISTS `tp_role_power`;
CREATE TABLE `tp_role_power`  (
  `role_id` mediumint(9) NOT NULL,
  `power_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`role_id`, `power_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色权限关联表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tp_role_power
-- ----------------------------
INSERT INTO `tp_role_power` VALUES (7, 20);
INSERT INTO `tp_role_power` VALUES (7, 21);
INSERT INTO `tp_role_power` VALUES (7, 51);
INSERT INTO `tp_role_power` VALUES (7, 52);
INSERT INTO `tp_role_power` VALUES (7, 63);
INSERT INTO `tp_role_power` VALUES (7, 64);
INSERT INTO `tp_role_power` VALUES (7, 65);
INSERT INTO `tp_role_power` VALUES (15, 22);

SET FOREIGN_KEY_CHECKS = 1;
