#
# Table structure for table `xcomic_comics`
#

DROP TABLE IF EXISTS `xcomic_comics`;
CREATE TABLE `xcomic_comics` (
  `cid` int(10) unsigned NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `filename` varchar(80) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`)
);

#
# Table structure for table `xcomic_config`
#

DROP TABLE IF EXISTS `xcomic_config`;
CREATE TABLE `xcomic_config` (
  `order` smallint(5) unsigned NOT NULL default '0',
  `option` varchar(50) NOT NULL default '',
  `value` text NOT NULL default '',
  `vartype` enum('string','number','boolean') NOT NULL default 'string',
  `displaycode` varchar(20) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `description` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`option`),
  UNIQUE KEY `order` (`order`)
);


#
# Table structure for table `xcomic_news`
#

DROP TABLE IF EXISTS `xcomic_news`;
CREATE TABLE `xcomic_news` (
  `id` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `uid` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
);

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
);

#
# Table structure for table `xcomic_admin_menu`
#

DROP TABLE IF EXISTS `xcomic_admin_menu`;
CREATE TABLE IF NOT EXISTS `xcomic_admin_menu` (
  `linkto` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `associatedpage` varchar(255) NOT NULL default 'all',
  `level` varchar(255) NOT NULL default 'top',
  `position` int(11) NOT NULL default '0',
  PRIMARY KEY  (`linkto`)
);

#
# Dumping data for table `xcomic_admin_menu`
#

INSERT INTO `xcomic_admin_menu` VALUES ('dashboard.php', 'Dashboard', 'all', 'top', 0);
INSERT INTO `xcomic_admin_menu` VALUES ('editcomiclist.php', 'Edit Comics', 'all', 'top', 6);
INSERT INTO `xcomic_admin_menu` VALUES ('editnewslist.php', 'Edit News', 'all', 'top', 14);
INSERT INTO `xcomic_admin_menu` VALUES ('logout.php', 'Logout', 'all', 'top', 100);
INSERT INTO `xcomic_admin_menu` VALUES ('options.php', 'Options', 'all', 'top', 22);
INSERT INTO `xcomic_admin_menu` VALUES ('plugins.php', 'Plugins', 'all', 'top', 24);
INSERT INTO `xcomic_admin_menu` VALUES ('postcomic.php', 'Post Comic', 'all', 'top', 2);
INSERT INTO `xcomic_admin_menu` VALUES ('postnews.php', 'Post News', 'all', 'top', 10);
INSERT INTO `xcomic_admin_menu` VALUES ('staticpages.php', 'Static Pages', 'all', 'top', 17);
INSERT INTO `xcomic_admin_menu` VALUES ('themes.php', 'Themes', 'all', 'top', 16);
INSERT INTO `xcomic_admin_menu` VALUES ('users.php', 'Users', 'all', 'top', 18);
INSERT INTO `xcomic_admin_menu` VALUES ('viewsite.php', 'View Site', 'all', 'top', 26);

#
# Table structure for table `xcomic_admin_vars`
#

DROP TABLE IF EXISTS `xcomic_admin_vars`;
CREATE TABLE IF NOT EXISTS `xcomic_admin_vars` (
  `name` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`name`)
);

#
# Dumping data for table `xcomic_admin_vars`
#

INSERT INTO `xcomic_admin_vars` VALUES ('page_directory_files', 'dashboard.php,editcomic.php,editcomiclist.php,editnews.php,editnewslist.php,edituser.php,index.php,logout.php,options.php,plugins.php,postcomic.php,postnews.php,staticpages.php,themes.php,users.php,viewsite.php');

#
# Table structure for table `xcomic_plugins`
#

DROP TABLE IF EXISTS `xcomic_plugins`;
CREATE TABLE IF NOT EXISTS `xcomic_plugins` (
  `name` varchar(50) NOT NULL default '',
  `order` smallint(5) unsigned NOT NULL default '0',
  `active` tinyint(1) unsigned NOT NULL default '0',
  `type` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`name`),
  KEY `active` (`active`),
  KEY `order` (`order`)
);

#
# Dumping data for table `xcomic_plugins`
#

INSERT INTO `xcomic_plugins` VALUES ('XcomicCore', 0, 1, 'builtin');
INSERT INTO `xcomic_plugins` VALUES ('XcomicNavigation', 1, 1, 'builtin');
INSERT INTO `xcomic_plugins` VALUES ('XcomicDisplay', 2, 1, 'builtin');
INSERT INTO `xcomic_plugins` VALUES ('XcomicNewsDisplay', 3, 1, 'builtin');

#
# Table structure for table `xcomic_plugin_vars`
#

DROP TABLE IF EXISTS `xcomic_plugin_vars`;
CREATE TABLE IF NOT EXISTS `xcomic_plugin_vars` (
  `plugin` varchar(50) NOT NULL default '',
  `option` varchar(50) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`plugin`,`option`)
);

#
# Table structure for table `xcomic_pages`
#

CREATE TABLE `xcomic_pages` (
  `pagename` varchar(30) NOT NULL default '',
  `themefile` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pagename`)
);

#
# Table structure for table `xcomic_comics_seq`
#

DROP TABLE IF EXISTS `xcomic_comics_seq`;
CREATE TABLE IF NOT EXISTS `xcomic_comics_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
);

#
# Table structure for table `xcomic_news_seq`
#

DROP TABLE IF EXISTS `xcomic_news_seq`;
CREATE TABLE IF NOT EXISTS `xcomic_news_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
);

#
# Table structure for table `xcomic_users_seq`
#

DROP TABLE IF EXISTS `xcomic_users_seq`;
CREATE TABLE IF NOT EXISTS `xcomic_users_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
);
