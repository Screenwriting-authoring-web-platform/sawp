-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2014 at 02:13 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sawp`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('addScreenplay', 2, 'Permission to add a new screenplay to the team', 'isArtistOrBetter', NULL, 1409570924, 1409570924),
('admin', 1, NULL, NULL, NULL, 1403871003, 1403871003),
('AdminCreateTeam', 2, 'Can create teams with another owner', NULL, NULL, 1403871003, 1403871003),
('adminDeleteTeam', 2, 'Permission to delete a team', NULL, NULL, 1409572414, 1409572414),
('adminDeleteUser', 2, 'Permission to delete a user', NULL, NULL, 1409572388, 1409572388),
('AdminEditTeam', 2, 'Permission to edit a team', NULL, NULL, 1409572388, 1409572388),
('AdminSettings', 2, 'Permission to edit the page settings', NULL, NULL, 1409572387, 1409572387),
('AdminUserAdd', 2, 'Permission to add a user', NULL, NULL, 1409572388, 1409572388),
('AdminUserEdit', 2, 'Permission to edit a user', NULL, NULL, 1409572388, 1409572388),
('createComment', 2, 'Permission to create comments of a screenplay', 'isObserverOrBetter', NULL, 1409568094, 1409568094),
('createTeam', 2, 'create a new Team', NULL, NULL, 1403184166, 1403184166),
('deleteComment', 2, 'Permission to delete Comments and Threads', 'isArtistOrBetter', NULL, 1409565765, 1409565765),
('deleteTeam', 2, 'Permission to delete the team', 'isDirector', NULL, 1409565764, 1409565764),
('deleteScreenplay', 2, 'Permission to delete a screenplay', 'isDirector', NULL, 1409570869, 1409570869),
('editProfile', 2, 'Permission to edit the profile', NULL, NULL, 1409572782, 1409572782),
('editTeam', 2, 'Permission to edit the details Page of a team', 'isDirector', NULL, 1409565764, 1409565764),
('exportScreenplay', 2, 'Permission to export the screenplay', 'isArtistOrBetter', NULL, 1409570924, 1409570924),
('generateBreakdown', 2, 'Permission to generate breakdown reports for the screenplay', 'isArtistOrBetter', NULL, 1409570924, 1409570924),
('getScreenplayContent', 2, 'Permission to get content of a screenplay', 'isObserverOrBetter', NULL, 1409568093, 1409568093),
('getScreenplayTree', 2, 'Permission to get the tagtree of a screenplay', 'isObserverOrBetter', NULL, 1409568093, 1409568093),
('importFile', 2, 'Permission to Import Files as new Screenplays', 'isArtistOrBetter', NULL, 1409565765, 1409565765),
('inviteArtist', 2, 'Permission to invite new artists to the team', 'isDirector', NULL, 1409565764, 1409565764),
('inviteObserver', 2, 'Permission to invite new obersvers to the team', 'isDirector', NULL, 1409565764, 1409565764),
('kickUserfromTeam', 2, 'Permission to kick an collaborator from the team', 'isDirector', NULL, 1409565764, 1409565764),
('leaveTeam', 2, 'Permission to leave the team', 'isObserverOrBetter', NULL, 1409565764, 1409565764),
('registerInPublicTeam', 2, 'Permission to register yourself in an public team', NULL, NULL, 1409565764, 1409565764),
('saveScreenplayContent', 2, 'Permission to save new content of a screenplay', 'isArtistOrBetter', NULL, 1409568093, 1409568093),
('saveScreenplayTree', 2, 'Permission to save a new tagtree of a screenplay', 'isArtistOrBetter', NULL, 1409568093, 1409568093),
('setUsersTeamRole', 2, 'Permission to set the role of an collaborator in the team', 'isDirector', NULL, 1409565764, 1409565764),
('showAdminIndex', 2, 'Permission to show the admin overview page', NULL, NULL, 1409572387, 1409572387),
('showEditor', 2, 'Permission to show the editor of a screenplay', 'isObserverOrBetter', NULL, 1409570869, 1409570869),
('showHistory', 2, 'Permission to show the screenplay history and revert', 'isArtistOrBetter', NULL, 1409570923, 1409570923),
('showProfile', 2, 'Permission to show a profile', NULL, NULL, 1409572782, 1409572782),
('showTeamIndex', 2, 'show a list of teams the user is associated', NULL, NULL, 1403184166, 1403184166),
('showTeamlist', 2, 'Permission to show the teamlist', NULL, NULL, 1409572414, 1409572414),
('showUserlist', 2, 'Permission to show the userlist', NULL, NULL, 1409572388, 1409572388),
('user', 1, NULL, NULL, NULL, 1403184166, 1403184166),
('viewComment', 2, 'Permission to view comments of a screenplay', 'isObserverOrBetter', NULL, 1409568094, 1409568094),
('viewTeam', 2, 'Permission to see the details Page of a team', 'isObserverOrBetter', NULL, 1409565764, 1409565764);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('user', 'addScreenplay'),
('admin', 'AdminCreateTeam'),
('admin', 'adminDeleteTeam'),
('admin', 'adminDeleteUser'),
('admin', 'AdminEditTeam'),
('admin', 'AdminSettings'),
('admin', 'AdminUserAdd'),
('admin', 'AdminUserEdit'),
('user', 'createComment'),
('user', 'createTeam'),
('user', 'deleteComment'),
('user', 'deleteTeam'),
('user', 'deleteScreenplay'),
('user', 'editProfile'),
('user', 'editTeam'),
('user', 'exportScreenplay'),
('user', 'generateBreakdown'),
('user', 'getScreenplayContent'),
('user', 'getScreenplayTree'),
('user', 'importFile'),
('user', 'inviteArtist'),
('user', 'inviteObserver'),
('user', 'kickUserfromTeam'),
('user', 'leaveTeam'),
('user', 'registerInPublicTeam'),
('user', 'saveScreenplayContent'),
('user', 'saveScreenplayTree'),
('user', 'setUsersTeamRole'),
('admin', 'showAdminIndex'),
('user', 'showEditor'),
('user', 'showHistory'),
('user', 'showProfile'),
('user', 'showTeamIndex'),
('admin', 'showTeamlist'),
('admin', 'showUserlist'),
('admin', 'user'),
('user', 'viewComment'),
('user', 'viewTeam');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_rule`
--

INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('isArtist', 'O:19:"app\\rbac\\ArtistRule":3:{s:4:"name";s:8:"isArtist";s:9:"createdAt";i:1409565472;s:9:"updatedAt";i:1409565472;}', 1409565472, 1409565472),
('isArtistOrBetter', 'O:27:"app\\rbac\\ArtistOrBetterRule":3:{s:4:"name";s:16:"isArtistorBetter";s:9:"createdAt";i:1409565659;s:9:"updatedAt";i:1409565659;}', 409565659, 1409565659),
('isDirector', 'O:21:"app\\rbac\\DirectorRule":3:{s:4:"name";s:10:"isDirector";s:9:"createdAt";i:1409565715;s:9:"updatedAt";i:1409565715;}', 1409565715, 1409565715),
('isObserver', 'O:21:"app\\rbac\\ObserverRule":3:{s:4:"name";s:10:"isObserver";s:9:"createdAt";i:1409565565;s:9:"updatedAt";i:1409565565;}', 1409565565, 1409565565),
('isObserverOrBetter', 'O:29:"app\\rbac\\ObserverOrBetterRule":3:{s:4:"name";s:18:"isObserverorBetter";s:9:"createdAt";i:1409565597;s:9:"updatedAt";i:1409565597;}', 409565597, 1409565597);


