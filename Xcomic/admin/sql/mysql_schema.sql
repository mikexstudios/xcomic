# phpMyAdmin SQL Dump
# version 2.5.2-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Nov 17, 2004 at 07:57 PM
# Server version: 3.23.41
# PHP Version: 4.3.6
# 
# Database : `xcomic065b`
# 

# --------------------------------------------------------

#
# Table structure for table `comics`
#
# Creation: Nov 17, 2004 at 07:56 PM
# Last update: Nov 17, 2004 at 07:56 PM
#

DROP TABLE IF EXISTS `comics`;
CREATE TABLE `comics` (
  `cid` int(255) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `filename` varchar(80) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
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
# Creation: Nov 17, 2004 at 07:56 PM
# Last update: Nov 17, 2004 at 07:56 PM
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


# --------------------------------------------------------

#
# Table structure for table `news`
#
# Creation: Nov 17, 2004 at 07:56 PM
# Last update: Nov 17, 2004 at 07:56 PM
#

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

#
# Dumping data for table `news`
#


# --------------------------------------------------------

#
# Table structure for table `users`
#
# Creation: Nov 17, 2004 at 07:56 PM
# Last update: Nov 17, 2004 at 07:56 PM
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
