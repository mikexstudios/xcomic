# phpMyAdmin SQL Dump
# version 2.5.2-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jun 18, 2004 at 09:15 AM
# Server version: 3.23.41
# PHP Version: 4.3.6
# 
# Database : `testxcomic`
# 

# --------------------------------------------------------

#
# Table structure for table `comics`
#
# Creation: Jun 18, 2004 at 09:13 AM
# Last update: Jun 18, 2004 at 09:13 AM
#

DROP TABLE IF EXISTS `comics`;
CREATE TABLE `comics` (
  `cid` int(255) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `filename` varchar(80) NOT NULL default '',
  `date` date default NULL,
  `newsitem` int(255) unsigned default NULL,
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `comics`
#


# --------------------------------------------------------

#
# Table structure for table `config`
#
# Creation: Jun 18, 2004 at 09:13 AM
# Last update: Jun 18, 2004 at 09:13 AM
#

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `option` varchar(50) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`option`)
) TYPE=MyISAM;

#
# Dumping data for table `config`
#

INSERT INTO `config` VALUES ('usingTheme', 'default');

# --------------------------------------------------------

#
# Table structure for table `news`
#
# Creation: Jun 18, 2004 at 09:13 AM
# Last update: Jun 18, 2004 at 09:13 AM
#

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `category` varchar(100) NOT NULL default 'default',
  `date` date default NULL,
  `username` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

#
# Dumping data for table `news`
#


# --------------------------------------------------------

#
# Table structure for table `newscategories`
#
# Creation: Jun 18, 2004 at 09:13 AM
# Last update: Jun 18, 2004 at 09:13 AM
#

DROP TABLE IF EXISTS `newscategories`;
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
# Creation: Jun 18, 2004 at 09:13 AM
# Last update: Jun 18, 2004 at 09:13 AM
#

DROP TABLE IF EXISTS `nextcomicstatus`;
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

INSERT INTO `nextcomicstatus` VALUES (0, '2004-06-16', 1, 'This is where the comments for the next comic status goes.');

# --------------------------------------------------------

#
# Table structure for table `users`
#
# Creation: Jun 18, 2004 at 09:14 AM
# Last update: Jun 18, 2004 at 09:14 AM
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

#
# Dumping data for table `users`
#

INSERT INTO `users` VALUES (1, 'admin', '925ad2679b095816cfc0cf772f467229', 'yourname@yourdomain.com');
