#
# Table structure for table `xcomic_comics`
#

DROP TABLE IF EXISTS `xcomic_comics`;
CREATE TABLE `xcomic_comics` (
  `cid` int(255) unsigned NOT NULL,
  `title` varchar(50) NOT NULL default '',
  `filename` varchar(80) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM;

#
# Table structure for table `xcomic_config`
#

DROP TABLE IF EXISTS `xcomic_config`;
CREATE TABLE `xcomic_config` (
  `order` smallint(5) unsigned NOT NULL default '0',
  `type` tinyint(2) unsigned NOT NULL default '0',
  `option` varchar(50) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`option`),
  UNIQUE KEY `order` (`order`)
) TYPE=MyISAM;


#
# Table structure for table `xcomic_news`
#

DROP TABLE IF EXISTS `xcomic_news`;
CREATE TABLE `xcomic_news` (
  `id` int(10) unsigned NOT NULL,
  `cid` int(255) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `date` int(11) unsigned NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `uid` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

#
# Table structure for table `xcomic_users`
#

DROP TABLE IF EXISTS `xcomic_users`;
CREATE TABLE `xcomic_users` (
  `uid` int(11) unsigned NOT NULL,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) TYPE=MyISAM;