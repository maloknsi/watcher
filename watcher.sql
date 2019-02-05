/*
Navicat MySQL Data Transfer

Source Server         : [localhost]-root
Source Server Version : 50638
Source Host           : 127.0.0.1:3306
Source Database       : watcher

Target Server Type    : MYSQL
Target Server Version : 50638
File Encoding         : 65001

Date: 2019-02-05 10:37:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for journal
-- ----------------------------
DROP TABLE IF EXISTS `journal`;
CREATE TABLE `journal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `external_code` varchar(255) DEFAULT NULL,
  `status` smallint(1) DEFAULT '0',
  `file_marker` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `publish_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of journal
-- ----------------------------
INSERT INTO `journal` VALUES ('1', '1', 'журнал1', '1547769600', '10', '1547769600.dat', '2019-02-03 23:43:34', '2019-02-02 00:00:00');
INSERT INTO `journal` VALUES ('2', '2', 'журнал2', '832982316729', '0', '832982316729.dat', '2019-01-04 02:16:48', '2019-02-04 00:00:00');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `role` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('5', 'admin', '$2y$13$jIShknBfGsRrEn5z6DFmqe5gd/oAxhkumGyMEFV/velLePlo1C1Hy', null, 'maloknsi@gmail.com', '10', '2019-02-03 22:42:14', '2019-02-03 22:42:39', '4');
INSERT INTO `user` VALUES ('7', 'malok', '$2y$13$UX8SXiguCWUTZ1iNJs1GU.6IEjjM6zuyEVho4cZgm4Gucepu2XTJ.', null, 'nsi@ukr.net', '10', '2019-02-03 22:42:14', '0000-00-00 00:00:00', '4');

-- ----------------------------
-- Table structure for video
-- ----------------------------
DROP TABLE IF EXISTS `video`;
CREATE TABLE `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_id` int(11) DEFAULT NULL,
  `number` int(11) NOT NULL,
  `file_video` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-journal_id-journal-id` (`journal_id`),
  CONSTRAINT `fk-journal_id-journal-id` FOREIGN KEY (`journal_id`) REFERENCES `journal` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of video
-- ----------------------------
INSERT INTO `video` VALUES ('1', '1', '0', null);
INSERT INTO `video` VALUES ('2', '1', '1', null);
INSERT INTO `video` VALUES ('37', '1', '2', null);
INSERT INTO `video` VALUES ('38', '1', '3', null);
INSERT INTO `video` VALUES ('39', '1', '4', null);
INSERT INTO `video` VALUES ('40', '1', '5', null);
INSERT INTO `video` VALUES ('41', '2', '0', null);
INSERT INTO `video` VALUES ('42', '2', '1', null);
INSERT INTO `video` VALUES ('43', '2', '2', null);
INSERT INTO `video` VALUES ('44', '2', '3', null);
INSERT INTO `video` VALUES ('45', '2', '4', null);