-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `creationtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `defaultCategories` text COLLATE utf8_unicode_ci,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
CREATE TABLE IF NOT EXISTS `team_user` (
  `teamid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `rights` int(11) NOT NULL COMMENT '0 = Team Founder',
  PRIMARY KEY (`teamid`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `screenplay`
--

DROP TABLE IF EXISTS `screenplay`;
CREATE TABLE `screenplay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `teamid` int(11) NOT NULL,
  `creationtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `locktime` timestamp NULL DEFAULT NULL,
  `lockuser` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `teamid` (`teamid`),
  KEY `lockuser` (`lockuser`),
  CONSTRAINT `screenplay_ibfk_2` FOREIGN KEY (`lockuser`) REFERENCES `user` (`id`),
  CONSTRAINT `screenplay_ibfk_1` FOREIGN KEY (`teamid`) REFERENCES `team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `screenplay_comment`
--

DROP TABLE IF EXISTS `screenplay_comment`;
CREATE TABLE IF NOT EXISTS `screenplay_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `screenplayId` int(11) NOT NULL,
  `nextId` int(11) DEFAULT NULL,
  `userId` int(11) NOT NULL,
  `creationtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`nextId`,`userId`),
  KEY `userId` (`userId`),
  KEY `screenplayId` (`screenplayId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `screenplay_revision`
--

DROP TABLE IF EXISTS `screenplay_revision`;
CREATE TABLE IF NOT EXISTS `screenplay_revision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `screenplayId` int(11) NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `textId` int(11) NOT NULL,
  `treeId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `textId` (`textId`,`treeId`),
  KEY `screenplayId` (`screenplayId`),
  KEY `treeId` (`treeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `screenplay_text_revision`
--

DROP TABLE IF EXISTS `screenplay_text_revision`;
CREATE TABLE IF NOT EXISTS `screenplay_text_revision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `screenplay_tree_revision`
--

DROP TABLE IF EXISTS `screenplay_tree_revision`;
CREATE TABLE IF NOT EXISTS `screenplay_tree_revision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `key` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `type` int(11) DEFAULT NULL COMMENT '1bool 2int 3string',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`key`, `value`, `type`) VALUES
('activationMailBody', 'Hello {username},\r\n\r\nyou have to activate your new account. To do so click on the link.\r\n{link}', 3),
('activationMailSender', 'test@screenwr.acamar.uberspace.de', 3),
('activationMailSubject', 'Verify your Account', 3),
('emailActivation', 'true', 1),
('frontPage', '<div class="jumbotron">\r\n        <h1>SAWP</h1>\r\n\r\n        <p class="lead">Free/Libre and Open Source web-based platform for screenwriting according with the standards set by television and film industries.</p>\r\n\r\n    </div>\r\n\r\n    <div class="body-content">\r\n\r\n        <div class="row">\r\n            <div class="col-lg-4">\r\n                <h2>Heading</h2>\r\n\r\n                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et\r\n                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip\r\n                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu\r\n                    fugiat nulla pariatur.</p>\r\n            </div>\r\n            <div class="col-lg-4">\r\n                <h2>Heading</h2>\r\n\r\n                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et\r\n                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip\r\n                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu\r\n                    fugiat nulla pariatur.</p>\r\n            </div>\r\n            <div class="col-lg-4">\r\n                <h2>Heading</h2>\r\n\r\n                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et\r\n                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip\r\n                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu\r\n                    fugiat nulla pariatur.</p>\r\n            </div>\r\n        </div>\r\n\r\n    </div>', 3),
('pagehomeurl', '/index.php', 3),
('contactmail', '', 3),
('defaultCategories', '{"expanded":true,"key":"root_1","title":"root","children":[{"expanded":true,"folder":true,"key":"_1","selected":false,"title":"ttt","data":{"color":"#d06b64"},"children":[{"expanded":true,"folder":true,"key":"_2","selected":false,"title":"Characters","tooltip":"click the edit button to edit the categories","children":[{"expanded":false,"folder":true,"key":"_3","selected":false,"title":"good Guys","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_4","selected":false,"title":"bad Guys","tooltip":"click the edit button to edit the categories"}]},{"folder":true,"key":"_5","selected":false,"title":"VFX","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_6","selected":false,"title":"Set","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_7","selected":false,"title":"Props","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_8","selected":false,"title":"Sound","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_9","selected":false,"title":"Music","tooltip":"click the edit button to edit the categories"}]}]}', 3),
('pagetitle', 'SAWP', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `passwordHash` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `mailAddress` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `authKey` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `accessToken` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL COMMENT '0new, 1active/mail verified, 2banned, 3deleted',
  `gravatarMailAddress` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastActive` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`mailAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_mail_token`
--

DROP TABLE IF EXISTS `user_mail_token`;
CREATE TABLE IF NOT EXISTS `user_mail_token` (
  `token` varchar(256) NOT NULL,
  `userid` int(11) NOT NULL,
  `creationtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`token`),
  KEY `userid` (`userid`),
  KEY `userid_2` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `team_user`
--
ALTER TABLE `team_user`
  ADD CONSTRAINT `team_user_ibfk_1` FOREIGN KEY (`teamid`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `team_user_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `user` (`id`);

--
-- Constraints for table `screenplay_comment`
--
ALTER TABLE `screenplay_comment`
  ADD CONSTRAINT `screenplay_comment_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `screenplay_comment_ibfk_3` FOREIGN KEY (`screenplayId`) REFERENCES `screenplay` (`id`),
  ADD CONSTRAINT `screenplay_comment_ibfk_4` FOREIGN KEY (`nextId`) REFERENCES `screenplay_comment` (`id`);

--
-- Constraints for table `screenplay_revision`
--
ALTER TABLE `screenplay_revision`
  ADD CONSTRAINT `screenplay_revision_ibfk_1` FOREIGN KEY (`screenplayId`) REFERENCES `screenplay` (`id`),
  ADD CONSTRAINT `screenplay_revision_ibfk_2` FOREIGN KEY (`textId`) REFERENCES `screenplay_text_revision` (`id`),
  ADD CONSTRAINT `screenplay_revision_ibfk_3` FOREIGN KEY (`treeId`) REFERENCES `screenplay_tree_revision` (`id`);

--
-- Constraints for table `user_mail_token`
--
ALTER TABLE `user_mail_token`
  ADD CONSTRAINT `user_mail_token_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
