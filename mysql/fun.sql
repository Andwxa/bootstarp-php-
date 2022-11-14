/*
SQLyog Ultimate v12.08 (64 bit)
MySQL - 5.7.26 : Database - traditional_culture_exchange_network
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`traditional_culture_exchange_network` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `traditional_culture_exchange_network`;

/*Table structure for table `fun_article` */

DROP TABLE IF EXISTS `fun_article`;

CREATE TABLE `fun_article` (
  `aId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `uId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(20) NOT NULL COMMENT '内容标题',
  `content` mediumtext NOT NULL COMMENT '内容文本',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `reply` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复量',
  PRIMARY KEY (`aId`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_column` */

DROP TABLE IF EXISTS `fun_column`;

CREATE TABLE `fun_column` (
  `cId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `cover` varchar(20) NOT NULL DEFAULT '' COMMENT '图片地址',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`cId`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_comment` */

DROP TABLE IF EXISTS `fun_comment`;

CREATE TABLE `fun_comment` (
  `cId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章id',
  `uId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '回复时间',
  PRIMARY KEY (`cId`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_identity` */

DROP TABLE IF EXISTS `fun_identity`;

CREATE TABLE `fun_identity` (
  `key` varchar(255) NOT NULL COMMENT 'SessionID',
  `expires` int(10) unsigned NOT NULL COMMENT '过期时间',
  `uId` varchar(255) DEFAULT NULL COMMENT '数据',
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `fun_inform` */

DROP TABLE IF EXISTS `fun_inform`;

CREATE TABLE `fun_inform` (
  `iId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '投诉id',
  `uId` int(10) unsigned NOT NULL COMMENT '举报人id',
  `type` varchar(30) NOT NULL COMMENT '投诉的表',
  `id` int(10) unsigned NOT NULL COMMENT '投诉表里的id',
  `commint` text NOT NULL COMMENT '投诉内容',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '举报时间',
  `complete` int(1) NOT NULL DEFAULT '0' COMMENT '0未审核,1正在审核2,投诉成功,3投诉失败',
  PRIMARY KEY (`iId`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_journal` */

DROP TABLE IF EXISTS `fun_journal`;

CREATE TABLE `fun_journal` (
  `jId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `incident` text COLLATE utf8_unicode_ci NOT NULL COMMENT '事件',
  `uId` int(10) unsigned NOT NULL COMMENT '动作发生者',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`jId`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fun_message` */

DROP TABLE IF EXISTS `fun_message`;

CREATE TABLE `fun_message` (
  `mId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `group` enum('user','all','only','admin') NOT NULL DEFAULT 'all' COMMENT '对象身份',
  `uId` int(10) unsigned NOT NULL COMMENT '用户id',
  `mcId` int(10) unsigned NOT NULL COMMENT '内容id',
  `read` int(1) NOT NULL DEFAULT '0' COMMENT '是否已读',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`mId`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_message_content` */

DROP TABLE IF EXISTS `fun_message_content`;

CREATE TABLE `fun_message_content` (
  `mcId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息内容id',
  `content` text NOT NULL COMMENT '消息内容',
  PRIMARY KEY (`mcId`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_review_article` */

DROP TABLE IF EXISTS `fun_review_article`;

CREATE TABLE `fun_review_article` (
  `rAId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cId` int(10) unsigned NOT NULL COMMENT '栏目Id',
  `uId` int(10) unsigned NOT NULL COMMENT '用户Id',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '内容文本',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  PRIMARY KEY (`rAId`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_task` */

DROP TABLE IF EXISTS `fun_task`;

CREATE TABLE `fun_task` (
  `taskId` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `taskName` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '任务名称',
  `taskDetail` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '任务细节',
  `taskIntegral` int(10) NOT NULL COMMENT '任务积分',
  `taskNumber` int(3) NOT NULL COMMENT '任务需要次数',
  `webpageName` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '应用的页面',
  `employ` int(1) DEFAULT '1' COMMENT '是否使用',
  PRIMARY KEY (`taskId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fun_task_list` */

DROP TABLE IF EXISTS `fun_task_list`;

CREATE TABLE `fun_task_list` (
  `taskId` int(10) NOT NULL COMMENT '任务id',
  `uId` int(10) DEFAULT NULL COMMENT '用户id',
  `number` int(3) DEFAULT '0' COMMENT '任务次数',
  `complete` int(1) DEFAULT '0' COMMENT '0未完成1进行中2领取奖励3已完成',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fun_user` */

DROP TABLE IF EXISTS `fun_user`;

CREATE TABLE `fun_user` (
  `uId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` enum('admin','user') NOT NULL DEFAULT 'user' COMMENT '用户组',
  `name` varchar(10) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(32) NOT NULL DEFAULT '' COMMENT '密钥',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像地址',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `employTime` int(10) DEFAULT NULL COMMENT '禁用时间',
  PRIMARY KEY (`uId`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Table structure for table `fun_user_information` */

DROP TABLE IF EXISTS `fun_user_information`;

CREATE TABLE `fun_user_information` (
  `uId` int(10) unsigned NOT NULL COMMENT '用户id',
  `nickname` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名称',
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '未填写' COMMENT '用户电话',
  `QQ` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '未填写' COMMENT '用户QQ',
  `wechat` varchar(20) COLLATE utf8_unicode_ci DEFAULT '未填写' COMMENT '用户微信',
  `card` varchar(20) COLLATE utf8_unicode_ci DEFAULT '未填写' COMMENT '用户身份证',
  `unit` varchar(40) COLLATE utf8_unicode_ci DEFAULT '未填写' COMMENT '用户单位',
  `privacy` int(1) DEFAULT '1' COMMENT '用户隐私，默认开启',
  `integral` int(13) DEFAULT '1' COMMENT '用户积分',
  `usingIntegral` int(13) DEFAULT '1' COMMENT '用户已经使用的积分',
  PRIMARY KEY (`uId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
