-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 03, 2014 at 04:50 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0; #--disable foreign key checks

--
-- Database: `nybr`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE IF NOT EXISTS `checkouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(13,2) NOT NULL,
  `card_description` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL,
  `vendor` enum('stripe') NOT NULL,
  `vendor_transaction_id` varchar(255) NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_transaction_id` (`vendor_transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `version_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `content` longtext,
  `revision_date` datetime NOT NULL,
  `publish_date` datetime,
  `updated_by` int(11) NOT NULL,
  `published_by` int(11),
  `live` tinyint(1),
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_blocks`
--

CREATE TABLE IF NOT EXISTS `content_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objectkey` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `output_type` enum('content','meta','special_view') NOT NULL,
  `output_view` varchar(255),
  `input_type` enum('wysiwyg','textarea','textfield','filemanager','multifield','customform') NOT NULL,
  `input_parameters` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_versions`
--

CREATE TABLE IF NOT EXISTS `content_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `selector` enum('url','role','session') NOT NULL,
  `selector_key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------


--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups_roles`
--

CREATE TABLE `groups_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `access_granted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=238 ;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ul_html` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `menus_pages`
--

CREATE TABLE IF NOT EXISTS `menus_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `link_type` enum('pages_id','url','none') NOT NULL DEFAULT 'pages_id',
  `link_value` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `target` varchar(50) NOT NULL,
  `link_attributes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `menus_pages_live`
--

CREATE TABLE IF NOT EXISTS `menus_pages_live` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `link_type` enum('pages_id','url','none') NOT NULL DEFAULT 'pages_id',
  `link_value` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `target` varchar(50) NOT NULL,
  `link_attributes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL,
  `required_role` varchar(255) NOT NULL,
  `add_children_role` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `searchable` tinyint(1) NOT NULL DEFAULT '1',
  `display_in_sitemap` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- Table structure for table `redirects`
--

CREATE TABLE IF NOT EXISTS `redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `is_301` tinyint(1) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `hits` int(11) DEFAULT '0',
  `last_hit` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '0=deleted,1=inactive,2=active;',
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `role_type` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `session_token`
--

CREATE TABLE IF NOT EXISTS `session_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IP` varchar(15) NOT NULL,
  `UA` varchar(500) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `loggedintoken` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('shell','layout','page') NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `template_content_blocks`
--

CREATE TABLE IF NOT EXISTS `template_content_blocks` (
  `template_id` int(11) NOT NULL,
  `content_block_id` int(11) NOT NULL,
  `required_edit_role` int(11) NOT NULL DEFAULT '0',
  KEY `template_id` (`template_id`),
  KEY `content_block_id` (`content_block_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first` varchar(50) NOT NULL,
  `last` varchar(50) NOT NULL,
  `logins` int(10) unsigned DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `lock_login_until` datetime DEFAULT NULL,
  `auth_scheme` tinyint(4) NOT NULL COMMENT '0=native,1=ldap,2=openid,3=multidb',
  `status` tinyint(4) DEFAULT '2' COMMENT '0=deleted,1=inactive,2=active',
  `username` varchar(255) DEFAULT NULL,
  `data` LONGTEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  `created` int(10) NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `roles_users`
--
ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- DATA --
INSERT INTO `content_blocks` (`id`, `objectkey`, `name`, `description`, `output_type`, `output_view`, `input_type`, `input_parameters`) VALUES
(1, 'meta_keywords', 'Meta Keywords', 'Meta keywords for search engine optimization', 'meta', '', 'textarea', '<textarea name="content" id="content" rows="5" style="width: 500px">{content}</textarea>'),
(2, 'meta_description', 'Meta Description', 'Meta description for search engine optimization', 'meta', '', 'textfield', '<input type="text" name="content" id="content" value="{content}" />'),
(3, 'main_content', 'Main Content', 'Main body of content for a tier page', 'content', '', 'wysiwyg', '<textarea name="content" id="content">{content}</textarea> \n<script type="text/javascript"> \n$(''#content'').ckeditor(function() \n	{ CKFinder.setupCKEditor(this,''/ckfinder/'') }, \n	{  allowedContent: true, \n          resize_enabled : true, \n	  height : ''350px'', \n	  toolbar : ''CMSdefault'' //these are set in CKeditor/config.js \n	} \n); \n</script>'),
(4, 'extra_head_code', 'Extra Header Code', 'HTML/JavaScript/CSS to be included in the >head<', 'meta', '', 'textarea', '<textarea name="content" id="content" rows="5" style="width: 550px">{content}</textarea>'),
(5, 'meta_title', 'Meta Title', 'Meta title for search engine optimization', 'meta', '', 'textfield', '<input type="text" name="content" id="content" value="{content}" />');

INSERT INTO `content_versions` (`id`, `name`, `description`, `selector`, `selector_key`) VALUES
(1, 'default', 'Standard version for all content', '', '');

INSERT INTO `groups` VALUES ('1', 'developers', 'The Permission that allows to configure all elements of the web site'), ('11', 'admin', 'The Permission that allows access to everything except the developer section'), ('21', 'managers', 'The Permission set that allows the user to login, to view content on pages, and to edit all the dashboard tools'), ('31', 'publishers', 'The Permission set that allows the user to create, edit, or publish any of the page contents'), ('41', 'users', 'The Permission set that allows the user to login and view content on the CMS.');


INSERT INTO `groups_roles` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(7,1,6),(8,1,7),(9,1,8),(10,1,9),(11,1,100),(12,1,101),(13,1,102),(14,1,103),(15,11,1),(16,11,2),(17,11,3),(18,11,4),(19,11,5),(20,11,6),(21,11,7),(22,11,8),(23,11,100),(24,11,101),(25,11,102),(26,11,103),(27,21,1),(28,21,2),(29,21,100),(30,21,101),(31,21,102),(32,21,103),(33,31,1),(34,31,2),(35,31,3),(36,31,4),(37,31,5),(38,31,6),(39,31,7),(40,31,8),(41,41,1),(42,41,2),(43,1,104),(44,11,104),(45,21,104);

INSERT INTO `roles` VALUES (1,'login','access','web site user'),(2,'read','permission','The Permission that allows viewing content.'),(3,'write','permission','The Permission that allows creating content.'),(4,'edit','permission','The Permission that allows modifying content.'),(5,'upload','permission','The Permission that allows uploading file'),(6,'delete','permission','The Permission that allows deleting content and files.'),(7,'publish','permission','The Permission that allows publishing content.'),(8,'admin','permission','basic administrative user'),(9,'developer','permission','The permission that allows to configure elements of the web site'),(100,'users','sections','Manage Users'),(101,'pages','sections','Manage site page structure'),(102,'redirects','sections','Manage site Redirects'),(103,'menus','sections','Manage SIte Menus'),(104,'dashboard','sections','Manage Site wide tools');

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES(1, 1);

INSERT INTO `templates` (`id`, `name`, `description`, `type`, `parameters`) VALUES
(1, 'Default Shell', 'Outer template of site', 'shell', 'public/templates/shell_default'),
(2, 'Default Layout', 'Default layout inner view. Includes breadcrumbs at top and two column layout.  Right column is a menu of pages in given section. Left column loads the page template', 'layout', 'public/templates/layout_default'),
(3, 'Open Layout', 'Inner layout view with no HTML, simply passes through to the given page template''s view.', 'layout', 'public/templates/layout_open'),
(4, 'Default Page', 'Default page template, using the default controller to load the default page template.', 'page', '{"available":1}'),
(5, 'Homepage', 'Homepage template, using the default controller but loading a custom page template.', 'page', '{"page":"public/pages/homepage","layout":3,"available":0}'),
(6, 'Search Page', 'Page for searching the site.  Uses custom controller.  Controller defines which "layout" and "page" templates to use.', 'page', '{"controller":"custom","controller_action":"search","dynamic_uri":0,"available":0}'),
(7, 'SitemapXML', 'Page for Search Engines', 'page', '{"layout":"3","available":"1","controller":"custom","controller_action":"action_sitemapXML"}'),
(8, 'Sitemap', 'Page That list all the pages in the site that have sitemap enabled', 'page', '{"layout":"2","available":"1","controller":"custom","controller_action":"action_sitemap"}');

INSERT INTO `template_content_blocks` (`template_id`, `content_block_id`, `required_edit_role`) VALUES
(4, 1, 0),
(4, 2, 0),
(4, 3, 0),
(4, 4, 0),
(4, 5, 0),
(5, 1, 0),
(5, 2, 0),
(5, 3, 0),
(5, 4, 0),
(5, 5, 0),
(6, 3, 0),
(8, 5, 0),
(8, 1, 0),
(8, 2, 0),
(8, 3, 0);

INSERT INTO `users_groups` VALUES (1,1,1);

INSERT INTO `pages` VALUES (1,0,5,'','Homepage',0,'',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00',1,1),
                                            (2,0,6,'search','Search',1,'',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0),
                                            (3,0,7,'sitemap.xml','SitemapXML',2,'',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0),
                                            (4,0,8,'sitemap','Sitemap',3,'',0,1,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0);


INSERT INTO `users` VALUES (1,'admin@thepitagroup.com','9fbf2fbfe2402b9a9e5ea4bc8d1ec880213de59fe90c344e3a23f6ee57a699e6','admin','thepitagroup',0000000076,1420828653,'2014-11-11 11:05:49',0,2,'admin@thepitagroup.com','{\"phone\":\"123 987 6789\",\"title\":\"dr\",\"department\":\"science\",\"unit\":\"unit1\",\"biography\":\"<p>sblah Blah<\\/p>\\n\"}');

SET FOREIGN_KEY_CHECKS=1;#--enable foreign key checks
