# Change comic columns to more valid definitions
ALTER TABLE `xcomic_comics`
    CHANGE `cid` `cid` int(10) unsigned NOT NULL default '0',
    CHANGE `date` `date` int(10) unsigned NOT NULL default '0';


# Add user id and comic id to news
ALTER TABLE `xcomic_news`
    CHANGE `id` `id` int(10) unsigned NOT NULL default '0',
    CHANGE `date` `date` int(10) unsigned NOT NULL default '0',
    ADD `cid` int(10) unsigned NOT NULL default '0' AFTER `id`,
    ADD `uid` int(10) unsigned NOT NULL default '0' AFTER `username`;

# Change auto increment on users
ALTER TABLE `xcomic_users`
    CHANGE `uid` `uid` int(10) unsigned NOT NULL default '0';
    
#
# Table structure for table `xcomic_comics_seq`
#

CREATE TABLE IF NOT EXISTS `xcomic_comics_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=comics_autoinc;

DELETE FROM `xcomic_comics_seq`;
INSERT INTO `xcomic_comics_seq` () VALUES ();

#
# Table structure for table `xcomic_news_seq`
#

CREATE TABLE IF NOT EXISTS `xcomic_news_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=news_autoinc;

DELETE FROM `xcomic_news_seq`;
INSERT INTO `xcomic_news_seq` () VALUES ();

#
# Table structure for table `xcomic_users_seq`
#

CREATE TABLE IF NOT EXISTS `xcomic_users_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=users_autoinc;

DELETE FROM `xcomic_users_seq`;
INSERT INTO `xcomic_users_seq` () VALUES ();

#
# Config table changes
#

ALTER TABLE `xcomic_config`
    DROP PRIMARY KEY;

ALTER TABLE `xcomic_config`
    ADD `order` smallint(5) unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (`order`);

ALTER TABLE `xcomic_config`
    CHANGE `order` `order` smallint(5) unsigned NOT NULL default '0',
    DROP PRIMARY KEY;

ALTER TABLE `xcomic_config`
    ADD PRIMARY KEY (`option`),
    ADD UNIQUE (`order`),
    CHANGE `value` `value` text NOT NULL default '',
    ADD `vartype` enum('string','number','boolean') NOT NULL default 'string' AFTER `value`,
    ADD `displaycode` varchar(20) NOT NULL default '' AFTER `vartype`;

DELETE FROM `xcomic_config` WHERE `option`='urlToXcomic' OR `option`='baseUrl';
INSERT INTO `xcomic_config` SET `order`=num_configs+1,`option`='title',`value`='Xcomic',`displaycode`='text',`name`='Title',`description`='A title for the comic';
INSERT INTO `xcomic_config` SET `order`=num_configs+2,`option`='enableRSS',`value`='1',`vartype`='boolean',`displaycode`='yesno',`name`='Enable RSS',`description`='Enable Really Simple Syndication of comics';
INSERT INTO `xcomic_config` SET `order`=num_configs+3,`option`='rssNumComics',`value`='5',`vartype`='number',`displaycode`='number',`name`='Number of comics to syndicate',`description`='The number of comics syndicated in the RSS feed';
INSERT INTO `xcomic_config` SET `order`=num_configs+4,`option`='usingTheme',`value`='Kubrick',`vartype`='string',`displaycode`='',`name`='Current Theme',`description`='The currently selected theme';
INSERT INTO `xcomic_config` SET `order`=num_configs+5,`option`='gzipcompress',`value`='1',`vartype`='boolean',`displaycode`='yesno',`name`='GZip Compression',`description`=' Compression can speed up pages on browsers that support it, but requires additional server processing';

#
# Admin menu
#

CREATE TABLE IF NOT EXISTS `xcomic_admin_menu` (
  `linkto` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `associatedpage` varchar(255) NOT NULL default 'all',
  `level` varchar(255) NOT NULL default 'top',
  `position` int(11) NOT NULL default '0',
  PRIMARY KEY  (`linkto`)
);

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

CREATE TABLE IF NOT EXISTS `xcomic_admin_vars` (
  `name` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`name`)
);

INSERT INTO `xcomic_admin_vars` VALUES ('page_directory_files', 'dashboard.php,editcomic.php,editcomiclist.php,editnews.php,editnewslist.php,edituser.php,index.php,logout.php,options.php,plugins.php,postcomic.php,postnews.php,staticpages.php,themes.php,users.php,viewsite.php');

#
# Plugins
#

CREATE TABLE IF NOT EXISTS `xcomic_plugins` (
  `name` varchar(50) NOT NULL default '',
  `order` smallint(5) unsigned NOT NULL default '0',
  `active` tinyint(1) unsigned NOT NULL default '0',
  `type` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`name`),
  KEY `active` (`active`),
  KEY `order` (`order`)
);

INSERT INTO `xcomic_plugins` VALUES ('XcomicCore', 0, 1, 'builtin');
INSERT INTO `xcomic_plugins` VALUES ('XcomicNavigation', 1, 1, 'builtin');
INSERT INTO `xcomic_plugins` VALUES ('XcomicDisplay', 2, 1, 'builtin');
INSERT INTO `xcomic_plugins` VALUES ('XcomicNewsDisplay', 3, 1, 'builtin');


CREATE TABLE IF NOT EXISTS `xcomic_plugin_vars` (
  `plugin` varchar(50) NOT NULL default '',
  `option` varchar(50) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`plugin`,`option`)
);

#
# Static pages
#

CREATE TABLE `xcomic_pages` (
  `pagename` varchar(30) NOT NULL default '',
  `themefile` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pagename`)
);
