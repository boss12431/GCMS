DROP TABLE IF EXISTS `{prefix}_board_q`;
CREATE TABLE `{prefix}_board_q` ( `id` int(11) NOT NULL AUTO_INCREMENT, `module_id` int(11) NOT NULL, `category_id` int(11) NOT NULL, `sender` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `member_id` int(11) NOT NULL, `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `create_date` int(11) unsigned NOT NULL, `last_update` int(11) NOT NULL, `visited` smallint(6) DEFAULT NULL, `comments` smallint(3) DEFAULT NULL, `comment_id` int(11) DEFAULT NULL, `commentator` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, `commentator_id` int(11) DEFAULT NULL, `comment_date` int(11) DEFAULT NULL, `picture` text COLLATE utf8_unicode_ci DEFAULT NULL, `pictureW` int(11) DEFAULT NULL, `pictureH` int(11) DEFAULT NULL, `hassubpic` smallint(3) DEFAULT NULL, `can_reply` tinyint(1) unsigned NOT NULL DEFAULT 1, `published` tinyint(1) unsigned NOT NULL DEFAULT 1, `pin` tinyint(1) unsigned NOT NULL DEFAULT 0, `locked` tinyint(1) unsigned NOT NULL DEFAULT 0, `related` varchar(149) COLLATE utf8_unicode_ci DEFAULT NULL, `topic` varchar(64) COLLATE utf8_unicode_ci NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`id`), FULLTEXT KEY `topic` (`topic`), FULLTEXT KEY `detail` (`detail`), FULLTEXT KEY `topic_2` (`topic`), FULLTEXT KEY `detail_2` (`detail`), FULLTEXT KEY `topic_3` (`topic`), FULLTEXT KEY `detail_3` (`detail`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_board_r`;
CREATE TABLE `{prefix}_board_r` ( `id` int(11) NOT NULL AUTO_INCREMENT, `module_id` int(11) NOT NULL, `index_id` int(11) NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, `sender` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `member_id` int(11) NOT NULL, `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `last_update` int(11) NOT NULL, `picture` text COLLATE utf8_unicode_ci DEFAULT NULL, `pictureW` int(11) DEFAULT NULL, `pictureH` int(11) DEFAULT NULL, PRIMARY KEY (`id`), FULLTEXT KEY `detail` (`detail`), FULLTEXT KEY `detail_2` (`detail`), FULLTEXT KEY `detail_3` (`detail`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_category`;
CREATE TABLE `{prefix}_category` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `category_id` int(11) unsigned NOT NULL, `group_id` int(11) unsigned NOT NULL DEFAULT 0, `config` text COLLATE utf8_unicode_ci DEFAULT NULL, `c1` int(11) unsigned DEFAULT NULL, `c2` int(11) unsigned DEFAULT NULL, `topic` text COLLATE utf8_unicode_ci NOT NULL, `detail` text COLLATE utf8_unicode_ci DEFAULT NULL, `icon` text COLLATE utf8_unicode_ci DEFAULT NULL, `published` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1', PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_comment`;
CREATE TABLE `{prefix}_comment` ( `id` int(11) NOT NULL AUTO_INCREMENT, `module_id` int(11) NOT NULL, `index_id` int(11) NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, `sender` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `member_id` int(11) NOT NULL, `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `last_update` int(11) NOT NULL, `picture` text COLLATE utf8_unicode_ci DEFAULT NULL, `pictureW` int(11) DEFAULT NULL, `pictureH` int(11) DEFAULT NULL, PRIMARY KEY (`id`), FULLTEXT KEY `detail` (`detail`), FULLTEXT KEY `detail_2` (`detail`), FULLTEXT KEY `detail_3` (`detail`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_counter`;
CREATE TABLE `{prefix}_counter` ( `id` int(11) NOT NULL AUTO_INCREMENT, `counter` int(11) NOT NULL, `visited` int(11) NOT NULL, `pages_view` int(11) NOT NULL, `time` int(11) NOT NULL, `date` date NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_download`;
CREATE TABLE `{prefix}_download` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `category_id` smallint(5) unsigned DEFAULT NULL, `member_id` int(11) unsigned NOT NULL, `detail` varchar(200) COLLATE utf8_unicode_ci NOT NULL, `last_update` int(11) unsigned NOT NULL, `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `ext` varchar(5) COLLATE utf8_unicode_ci NOT NULL, `size` int(11) unsigned NOT NULL, `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `downloads` int(11) unsigned NOT NULL, `reciever` text COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_edocument`;
CREATE TABLE `{prefix}_edocument` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `sender_id` int(11) unsigned NOT NULL, `reciever` text COLLATE utf8_unicode_ci NOT NULL, `last_update` int(11) unsigned NOT NULL, `downloads` int(11) unsigned NOT NULL, `document_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, `topic` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `ext` varchar(4) COLLATE utf8_unicode_ci NOT NULL, `size` double unsigned NOT NULL, `file` varchar(15) COLLATE utf8_unicode_ci NOT NULL, `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_edocument_download`;
CREATE TABLE `{prefix}_edocument_download` ( `id` int(10) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(10) unsigned NOT NULL, `document_id` int(10) unsigned NOT NULL, `member_id` int(10) unsigned NOT NULL, `downloads` int(10) unsigned NOT NULL, `last_update` int(10) unsigned NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=latin1
DROP TABLE IF EXISTS `{prefix}_emailtemplate`;
CREATE TABLE `{prefix}_emailtemplate` ( `id` int(10) unsigned NOT NULL AUTO_INCREMENT, `module` varchar(20) COLLATE utf8_unicode_ci NOT NULL, `email_id` int(10) unsigned NOT NULL, `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL, `from_email` text COLLATE utf8_unicode_ci NOT NULL, `copy_to` text COLLATE utf8_unicode_ci NOT NULL, `name` text COLLATE utf8_unicode_ci NOT NULL, `subject` text COLLATE utf8_unicode_ci NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, `last_update` int(11) unsigned NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_eventcalendar`;
CREATE TABLE `{prefix}_eventcalendar` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `topic` varchar(64) COLLATE utf8_unicode_ci NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, `description` varchar(149) COLLATE utf8_unicode_ci NOT NULL, `keywords` varchar(149) COLLATE utf8_unicode_ci NOT NULL, `member_id` int(11) unsigned NOT NULL, `create_date` datetime DEFAULT NULL, `last_update` int(11) unsigned NOT NULL, `begin_date` datetime DEFAULT NULL, `end_date` datetime DEFAULT NULL, `color` varchar(11) COLLATE utf8_unicode_ci NOT NULL, `published` tinyint(1) unsigned NOT NULL, `published_date` date DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_gallery`;
CREATE TABLE `{prefix}_gallery` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `album_id` int(11) unsigned NOT NULL, `image` varchar(15) COLLATE utf8_unicode_ci NOT NULL, `last_update` int(11) unsigned NOT NULL, `count` int(11) unsigned NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_gallery_album`;
CREATE TABLE `{prefix}_gallery_album` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `topic` varchar(64) COLLATE utf8_unicode_ci NOT NULL, `detail` varchar(200) COLLATE utf8_unicode_ci NOT NULL, `last_update` int(11) unsigned NOT NULL, `count` int(11) unsigned NOT NULL, `visited` int(11) unsigned NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_index`;
CREATE TABLE `{prefix}_index` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `index` tinyint(1) unsigned NOT NULL DEFAULT 0, `module_id` int(11) unsigned NOT NULL, `category_id` int(11) unsigned DEFAULT NULL, `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '', `sender` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '', `member_id` int(11) unsigned NOT NULL, `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `create_date` int(11) unsigned NOT NULL, `last_update` int(11) unsigned NOT NULL, `visited` int(11) unsigned NOT NULL DEFAULT 0, `visited_today` int(11) unsigned NOT NULL DEFAULT 0, `comments` smallint(3) unsigned NOT NULL DEFAULT 0, `comment_id` int(11) unsigned NOT NULL DEFAULT 0, `commentator` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, `commentator_id` int(11) unsigned NOT NULL DEFAULT 0, `comment_date` int(11) DEFAULT NULL, `picture` text COLLATE utf8_unicode_ci DEFAULT NULL, `pictureW` int(11) DEFAULT NULL, `pictureH` int(11) DEFAULT NULL, `hassubpic` smallint(3) DEFAULT NULL, `can_reply` tinyint(1) unsigned NOT NULL DEFAULT 0, `show_news` text COLLATE utf8_unicode_ci DEFAULT NULL, `published` tinyint(1) unsigned NOT NULL DEFAULT 1, `pin` tinyint(1) unsigned NOT NULL DEFAULT 0, `locked` tinyint(1) unsigned NOT NULL DEFAULT 0, `published_date` date NOT NULL, `alias` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL, `page` varchar(20) COLLATE utf8_unicode_ci DEFAULT '', PRIMARY KEY (`id`,`module_id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_index_detail`;
CREATE TABLE `{prefix}_index_detail` ( `id` int(11) unsigned NOT NULL, `module_id` int(11) unsigned NOT NULL, `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL, `topic` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `relate` varchar(255) COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`id`,`module_id`,`language`), FULLTEXT KEY `topic` (`topic`), FULLTEXT KEY `detail` (`detail`), FULLTEXT KEY `topic_2` (`topic`), FULLTEXT KEY `detail_2` (`detail`), FULLTEXT KEY `topic_3` (`topic`), FULLTEXT KEY `detail_3` (`detail`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_language`;
CREATE TABLE `{prefix}_language` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `key` text COLLATE utf8_unicode_ci NOT NULL, `ja` text COLLATE utf8_unicode_ci DEFAULT NULL, `th` text COLLATE utf8_unicode_ci DEFAULT NULL, `en` text COLLATE utf8_unicode_ci DEFAULT NULL, `owner` varchar(20) COLLATE utf8_unicode_ci NOT NULL, `type` varchar(5) COLLATE utf8_unicode_ci NOT NULL, `js` tinyint(1) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_logs`;
CREATE TABLE `{prefix}_logs` ( `time` datetime NOT NULL, `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL, `session_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL, `referer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL, `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL , `url` TEXT COLLATE utf8_unicode_ci DEFAULT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_menus`;
CREATE TABLE `{prefix}_menus` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `index_id` int(11) unsigned NOT NULL, `parent` varchar(20) COLLATE utf8_unicode_ci NOT NULL, `level` smallint(2) unsigned NOT NULL, `language` varchar(2) COLLATE utf8_unicode_ci NOT NULL, `menu_text` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `menu_tooltip` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `accesskey` varchar(1) COLLATE utf8_unicode_ci NOT NULL, `menu_order` int(11) unsigned NOT NULL, `menu_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `menu_target` varchar(6) COLLATE utf8_unicode_ci NOT NULL, `alias` varchar(20) COLLATE utf8_unicode_ci NOT NULL, `published` enum('0','1','2','3') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1', PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_modules`;
CREATE TABLE `{prefix}_modules` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `owner` varchar(20) COLLATE utf8_unicode_ci NOT NULL, `module` varchar(64) COLLATE utf8_unicode_ci NOT NULL, `config` text COLLATE utf8_unicode_ci DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_personnel`;
CREATE TABLE `{prefix}_personnel` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `category_id` int(11) unsigned NOT NULL, `name` varchar(50) NOT NULL, `position` varchar(100) NOT NULL, `detail` varchar(255) NOT NULL, `address` varchar(255) NOT NULL, `phone` varchar(20) NOT NULL, `email` varchar(255) NOT NULL, `picture` varchar(15) NOT NULL, `order` tinyint(2) unsigned NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS `{prefix}_portfolio`;
CREATE TABLE `{prefix}_portfolio` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `detail` text COLLATE utf8_unicode_ci NOT NULL, `create_date` int(11) NOT NULL, `image` varchar(15) COLLATE utf8_unicode_ci NOT NULL, `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `published` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1', `visited` int(11) unsigned NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_tags`;
CREATE TABLE `{prefix}_tags` ( `id` int(11) NOT NULL AUTO_INCREMENT, `tag` text COLLATE utf8_unicode_ci NOT NULL, `count` int(11) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_textlink`;
CREATE TABLE `{prefix}_textlink` ( `id` int(11) NOT NULL AUTO_INCREMENT, `text` text COLLATE utf8_unicode_ci NOT NULL, `url` text COLLATE utf8_unicode_ci NOT NULL, `publish_start` int(11) NOT NULL, `publish_end` int(11) NOT NULL, `logo` text COLLATE utf8_unicode_ci NOT NULL, `width` int(11) NOT NULL, `height` int(11) NOT NULL, `type` varchar(11) COLLATE utf8_unicode_ci NOT NULL, `name` varchar(11) COLLATE utf8_unicode_ci NOT NULL, `published` smallint(1) NOT NULL DEFAULT 1, `link_order` smallint(2) NOT NULL, `last_preview` int(11) unsigned DEFAULT NULL, `description` varchar(49) COLLATE utf8_unicode_ci NOT NULL, `template` text COLLATE utf8_unicode_ci DEFAULT NULL, `target` varchar(6) COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_user`;
CREATE TABLE `{prefix}_user` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `token` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, `name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL, `displayname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, `sex` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL, `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL, `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL, `idcard` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL, `birthday` date DEFAULT NULL, `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL, `company` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL, `icon` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL, `create_date` int(11) unsigned NOT NULL, `visited` int(11) unsigned DEFAULT NULL, `lastvisited` int(11) unsigned DEFAULT NULL, `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, `ban` int(11) DEFAULT NULL, `point` int(11) DEFAULT NULL, `post` int(11) unsigned DEFAULT NULL, `reply` int(11) unsigned DEFAULT NULL, `address1` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL, `address2` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL, `provinceID` smallint(3) unsigned DEFAULT NULL, `province` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL, `zipcode` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL, `country` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL, `phone1` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL, `phone2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL, `activatecode` varchar(32) COLLATE utf8_unicode_ci NOT NULL, `status` tinyint(1) unsigned NOT NULL, `social` tinyint(4) NOT NULL, `session_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL, `active` tinyint(1) NOT NULL DEFAULT 0, `permission` text COLLATE utf8_unicode_ci, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_useronline`;
CREATE TABLE `{prefix}_useronline` ( `member_id` int(11) NOT NULL, `displayname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, `session` varchar(32) COLLATE utf8_unicode_ci NOT NULL, `time` int(11) NOT NULL, `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, PRIMARY KEY (`session`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
DROP TABLE IF EXISTS `{prefix}_video`;
CREATE TABLE `{prefix}_video` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `module_id` int(11) unsigned NOT NULL, `youtube` varchar(11) COLLATE utf8_unicode_ci NOT NULL, `topic` text COLLATE utf8_unicode_ci NOT NULL, `description` text COLLATE utf8_unicode_ci NOT NULL, `views` int(11) unsigned NOT NULL, `last_update` int(11) unsigned NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('member','1','th','','','ตอบรับการสมัครสมาชิกใหม่ (ยืนยันสมาชิก)','ตอบรับการสมัครสมาชิก %WEBTITLE%','<div style="padding: 10px;  background-color: rgb(247, 247, 247);">\r\n<table style=" border-collapse: collapse;">\r\n	<tbody>\r\n		<tr>\r\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">ยินดีต้อนรับสมาชิกใหม่ %WEBTITLE%</th>\r\n		</tr>\r\n		<tr>\r\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">ขอขอบคุณสำหรับการลงทะเบียนกับเรา บัญชีใหม่ของคุณได้รับการติดตั้งเรียบร้อยแล้วและคุณสามารถเข้าระบบได้โดยใช้รายละเอียดด้านล่างนี้<br>\r\n			<br>\r\n			ที่อยู่อีเมล : <strong>%EMAIL%</strong><br>\r\n			รหัสผ่าน&nbsp; : <strong>%PASSWORD%</strong><br>\r\n			<br>\r\n			ก่อนอื่นคุณต้องกลับไปยืนยันการสมัครสมาชิกที่ <a href="%WEBURL%index.php?module=activate&amp;id=%ID%" rel="nofollow">%WEBURL%index.php?module=activate&amp;id=%ID%</a></td>\r\n		</tr>\r\n		<tr>\r\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">ด้วยความขอบคุณ <a href="mailto:%ADMINEMAIL%" rel="nofollow">เว็บมาสเตอร์</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>','1473600064');
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('member','1','en','','','Client Signup (Activate)','Welcome %WEBTITLE%','<div style="padding: 10px; background-color: rgb(247, 247, 247);">\r\n<table style="border-collapse: collapse;">\r\n	<tbody>\r\n		<tr>\r\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">Welcome %WEBTITLE%</th>\r\n		</tr>\r\n		<tr>\r\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">Thank you for signing up with us. Your new account has been setup and you can now login to our client area using the details below.<br>\r\n			<br>\r\n			E-mail : <strong>%EMAIL%</strong><br>\r\n			Password : <strong>%PASSWORD%</strong><br>\r\n			<br>\r\n			Please visit and activate users&nbsp;at <a href="%WEBURL%index.php?module=activate&amp;id=%ID%" rel="nofollow">%WEBURL%index.php?module=activate&amp;id=%ID%</a></td>\r\n		</tr>\r\n		<tr>\r\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">With thank <a href="mailto:%ADMINEMAIL%" rel="nofollow">Webmaster</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>','1378257446');
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('member','2','th','','','ตอบรับการสมัครสมาชิกใหม่ (ไม่ต้องยืนยันสมาชิก)','ตอบรับการสมัครสมาชิก %WEBTITLE%','<div style="padding: 10px; background-color: rgb(247, 247, 247);">\n<table style="border-collapse: collapse;">\n	<tbody>\n		<tr>\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">ยินดีต้อนรับสมาชิกใหม่ %WEBTITLE%</th>\n		</tr>\n		<tr>\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">ขอขอบคุณสำหรับการลงทะเบียนกับเรา บัญชีใหม่ของคุณได้รับการติดตั้งเรียบร้อยแล้วและคุณสามารถเข้าระบบได้โดยใช้รายละเอียดด้านล่างนี้<br />\n			<br />\n			ที่อยู่อีเมล : <strong>%EMAIL%</strong><br />\n			รหัสผ่าน&nbsp; : <strong>%PASSWORD%</strong><br />\n			<br />\n			คุณสามารถกลับไปเข้าระบบได้ที่ <a href="%WEBURL%">%WEBURL%</a></td>\n		</tr>\n		<tr>\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">ด้วยความขอบคุณ <a href="mailto:%ADMINEMAIL%">เว็บมาสเตอร์</a></td>\n		</tr>\n	</tbody>\n</table>\n</div>\n','1473599703');
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('member','2','en','','','Client Signup (no Activate)','Welcome %WEBTITLE%','<div style="padding: 10px; background-color: rgb(247, 247, 247);">\n<table style="border-collapse: collapse;">\n	<tbody>\n		<tr>\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">Welcome %WEBTITLE%</th>\n		</tr>\n		<tr>\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">Thank you for signing up with us. Your new account has been setup and you can now login to our client area using the details below.<br />\n			<br />\n			E-mail: <strong>%EMAIL%</strong><br />\n			Password : <strong>%PASSWORD%</strong><br />\n			<br />\n			To login, visit <a href="%WEBURL%">%WEBURL%</a></td>\n		</tr>\n		<tr>\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">With thank <a href="mailto:%ADMINEMAIL%">Webmaster</a></td>\n		</tr>\n	</tbody>\n</table>\n</div>\n','1473599844');
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('member','3','th','','','ขอรหัสผ่านใหม่','รหัสผ่านของคุณใน %WEBTITLE%','<div style="padding: 10px;  background-color: rgb(247, 247, 247);">\r\n<table style=" border-collapse: collapse;">\r\n	<tbody>\r\n		<tr>\r\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">รหัสผ่านของคุณใน %WEBTITLE%</th>\r\n		</tr>\r\n		<tr>\r\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">รหัสผ่านใหม่ของคุณถูกส่งมาจากระบบอัตโนมัติ เมื่อ %TIME%<br />\r\n			ไม่ว่าคุณจะได้ทำการขอรหัสผ่านใหม่หรือไม่ก็ตาม โปรดใช้รหัสผ่านใหม่นี้กับบัญชีของคุณ<br />\r\n			(ถ้าคุณไม่ได้ดำเนินการนี้ด้วยตัวเอง อาจมีผู้พยายามเข้าไปเปลี่ยนแปลงข้อมูลส่วนตัวของคุณ)<br />\r\n			<br />\r\n			ที่อยู่อีเมล : <strong>%EMAIL%</strong><br />\r\n			รหัสผ่าน : <strong>%PASSWORD%</strong><br />\r\n			<br />\r\n			คุณสามารถกลับไปเข้าระบบและแก้ไขข้อมูลส่วนตัวของคุณใหม่ได้ที่ <a href="%WEBURL%">%WEBURL%</a></td>\r\n		</tr>\r\n		<tr>\r\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">ด้วยความขอบคุณ <a href="mailto:%ADMINEMAIL%">เว็บมาสเตอร์</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>\r\n','1473599666');
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('member','3','en','','','Request new password','Your password in %WEBTITLE%','<div style="padding: 10px; background-color: rgb(247, 247, 247);">\r\n<table style="border-collapse: collapse;">\r\n	<tbody>\r\n		<tr>\r\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">Your new password for use in %WEBTITLE%</th>\r\n		</tr>\r\n		<tr>\r\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">Your new password is sent automatically on %TIME%<br />\r\n			Whether you do this or not. Use this new password for your account<br />\r\n			(If you do not do this yourself. May have tried to change your personal information)<br />\r\n			<br />\r\n			E-mail : <strong>%EMAIL%</strong><br />\r\n			Password : <strong>%PASSWORD%</strong><br />\r\n			<br />\r\n			You can return to the login and edit your profile at <a href="%WEBURL%">%WEBURL%</a></td>\r\n		</tr>\r\n		<tr>\r\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">With thanks <a href="mailto:%ADMINEMAIL%">Webmaster</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>\r\n','1473599676');
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('edocument','1','th','','','แจ้งการส่งเอกสาร','มีเอกสารส่งถึงคุณใน %WEBTITLE%','<div style="padding: 10px; background-color: rgb(247, 247, 247);">\r\n<table style="border-collapse: collapse;">\r\n	<tbody>\r\n		<tr>\r\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">มีเอกสารส่งถึงคุณใน %WEBTITLE%</th>\r\n		</tr>\r\n		<tr>\r\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">เรียนคุณ %NAME%<br>\r\n			<br>\r\n			มีเอกสารใหม่ส่งถึงคุณ เมื่อ %TIME%<br>\r\n			<br>\r\n			คุณสามารถตรวจสอบรายการเอกสารของคุณได้ที่ <a href="%URL%" rel="nofollow">%URL%</a> (คุณอาจต้องเข้าระบบก่อน)</td>\r\n		</tr>\r\n		<tr>\r\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">ด้วยความขอบคุณ <a href="mailto:%ADMINEMAIL%" rel="nofollow">เว็บมาสเตอร์</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>','1377480437');
INSERT INTO `{prefix}_emailtemplate` (`module`, `email_id`, `language`, `from_email`, `copy_to`, `name`, `subject`, `detail`, `last_update`) VALUES ('edocument','1','en','','','Shipping documents','A document sent to you in %WEBTITLE%','<div style="padding: 10px; background-color: rgb(247, 247, 247);">\r\n<table style="border-collapse: collapse;">\r\n	<tbody>\r\n		<tr>\r\n			<th style="border-width: 1px; border-style: none solid; border-color: rgb(59, 89, 152); padding: 5px; text-align: left; color: rgb(255, 255, 255); font-family: tahoma; font-size: 9pt; background-color: rgb(59, 89, 152);">A document sent to you in %WEBTITLE%</th>\r\n		</tr>\r\n		<tr>\r\n			<td style="border-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204) rgb(204, 204, 204) rgb(59, 89, 152); padding: 15px; line-height: 1.8em; font-family: tahoma; font-size: 9pt;">dear %NAME%<br>\r\n			<br>\r\n			The new document is sent to you on %TIME%<br>\r\n			<br>\r\n			You can check your document at. <a href="%URL%" rel="nofollow">%URL%</a> (You may need to login first)</td>\r\n		</tr>\r\n		<tr>\r\n			<td style="padding: 15px; color: rgb(153, 153, 153); font-family: tahoma; font-size: 8pt;">With thanks <a href="mailto:%ADMINEMAIL%" rel="nofollow">Webmaster</a></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>','1473601020');
