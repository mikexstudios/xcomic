# phpMyAdmin SQL Dump
# version 2.5.2-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jun 16, 2004 at 09:24 PM
# Server version: 3.23.41
# PHP Version: 4.3.6
# 
# Database : `xcomic`
# 

# --------------------------------------------------------

#
# Table structure for table `comics`
#
# Creation: Jun 13, 2004 at 09:59 AM
# Last update: Jun 15, 2004 at 05:02 PM
#

CREATE TABLE `comics` (
  `cid` int(255) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `filename` varchar(80) NOT NULL default '',
  `date` date default NULL,
  `newsitem` int(255) unsigned default NULL,
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

#
# Dumping data for table `comics`
#

INSERT INTO `comics` VALUES (1, 'First Test', '0542.gif', '2004-06-11', NULL);
INSERT INTO `comics` VALUES (2, 'The next comic', '0529.gif', '2004-06-12', NULL);
INSERT INTO `comics` VALUES (3, 'Third comic', '0563.gif', '2004-06-13', NULL);
INSERT INTO `comics` VALUES (4, 'The lost day', '0565.gif', '2004-06-15', NULL);

# --------------------------------------------------------

#
# Table structure for table `config`
#
# Creation: Jun 12, 2004 at 11:30 AM
# Last update: Jun 13, 2004 at 01:36 PM
#

CREATE TABLE `config` (
  `option` varchar(50) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`option`)
) TYPE=MyISAM;

#
# Dumping data for table `config`
#

INSERT INTO `config` VALUES ('usingTheme', 'default');
INSERT INTO `config` VALUES ('sitename', 'Xcomic');
INSERT INTO `config` VALUES ('version', '0.1.0');
INSERT INTO `config` VALUES ('urlToXcomic', 'http://labs/Xcomic');
INSERT INTO `config` VALUES ('baseUrl', 'http://labs');

# --------------------------------------------------------

#
# Table structure for table `news`
#
# Creation: Jun 13, 2004 at 03:14 PM
# Last update: Jun 15, 2004 at 05:02 PM
#

CREATE TABLE `news` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `category` varchar(100) NOT NULL default 'default',
  `date` date default NULL,
  `username` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=12 ;

#
# Dumping data for table `news`
#

INSERT INTO `news` VALUES (2, 'test test test', 'default', NULL, '', 'test test test');
INSERT INTO `news` VALUES (3, 'Title!', 'default', '2004-06-10', '', 'test test test');
INSERT INTO `news` VALUES (11, 'The Lost Day', 'default', '2004-06-15', '', 'Wow, it\'s been difficult. I\'ve just lost a few days!');
INSERT INTO `news` VALUES (5, 'This is a title', 'right', '2004-06-12', '', 'I want it in the right category.');
INSERT INTO `news` VALUES (6, 'So this goes on the left', 'default', '2004-06-13', '', 'Hi, \r\n\r\nthis is totally a new post. Coolz!');
INSERT INTO `news` VALUES (7, 'yeah', 'left', '2004-06-12', '', 'default stuff');
INSERT INTO `news` VALUES (8, 'okay', 'default', '2004-06-13', '', 'this is crap.');
INSERT INTO `news` VALUES (10, 'This should replace right', 'right', '2004-06-13', '', 'This should replace the right side.');

# --------------------------------------------------------

#
# Table structure for table `newscategories`
#
# Creation: Jun 13, 2004 at 03:16 PM
# Last update: Jun 13, 2004 at 04:10 PM
#

CREATE TABLE `newscategories` (
  `catid` int(100) unsigned NOT NULL auto_increment,
  `catname` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`catid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

#
# Dumping data for table `newscategories`
#

INSERT INTO `newscategories` VALUES (1, 'default');
INSERT INTO `newscategories` VALUES (2, 'right');

# --------------------------------------------------------

#
# Table structure for table `nextcomicstatus`
#
# Creation: Jun 16, 2004 at 05:59 PM
# Last update: Jun 16, 2004 at 06:39 PM
#

CREATE TABLE `nextcomicstatus` (
  `ncid` int(3) NOT NULL default '0',
  `nextdate` date NOT NULL default '0000-00-00',
  `percentstatus` int(4) NOT NULL default '0',
  `comments` text NOT NULL,
  PRIMARY KEY  (`ncid`)
) TYPE=MyISAM;

#
# Dumping data for table `nextcomicstatus`
#

INSERT INTO `nextcomicstatus` VALUES (0, '2004-06-16', 10, 'Hmm, it seems that I\'ve procrastinated a bit. The next comic won\'t be posted until three days after the next day after tomorrow before yesterday.');

# --------------------------------------------------------

#
# Table structure for table `users`
#
# Creation: Jun 14, 2004 at 02:00 PM
# Last update: Jun 15, 2004 at 10:39 AM
#

CREATE TABLE `users` (
  `uid` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=10 ;

#
# Dumping data for table `users`
#

INSERT INTO `users` VALUES (7, 'admin', '098f6bcd4621d373cade4e832627b4f6', 'admin@mx.com');
INSERT INTO `users` VALUES (8, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@test.com');
