#
# Table structure for table `comics`
#

DROP TABLE IF EXISTS `comics`;
CREATE TABLE `comics` (
  `cid` int(255) unsigned NOT NULL,
  `title` varchar(50) NOT NULL default '',
  `filename` varchar(80) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM;

#
# Table structure for table `config`
#

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `option` varchar(50) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`option`)
) TYPE=MyISAM;


#
# Table structure for table `news`
#

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
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
# Table structure for table `users`
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` int(11) unsigned NOT NULL,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) TYPE=MyISAM;