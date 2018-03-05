-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-03-05 09:15:05
-- 服务器版本： 5.5.54-log
-- PHP Version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eyutou`
--

-- --------------------------------------------------------

--
-- 表的结构 `ni_10_ads`
--

CREATE TABLE `ni_10_ads` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `type` char(10) NOT NULL DEFAULT 'image',
  `content` longtext,
  `link` varchar(512) DEFAULT NULL,
  `display` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_10_ads`
--

INSERT INTO `ni_10_ads` (`id`, `title`, `type`, `content`, `link`, `display`, `hits`) VALUES
(1, '天猫旗舰店链接', 'image', '/Users/Public/Themes/_7/default/images/button.target2tmall.jpg', 'http://www.tmall.com', 0, 0),
(2, '首页大banner', 'image', '/Users/Public/Themes/_7/default/images/index/poster.jpg', '/productions/', 0, 0),
(3, '首页视频广告', 'video', '/Users/Public/Themes/_7/default/ads.mp4', '/productions/', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_authorities`
--

CREATE TABLE `ni_cloud_authorities` (
  `uid` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `table_name` char(64) NOT NULL,
  `auth_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_filemeta`
--

CREATE TABLE `ni_cloud_filemeta` (
  `ID` char(44) NOT NULL,
  `SRC_ID` int(11) NOT NULL,
  `FOLDER` int(11) NOT NULL,
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `SUFFIX` char(32) DEFAULT NULL,
  `SK_MTIME` datetime NOT NULL,
  `SK_IS_RECYCLED` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_filemeta`
--

INSERT INTO `ni_cloud_filemeta` (`ID`, `SRC_ID`, `FOLDER`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `SUFFIX`, `SK_MTIME`, `SK_IS_RECYCLED`) VALUES
('4b8512215a30cac5346', 9, 3, 'user.jpg', 'image', 66707, 'jpg', '2016-03-23 18:59:35', 0),
('94e4347ccf5c0f24112', 8, 3, 'captain.jpg', 'image', 298702, 'jpg', '2016-03-23 18:59:35', 0),
('ca28525a8b386236136', 4, 3, 'guest.jpg', 'image', 73401, 'jpg', '2016-03-23 18:59:34', 0),
('cc26775220c32188228', 6, 3, 'operator.jpg', 'image', 139485, 'jpg', '2016-03-23 18:59:35', 0),
('d78cf72c9a8f4731217', 5, 3, 'yangram.jpg', 'image', 134581, 'jpg', '2016-03-23 18:59:35', 0),
('defaultpic', 13, 6, 'DefaultPicture.jpg', 'image', 3324, 'jpg', '2018-02-06 09:38:46', 0),
('f7f476c3a12b1f1f130', 7, 3, 'fish.jpg', 'image', 199597, 'jpg', '2016-03-23 18:59:35', 0),
('sampledocument', 1, 2, 'License.doc', 'compressed', 9216, 'zip', '2015-12-23 00:00:00', 0),
('samplemp3audio', 10, 2, 'LaCampanella.mp3', 'audio', 2885616, 'mp3', '2015-12-23 00:00:00', 0),
('sampleoggaudio', 11, 2, 'LaCampanella.ogg', 'audio', 2224551, 'ogg', '2015-12-23 00:00:00', 0),
('samplepicture01', 2, 2, 'trees.jpg', 'image', 370490, 'jpg', '2016-01-01 00:00:00', 0),
('samplepicture02', 3, 2, 'magic.jpg', 'image', 339220, 'jpg', '2015-12-24 00:00:00', 0),
('samplevideo', 12, 2, 'FlyToSpace.mp4', 'video', 14020722, 'mp4', '2015-12-23 00:00:00', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_filesrc`
--

CREATE TABLE `ni_cloud_filesrc` (
  `SID` int(11) NOT NULL,
  `HASH` char(32) DEFAULT NULL,
  `LOCATION` varchar(128) NOT NULL,
  `MIME` char(128) NOT NULL,
  `IMAGE_SIZE` varchar(64) DEFAULT NULL,
  `WIDTH` int(11) NOT NULL DEFAULT '0',
  `HEIGHT` int(11) NOT NULL DEFAULT '0',
  `DURATION` int(11) NOT NULL DEFAULT '0',
  `SK_CTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_filesrc`
--

INSERT INTO `ni_cloud_filesrc` (`SID`, `HASH`, `LOCATION`, `MIME`, `IMAGE_SIZE`, `WIDTH`, `HEIGHT`, `DURATION`, `SK_CTIME`) VALUES
(1, 'c1d6380a9cd5698e8dc361c25346ae53', 'Docs/Smaple/License.doc', 'application/msword', NULL, 0, 0, 0, '2015-12-23 00:00:00'),
(2, '85c0878cd4f2aceb891953264afcbf3e', 'Images/Sample/trees.jpg', 'image/jpeg', 'width=\"800\" height=\"600\"', 800, 600, 0, '2015-12-31 00:00:00'),
(3, '3798a0f35fdc4184dff4c09319ce7093', 'Images/Sample/magic.jpg', 'image/jpeg', 'width=\"960\" height=\"600\"', 960, 600, 0, '2015-12-23 00:00:00'),
(4, 'c4f57c5173781656b5d0a3566ffbc232', 'Images/DefultAvatars/guest.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0, '2016-03-23 18:59:34'),
(5, 'adb9841975686eeae6fd60ce88bf9bdc', 'Images/DefultAvatars/yangram.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0, '2016-03-23 18:59:35'),
(6, '7b36442749de9f5c49fad4dd0e884c9e', 'Images/DefultAvatars/operator.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0, '2016-03-23 18:59:35'),
(7, 'c612a30507b055b6b499b0357ddbb5f6', 'Images/DefultAvatars/fish.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0, '2016-03-23 18:59:35'),
(8, '6323083e588d801d4e8e913d5b55ab54', 'Images/DefultAvatars/captain.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0, '2016-03-23 18:59:35'),
(9, '9bea2f588576bd36a304ea6e230e0ff9', 'Images/DefultAvatars/user.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0, '2016-03-23 18:59:35'),
(10, '0f4e74454fba8cb493ae743539db1816', 'Audios/Sample/LaCampanella.mp3', 'audio/mpeg', NULL, 0, 0, 120, '2015-12-23 00:00:00'),
(11, '7ef4ed4b6ba3744993aa9f6476dc639a', 'Audios/Sample/LaCampanella.ogg', 'audio/ogg', NULL, 0, 0, 120, '2015-12-23 00:00:00'),
(12, 'df7a6b0f2c5d058ea952e00af1bdb414', 'Videos/Sample/FlyToSpace.mp4', 'video/mp4', NULL, 0, 0, 20, '2015-12-23 00:00:00'),
(13, 'd93a85bf078ce8e4f465e2c0e204a615', 'Images/Defaults/Common.jpg', 'image/jpeg', 'width=\"300\" height=\"200\"', 300, 200, 0, '2018-02-06 09:38:46');

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_folders`
--

CREATE TABLE `ni_cloud_folders` (
  `id` int(11) NOT NULL,
  `type` char(7) NOT NULL DEFAULT 'file',
  `tablename` char(64) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `parent` int(11) DEFAULT '6',
  `SK_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `SK_MTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_folders`
--

INSERT INTO `ni_cloud_folders` (`id`, `type`, `tablename`, `name`, `description`, `parent`, `SK_IS_RECYCLED`, `SK_MTIME`) VALUES
(1, 'file', NULL, 'System', NULL, 0, 0, '2015-12-30 00:00:00'),
(2, 'file', NULL, 'Sample', NULL, 1, 0, '2015-12-30 00:00:00'),
(3, 'file', NULL, 'UserAvatars', NULL, 1, 0, '2015-12-30 00:00:00'),
(4, 'file', NULL, 'Applications', NULL, 0, 0, '2016-01-04 09:50:36'),
(5, 'file', NULL, 'UPLOADER', NULL, 4, 0, '2016-01-09 20:08:45'),
(6, 'file', NULL, 'OurDocuments', NULL, 0, 0, '2015-12-31 22:26:33'),
(7, 'news', 'news', '集团新闻', '集团新闻', 0, 0, '2018-01-21 00:00:00'),
(8, 'news', 'news', '行业动态', '行业动态', 0, 0, '2018-01-21 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_album`
--

CREATE TABLE `ni_cloud_schema_album` (
  `ID` int(11) NOT NULL,
  `RANK` tinyint(1) DEFAULT '6',
  `AUTHOR` char(255) NOT NULL,
  `ORIGINATE_FROM` varchar(512) DEFAULT NULL,
  `GENRE` char(128) DEFAULT NULL,
  `THUMB` varchar(512) DEFAULT NULL,
  `ITEMLIST` longtext NOT NULL,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_article`
--

CREATE TABLE `ni_cloud_schema_article` (
  `ID` int(11) NOT NULL,
  `RANK` tinyint(1) DEFAULT '6',
  `AUTHOR` varchar(64) DEFAULT NULL,
  `ORIGINATE_FROM` varchar(512) DEFAULT NULL,
  `REELS` varchar(512) DEFAULT NULL,
  `GENRE` varchar(128) DEFAULT NULL,
  `CONTENT` longtext,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_artwork`
--

CREATE TABLE `ni_cloud_schema_artwork` (
  `ID` int(11) NOT NULL,
  `RANK` tinyint(1) DEFAULT '6',
  `UPLOADER` char(128) DEFAULT NULL,
  `PASSWORD` char(32) DEFAULT NULL,
  `ARTIST` char(128) DEFAULT NULL,
  `CREADATE` datetime NOT NULL,
  `RELEASE` date DEFAULT NULL,
  `GENRE` char(128) DEFAULT NULL,
  `THUMB` varchar(512) NOT NULL,
  `SRC` varchar(512) NOT NULL,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_default`
--

CREATE TABLE `ni_cloud_schema_default` (
  `ID` int(11) NOT NULL,
  `RANK` tinyint(1) DEFAULT '6',
  `GUID` char(64) NOT NULL,
  `NAME` varchar(128) NOT NULL,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_job`
--

CREATE TABLE `ni_cloud_schema_job` (
  `ID` int(11) NOT NULL,
  `EDUCATION` varchar(64) DEFAULT NULL,
  `POSINAME` varchar(256) DEFAULT NULL,
  `SALARY_RANGE` varchar(512) DEFAULT NULL COMMENT '薪资范围',
  `PLACE_WORKING` varchar(512) DEFAULT NULL,
  `IS_FULLTIME` tinyint(1) NOT NULL DEFAULT '1',
  `PROBATION` varchar(256) DEFAULT '0' COMMENT '试用期',
  `POSI_DESC` longtext,
  `SEX` tinyint(1) DEFAULT '0' COMMENT '0为不限，1为男性，2为女性',
  `AGE_RANGE` varchar(512) DEFAULT NULL,
  `YEARS_WORKING` tinyint(3) NOT NULL DEFAULT '0',
  `CERTIFICATE` varchar(2048) DEFAULT NULL COMMENT '证书要求，JSON格式',
  `POSI_REQUIRE` longtext,
  `NUMBER` int(11) NOT NULL DEFAULT '0',
  `CONTACT` varchar(2048) DEFAULT NULL,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_schema_job`
--

INSERT INTO `ni_cloud_schema_job` (`ID`, `EDUCATION`, `POSINAME`, `SALARY_RANGE`, `PLACE_WORKING`, `IS_FULLTIME`, `PROBATION`, `POSI_DESC`, `SEX`, `AGE_RANGE`, `YEARS_WORKING`, `CERTIFICATE`, `POSI_REQUIRE`, `NUMBER`, `CONTACT`, `X_ATTRS`) VALUES
(3, NULL, '前台专员', '', '', 1, '0', '&lt;p&gt;负责收集潜在客户的有效信息； 负责协助中心学员招募，配合公司产品宣传；&lt;/p&gt;\n                                    &lt;p&gt;负责早教中心的行政事务； 负责早教中心的接待工作，如：接听来电、接待来访者；&lt;/p&gt;\n                                    &lt;p&gt;协助处理内/外部的各种事宜，统计出勤率以及客户服务； 玩具和产品的销售&lt;/p&gt;', 0, '', 0, NULL, '&lt;p&gt;专科及以上学历；善于沟通和普通话标准,英文流利，可简单对话；积极乐观，有爱心，喜爱儿&lt;/p&gt;\n                                    &lt;p&gt;童，乐于助人；有服务标准意识；良好的沟通技巧及协调能力；形象好，气质佳&lt;/p&gt;', 0, '', NULL),
(4, NULL, '天猫运营', '', '', 1, '0', '&lt;p&gt;负责收集潜在客户的有效信息； 负责协助中心学员招募，配合公司产品宣传；&lt;/p&gt;\n                                    &lt;p&gt;负责早教中心的行政事务； 负责早教中心的接待工作，如：接听来电、接待来访者；&lt;/p&gt;\n                                    &lt;p&gt;协助处理内/外部的各种事宜，统计出勤率以及客户服务； 玩具和产品的销售&lt;/p&gt;', 0, '', 0, NULL, '&lt;p&gt;专科及以上学历；善于沟通和普通话标准,英文流利，可简单对话；积极乐观，有爱心，喜爱儿&lt;/p&gt;\n                                    &lt;p&gt;童，乐于助人；有服务标准意识；良好的沟通技巧及协调能力；形象好，气质佳&lt;/p&gt;', 0, '', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_news`
--

CREATE TABLE `ni_cloud_schema_news` (
  `ID` int(11) NOT NULL,
  `RANK` tinyint(1) DEFAULT '6',
  `PRIMER` varchar(512) DEFAULT NULL,
  `SUBTITLE` varchar(512) DEFAULT NULL,
  `AUTHOR` char(128) DEFAULT NULL,
  `MARKED` tinyint(1) DEFAULT '0',
  `ORIGINATE_FROM` varchar(512) DEFAULT NULL,
  `PRESS` varchar(512) DEFAULT NULL,
  `PRESS_DATE` date DEFAULT NULL,
  `THUMB` varchar(512) DEFAULT NULL,
  `ABSTRACT` varchar(1024) DEFAULT NULL,
  `CONTENT` longtext,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_schema_news`
--

INSERT INTO `ni_cloud_schema_news` (`ID`, `RANK`, `PRIMER`, `SUBTITLE`, `AUTHOR`, `MARKED`, `ORIGINATE_FROM`, `PRESS`, `PRESS_DATE`, `THUMB`, `ABSTRACT`, `CONTENT`, `X_ATTRS`) VALUES
(1, 6, '', '', NULL, 0, NULL, NULL, NULL, '/Users/Public/Themes/_7/default/images/news/thumb.jpg', NULL, '&lt;img src=&quot;/Users/Public/Themes/_7/default/images/news/figure.jpg&quot;&gt;\n&lt;p&gt;2017年6月16日，以“欢迎回家”为主题的来利洪食品集团2017年全国经销商大会暨新品发布会在广州来利洪集团总部隆重召开，全国数百名经销商家人欢聚一堂，共襄盛会，再续情谊。&lt;/p&gt;\n&lt;p&gt;大会正式开始前，在各厂生产主管的陪同下，经销商家人先后参观了瑞达饼干生产车间、溏心月饼生产车间以及来利洪饼干生产车间，了解近年更新的生产设备和生产技术。参观过程中，一尘不染的环境和先进的设备给大家留下了深刻的印象。&lt;/p&gt;\n&lt;p&gt;午后，会议在激动人心的倒数声中开始。集团创始人、董事长刘启洪在致辞中表示，来利洪创办至今已有35年历史，在座不乏合作时间长达十年以上的老客户，多年来相互合作，相互信任，相互支持，彼此间的情谊早已超越生意伙伴，胜似家人。今天很高兴能够与大家相聚于来利洪的大本营，预祝本次会议获得圆满成功，同时也祝愿各位家人朋友身体健康，事业顺利。&lt;/p&gt;\n&lt;p&gt;会上，集团总经理刘海陶重点介绍了集团旗下品牌溏心月饼的未来发展规划，伴随着品牌战略的调整，下阶段溏心将从“调性升级、形象升级、产品升级”三大方面进行品牌“迭代”，垂直细分市场，从“溏心1.0”跨入“溏心2.0”。此外，刘总揭幕了本次会议发布的主题新品——流心月饼，同时安排了现场试吃环节。流心月饼美味的口感让大家为之惊艳，不少经销商向工作人员要求“再来一碟”。刘总表示，作为溏心的匠心之作，流心月饼拟以品牌拳头产品的定位推向市场，集团将提供“渠道优化、形象提升、地推助力、媒体推广”四大维度的大力度市场支持。&lt;/p&gt;\n&lt;p&gt;为了进一步提高产品研发水平，集团特别聘请来自台湾的烘焙大师林甫青先生出任集团首席技术顾问，聘任仪式在本次大会中举行，总经理刘海陶代表集团，向林先生颁发了聘书及锦旗。林老师表示，他被来利洪的匠心精神深深打动，希望双方能共同研发出更多精致、纯粹的美食。&lt;/p&gt;', NULL),
(2, 0, '', '', NULL, 0, NULL, NULL, NULL, '/Users/Public/Themes/_7/default/images/news/thumb.jpg', NULL, '&lt;img src=&quot;/Users/Public/Themes/_7/default/images/news/figure.jpg&quot;&gt;\n&lt;p&gt;2017年6月16日，以“欢迎回家”为主题的来利洪食品集团2017年全国经销商大会暨新品发布会在广州来利洪集团总部隆重召开，全国数百名经销商家人欢聚一堂，共襄盛会，再续情谊。&lt;/p&gt;\n&lt;p&gt;大会正式开始前，在各厂生产主管的陪同下，经销商家人先后参观了瑞达饼干生产车间、溏心月饼生产车间以及来利洪饼干生产车间，了解近年更新的生产设备和生产技术。参观过程中，一尘不染的环境和先进的设备给大家留下了深刻的印象。&lt;/p&gt;\n&lt;p&gt;午后，会议在激动人心的倒数声中开始。集团创始人、董事长刘启洪在致辞中表示，来利洪创办至今已有35年历史，在座不乏合作时间长达十年以上的老客户，多年来相互合作，相互信任，相互支持，彼此间的情谊早已超越生意伙伴，胜似家人。今天很高兴能够与大家相聚于来利洪的大本营，预祝本次会议获得圆满成功，同时也祝愿各位家人朋友身体健康，事业顺利。&lt;/p&gt;\n&lt;p&gt;会上，集团总经理刘海陶重点介绍了集团旗下品牌溏心月饼的未来发展规划，伴随着品牌战略的调整，下阶段溏心将从“调性升级、形象升级、产品升级”三大方面进行品牌“迭代”，垂直细分市场，从“溏心1.0”跨入“溏心2.0”。此外，刘总揭幕了本次会议发布的主题新品——流心月饼，同时安排了现场试吃环节。流心月饼美味的口感让大家为之惊艳，不少经销商向工作人员要求“再来一碟”。刘总表示，作为溏心的匠心之作，流心月饼拟以品牌拳头产品的定位推向市场，集团将提供“渠道优化、形象提升、地推助力、媒体推广”四大维度的大力度市场支持。&lt;/p&gt;\n&lt;p&gt;为了进一步提高产品研发水平，集团特别聘请来自台湾的烘焙大师林甫青先生出任集团首席技术顾问，聘任仪式在本次大会中举行，总经理刘海陶代表集团，向林先生颁发了聘书及锦旗。林老师表示，他被来利洪的匠心精神深深打动，希望双方能共同研发出更多精致、纯粹的美食。&lt;/p&gt;', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_note`
--

CREATE TABLE `ni_cloud_schema_note` (
  `ID` int(11) NOT NULL,
  `ARCHIVE` varchar(64) NOT NULL DEFAULT '',
  `NAME` varchar(128) NOT NULL,
  `GUID` char(64) NOT NULL,
  `LANG` char(64) NOT NULL,
  `CONTENT` longtext NOT NULL,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_project`
--

CREATE TABLE `ni_cloud_schema_project` (
  `ID` int(11) NOT NULL,
  `RANK` tinyint(1) DEFAULT '6',
  `PROJ_GUID` char(64) NOT NULL,
  `PROGRESS` varchar(256) DEFAULT NULL,
  `RATE` tinyint(3) DEFAULT '0',
  `PIC` varchar(128) DEFAULT NULL,
  `PARTICIPANTS` longtext,
  `START_TIME` datetime NOT NULL,
  `FINISH_TIME` datetime NOT NULL,
  `PLAN_START_TIME` datetime DEFAULT NULL,
  `PLAN_FINISH_TIME` datetime DEFAULT NULL,
  `PLAN` longtext,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_resume`
--

CREATE TABLE `ni_cloud_schema_resume` (
  `ID` int(11) NOT NULL,
  `RANK` tinyint(1) DEFAULT '6',
  `NAME` varchar(512) DEFAULT NULL,
  `NICKNAME` varchar(512) DEFAULT NULL,
  `HONOR` varchar(512) DEFAULT NULL,
  `SEX` tinyint(1) DEFAULT '0',
  `PHOTO` varchar(512) NOT NULL,
  `CERTIFICATES` longtext NOT NULL,
  `DETAILS` longtext NOT NULL,
  `RESUME` longtext,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_wiki`
--

CREATE TABLE `ni_cloud_schema_wiki` (
  `ID` int(11) NOT NULL,
  `OID` int(11) NOT NULL DEFAULT '0' COMMENT '词条最初的ID',
  `RANK` tinyint(1) DEFAULT '6',
  `NAME` varchar(512) DEFAULT NULL,
  `ALIASES` longtext,
  `IMAGE` varchar(512) NOT NULL,
  `MEANING` longtext,
  `PARTICIPANTS` longtext,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_tablemeta`
--

CREATE TABLE `ni_cloud_tablemeta` (
  `name` char(64) NOT NULL,
  `type` char(7) NOT NULL DEFAULT 'default',
  `item` varchar(128) NOT NULL DEFAULT 'Item',
  `fields` longtext,
  `app_id` int(11) NOT NULL DEFAULT '0',
  `app_data` longtext COMMENT '应用为表拓展的属性，JSON',
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_tablemeta`
--

INSERT INTO `ni_cloud_tablemeta` (`name`, `type`, `item`, `fields`, `app_id`, `app_data`, `SK_STATE`) VALUES
('news', 'news', 'News', '[]', 10, '', 1),
('positions', 'job', 'position', '[]', 11, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_tablerowmeta`
--

CREATE TABLE `ni_cloud_tablerowmeta` (
  `ID` int(11) NOT NULL,
  `TYPE` char(7) NOT NULL DEFAULT 'default',
  `TABLENAME` varchar(64) NOT NULL,
  `FOLDER` int(11) DEFAULT '0',
  `TITLE` varchar(512) DEFAULT NULL,
  `DESCRIPTION` varchar(1024) DEFAULT NULL,
  `PUBTIME` datetime DEFAULT NULL,
  `LEVEL` int(11) NOT NULL DEFAULT '0',
  `SK_CTIME` datetime NOT NULL,
  `SK_MTIME` datetime DEFAULT NULL,
  `SK_STATE` int(11) DEFAULT '1',
  `SK_IS_RECYCLED` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_tablerowmeta`
--

INSERT INTO `ni_cloud_tablerowmeta` (`ID`, `TYPE`, `TABLENAME`, `FOLDER`, `TITLE`, `DESCRIPTION`, `PUBTIME`, `LEVEL`, `SK_CTIME`, `SK_MTIME`, `SK_STATE`, `SK_IS_RECYCLED`) VALUES
(1, 'news', 'news', 7, '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', '2018-01-21 00:00:00', 0, '0000-00-00 00:00:00', '2018-03-01 19:20:02', 1, 0),
(2, 'news', 'news', 8, '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品', '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', '2018-01-21 00:00:00', 0, '0000-00-00 00:00:00', '2018-02-23 16:11:25', 1, 0),
(3, 'job', 'positions', 0, '前台专员', '前台专员', '2018-01-25 00:00:00', 0, '0000-00-00 00:00:00', '2018-02-11 22:34:35', 1, 0),
(4, 'job', 'positions', 0, '天猫运营', '天猫运营', '2018-01-25 00:00:00', 0, '0000-00-00 00:00:00', '2018-02-11 22:34:22', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_tagmaps`
--

CREATE TABLE `ni_cloud_tagmaps` (
  `id` int(11) NOT NULL,
  `tag` char(220) NOT NULL,
  `type` char(7) NOT NULL,
  `tablename` char(64) DEFAULT NULL,
  `item` char(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_bat`
--

CREATE TABLE `ni_notifier_bat` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u0`
--

CREATE TABLE `ni_notifier_u0` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u1`
--

CREATE TABLE `ni_notifier_u1` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u2`
--

CREATE TABLE `ni_notifier_u2` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u3`
--

CREATE TABLE `ni_notifier_u3` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u4`
--

CREATE TABLE `ni_notifier_u4` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u5`
--

CREATE TABLE `ni_notifier_u5` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u6`
--

CREATE TABLE `ni_notifier_u6` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u7`
--

CREATE TABLE `ni_notifier_u7` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u8`
--

CREATE TABLE `ni_notifier_u8` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_notifier_u9`
--

CREATE TABLE `ni_notifier_u9` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `content` longtext,
  `target` varchar(512) DEFAULT NULL,
  `SK_TIME` datetime NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_pages_archives`
--

CREATE TABLE `ni_pages_archives` (
  `id` int(11) NOT NULL,
  `archive_name` varchar(128) NOT NULL COMMENT '归档名称',
  `archive_desc` varchar(256) DEFAULT NULL COMMENT '归档描述',
  `archive_image` varchar(512) DEFAULT NULL COMMENT '归档图片',
  `archive_hp` varchar(512) DEFAULT NULL COMMENT '归档主页'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_pages_comments`
--

CREATE TABLE `ni_pages_comments` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `reply2` int(11) NOT NULL DEFAULT '0',
  `title` varchar(512) DEFAULT NULL,
  `content` longtext NOT NULL,
  `file` varchar(1024) DEFAULT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `pubtime` datetime NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_pages_links`
--

CREATE TABLE `ni_pages_links` (
  `id` int(11) NOT NULL,
  `menu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分组',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级链接',
  `name` varchar(128) NOT NULL COMMENT '链接名',
  `type` char(4) NOT NULL DEFAULT 'page' COMMENT '链接类型',
  `value` varchar(512) NOT NULL COMMENT 'page_id或archive_id或url',
  `alt` varchar(512) DEFAULT NULL COMMENT 'alt替换',
  `sort` float NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_pages_options`
--

CREATE TABLE `ni_pages_options` (
  `option_name` char(64) NOT NULL COMMENT '项目名',
  `option_value` longtext NOT NULL COMMENT '项目值',
  `autoload` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否主动加载'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_pages_options`
--

INSERT INTO `ni_pages_options` (`option_name`, `option_value`, `autoload`) VALUES
('default_description', '来利洪集团官网, 广州来利洪饼业', 1),
('default_page_title', '来利洪集团官网', 1),
('use_theme', 'default', 1),
('default_page_template', 'index.niml', 1),
('logo', '/Users/Public/Themes/_PAGES/default/images/logo.common.jpg', 1),
('default_page_content', '<div><br></div>', 1),
('common_bottom', '<span>版权所有© 2017 广州来利洪食品集团</span><span>地 址: 广州市白云区人和镇秀盛路三盛工业区自编1号</span><span>粤ICP备14012344号</span><span>技术支持：唐云科技</span>', 1),
('more', '附加内容', 1),
('search_result_page_template', '', 1),
('default_page_url', '/', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ni_pages_pages`
--

CREATE TABLE `ni_pages_pages` (
  `id` int(11) NOT NULL COMMENT '主键',
  `alias` varchar(256) NOT NULL COMMENT '页面别名',
  `archive` int(11) NOT NULL DEFAULT '0' COMMENT '归档id，0为不归档',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT '父页面',
  `title` varchar(512) NOT NULL COMMENT '页面标题',
  `crttime` datetime NOT NULL COMMENT '创建时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  `pubtime` datetime NOT NULL COMMENT '发布时间',
  `thumb_inlist` varchar(512) NOT NULL,
  `description` varchar(1024) NOT NULL COMMENT '页面SEO描述',
  `banner` varchar(512) DEFAULT NULL,
  `banner_link` varchar(512) DEFAULT NULL,
  `content` longtext COMMENT '页面内容',
  `template` varchar(512) DEFAULT NULL COMMENT '模板，留空为使用父级模板',
  `more` longtext COMMENT '附加内容，支持JSON',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '发布状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_reg_appdirs`
--

CREATE TABLE `ni_reg_appdirs` (
  `ID` int(11) NOT NULL COMMENT '主键',
  `MAP_ID` int(11) NOT NULL DEFAULT '0' COMMENT '所属路由表ID，默认为根表',
  `DOMAIN` char(255) NOT NULL DEFAULT '<ANY>' COMMENT '相对路径所在域名',
  `DIR_NAME` char(255) NOT NULL DEFAULT '/dirname' COMMENT '目录名，即分配给下线路由器的相对路径名',
  `HANDLER` char(32) NOT NULL COMMENT '子路由表ID或下线路由所属子应用的ID',
  `ROUTE` int(11) NOT NULL DEFAULT '0' COMMENT '信道，应用可以有多个不同路由，他们会自动按配置顺序编号，一个路由可以占用多个信道',
  `DEFAULTS` longtext COMMENT '预留参数组，JSON格式',
  `SK_STATE` tinyint(11) NOT NULL DEFAULT '1' COMMENT '启动状态，默认为1，即索引状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='路由索引表，又名总路由表';

--
-- 转存表中的数据 `ni_reg_appdirs`
--

INSERT INTO `ni_reg_appdirs` (`ID`, `MAP_ID`, `DOMAIN`, `DIR_NAME`, `HANDLER`, `ROUTE`, `DEFAULTS`, `SK_STATE`) VALUES
(1, 0, '<ANY>', '/slrapi/', 'SA', 0, '{}', 1),
(2, 0, '<ANY>', '/restapi/', 'SA', 1, '{}', 1),
(3, 0, '<ANY>', '/regexpapi/', 'SA', 2, '{}', 1),
(4, 0, '<ANY>', '/customapi/', 'SA', 3, '{}', 1),
(6, 0, '<DEF>', '/admin/', '2', 0, '{}', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ni_reg_apps`
--

CREATE TABLE `ni_reg_apps` (
  `app_id` int(11) NOT NULL COMMENT '应用ID',
  `dev_id` int(11) NOT NULL DEFAULT '0' COMMENT '开发者内部ID',
  `app_name` varchar(128) NOT NULL COMMENT '应用名',
  `app_scode` char(2) NOT NULL COMMENT '开发者内部短编号',
  `app_authorname` char(32) NOT NULL DEFAULT 'YangRAM' COMMENT '开发者',
  `app_installpath` varchar(128) DEFAULT NULL COMMENT '安装路径',
  `app_usedb` int(3) NOT NULL DEFAULT '0' COMMENT '所用数据库索引',
  `app_build_time` datetime NOT NULL COMMENT '应用发布时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='已购装商城应用登记表';

--
-- 转存表中的数据 `ni_reg_apps`
--

INSERT INTO `ni_reg_apps` (`app_id`, `dev_id`, `app_name`, `app_scode`, `app_authorname`, `app_installpath`, `app_usedb`, `app_build_time`) VALUES
(0, 0, 'YangRAM Market', 'MK', 'YangRAM', 'AdminSuit/Market/', 0, '0000-00-00 00:00:00'),
(6, 0, 'YangRAM Statistics', 'ST', 'YangRAM', 'Statistics/', 0, '2018-01-19 05:06:03'),
(9, 0, 'YangRAM Jobs', 'JB', 'YangRAM', 'Jobs/', 0, '2018-01-19 05:06:03'),
(1, 0, 'YangRAM Contacts', 'CT', 'YangRAM', 'AdminSuit/Contacts/', 0, '0000-00-00 00:00:00'),
(8, 0, 'YangRAM Goods', 'GD', 'YangRAM', 'Goods/', 0, '0000-00-00 00:00:00'),
(3, 0, 'YangRAM Registry', 'RG', 'YangRAM', 'AdminSuit/Registry/', 0, '0000-00-00 00:00:00'),
(2, 0, 'YangRAM Backstage', 'BS', 'YangRAM', 'AdminSuit/Backstage/', 0, '0000-00-00 00:00:00'),
(1015, 0, 'Book+', 'Bp', 'Tangram', 'IP/BookPlus/', 0, '0000-00-00 00:00:00'),
(1016, 0, 'Comment+', 'Cm', 'Tangram', 'IP/CommentPlus/', 0, '0000-00-00 00:00:00'),
(1017, 0, 'Evaluate+', 'Ep', 'Tangram', 'IP/EvaluatePlus/', 0, '0000-00-00 00:00:00'),
(1018, 0, 'Like+', 'Lk', 'Tangram', 'IP/LikePlus/', 0, '0000-00-00 00:00:00'),
(1019, 0, 'Support+', 'Su', 'Tangram', 'IP/SupportPlus/', 0, '0000-00-00 00:00:00'),
(1020, 0, 'Favorite+', 'Fa', 'Tangram', 'IP/FavoritePlus/', 0, '0000-00-00 00:00:00'),
(1021, 0, 'Sign+', 'Sp', 'Tangram', 'IP/SignPlus/', 0, '0000-00-00 00:00:00'),
(1022, 0, 'YangRAM Listen', 'Lt', 'YangRAM', 'NFA/Listen/', 0, '0000-00-00 00:00:00'),
(1023, 0, 'YangRAM Watch', 'Gk', 'YangRAM', 'NFA/Watch/', 0, '0000-00-00 00:00:00'),
(1024, 0, 'YangRAM Read', 'Yd', 'YangRAM', 'NFA/Read/', 0, '0000-00-00 00:00:00'),
(1025, 0, 'YangRAM Play', 'Yx', 'YangRAM', 'NFA/Play/', 0, '0000-00-00 00:00:00'),
(1026, 0, 'YangRAM News', 'Xw', 'YangRAM', 'NFA/News/', 0, '0000-00-00 00:00:00'),
(1027, 0, 'YangRAM Weather', 'Tq', 'YangRAM', 'NFA/Weather/', 0, '0000-00-00 00:00:00'),
(1028, 0, 'YangRAM Stock', 'Gp', 'YangRAM', 'NFA/Stock/', 0, '0000-00-00 00:00:00'),
(1234, 0, 'Microivan FastGPS', 'Fp', 'MicroIvan', 'FastGPS/', 0, '0000-00-00 00:00:00'),
(6109, 0, 'Microivan  Blog', 'Bg', 'MicroIvan', 'Multilingual/Blog/', 0, '0000-00-00 00:00:00'),
(6885, 0, 'Microivan  BBS', 'Bg', 'MicroIvan', 'BBS/', 0, '0000-00-00 00:00:00'),
(6886, 0, 'Microivan  Fourm', 'Bg', 'MicroIvan', 'Fourm/', 0, '0000-00-00 00:00:00'),
(10, 0, 'YangRAM Promotion', 'Pn', 'YangRAM', 'Promotion/', 0, '2018-01-19 00:00:00'),
(7, 0, 'YangRAM Press', 'Pr', 'YangRAM', 'Press/', 0, '2018-01-19 05:06:03');

-- --------------------------------------------------------

--
-- 表的结构 `ni_reg_languages`
--

CREATE TABLE `ni_reg_languages` (
  `LOC_ID` int(11) NOT NULL COMMENT '负责机构或部门ID',
  `LANG` char(5) NOT NULL COMMENT '标准语言代码',
  `NAME` varchar(50) NOT NULL COMMENT '该语言版本的站点名',
  `OWNER` varchar(60) NOT NULL COMMENT '该语言版本的所有者名',
  `ADDR` varchar(256) NOT NULL COMMENT '该语言版本机构或部门地址',
  `BRIEF` longtext NOT NULL COMMENT '该语言版本站点或机构简介',
  `REMARK` varchar(256) NOT NULL COMMENT '该语言版本的备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='语言表';

--
-- 转存表中的数据 `ni_reg_languages`
--

INSERT INTO `ni_reg_languages` (`LOC_ID`, `LANG`, `NAME`, `OWNER`, `ADDR`, `BRIEF`, `REMARK`) VALUES
(1, 'en-us', 'Your Tangram', 'your name', 'no content', 'no content', 'no content');

-- --------------------------------------------------------

--
-- 表的结构 `ni_reg_locations`
--

CREATE TABLE `ni_reg_locations` (
  `id` int(11) NOT NULL COMMENT '主键，机构ID',
  `tel1` char(32) DEFAULT NULL COMMENT '联系电话1',
  `tel2` char(32) DEFAULT NULL COMMENT '联系电话2',
  `email` char(255) NOT NULL COMMENT '电子邮箱',
  `lng` float(9,6) NOT NULL DEFAULT '0.000000' COMMENT '所在地经度',
  `lat` float(9,6) NOT NULL DEFAULT '0.000000' COMMENT '所在地纬度'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地区表，登记负责地区的分支、代理机构和相关部门';

--
-- 转存表中的数据 `ni_reg_locations`
--

INSERT INTO `ni_reg_locations` (`id`, `tel1`, `tel2`, `email`, `lng`, `lat`) VALUES
(1, '12345678', '12345678', 'yourname@abc.com', 113.276245, 23.188196);

-- --------------------------------------------------------

--
-- 表的结构 `ni_reg_usergroups`
--

CREATE TABLE `ni_reg_usergroups` (
  `GUID` char(70) NOT NULL COMMENT '用户组ID，#APPID_TYPE[SYMBOL]',
  `ALIAS` char(64) DEFAULT NULL COMMENT '组别名',
  `APPID` char(32) NOT NULL DEFAULT 'USERS' COMMENT '所属应用ID',
  `TYPE` char(4) NOT NULL DEFAULT 'CARD' COMMENT '认证或授权类型，有CARD、VISA、USER三种',
  `SYMBOL` char(32) DEFAULT NULL COMMENT '标识符，salt、member group、user id，可为空',
  `TABLENAME` char(128) DEFAULT NULL COMMENT '指定认证表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组表';

--
-- 转存表中的数据 `ni_reg_usergroups`
--

INSERT INTO `ni_reg_usergroups` (`GUID`, `ALIAS`, `APPID`, `TYPE`, `SYMBOL`, `TABLENAME`) VALUES
('#STUDIO_VISA', 'Administrators', 'STUDIO', 'VISA', NULL, 'public_administrators'),
('#STUDIO_VISAOPTR', 'System Operators', 'STUDIO', 'VISA', 'OPTR', 'public_administrators'),
('#USERS_CARD', 'Users', 'USERS', 'CARD', NULL, 'reg_users'),
('#USERS_USER0', 'Guests', 'USERS', 'USER', '0', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ni_reg_users`
--

CREATE TABLE `ni_reg_users` (
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `username` char(32) NOT NULL COMMENT '单词用户名',
  `unicodename` char(64) NOT NULL COMMENT 'UNICODE用户名',
  `nickname` char(64) DEFAULT NULL COMMENT '昵称',
  `avatar` longtext NOT NULL COMMENT '用户头像',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `regtime` datetime NOT NULL COMMENT '注册时间',
  `remark` char(255) DEFAULT NULL COMMENT '用户备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `ni_reg_users`
--

INSERT INTO `ni_reg_users` (`uid`, `status`, `username`, `unicodename`, `nickname`, `avatar`, `password`, `regtime`, `remark`) VALUES
(1, 0, 'admin', 'Administrator', 'Administrator', '/applications/uploads/files/4b8512215a30cac5346.jpg', '9188fd3d1405c6b80d86b35689a58614', '2015-07-12 18:17:37', '这个家伙很懒'),
(2, 0, 'assistant', '运营助理', 'Assistant', '/applications/uploads/files/4b8512215a30cac5346.jpg', '9188fd3d1405c6b80d86b35689a58614', '2015-07-12 18:17:37', '这个家伙很懒'),
(3, 0, 'financial', '财务主管', 'Financial Controller', '/applications/uploads/files/4b8512215a30cac5346.jpg', '9188fd3d1405c6b80d86b35689a58614', '2015-07-12 18:17:37', '这个家伙很懒');

-- --------------------------------------------------------

--
-- 表的结构 `ni_statistics_guests`
--

CREATE TABLE `ni_statistics_guests` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `gst_id` char(15) NOT NULL,
  `aid` int(11) NOT NULL DEFAULT '10',
  `col_id` int(11) NOT NULL,
  `uri` varchar(512) NOT NULL,
  `accesstime` datetime NOT NULL,
  `is_mobile` int(11) NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL,
  `is_new` int(11) NOT NULL,
  `source` varchar(512) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_studio_apps`
--

CREATE TABLE `ni_studio_apps` (
  `app_id` int(11) NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `app_icon` char(32) DEFAULT 'yangram-logo',
  `app_bgcolor` char(8) DEFAULT NULL,
  `app_releasetime` date NOT NULL,
  `app_count` int(11) NOT NULL DEFAULT '0',
  `app_is_ondock` int(11) NOT NULL DEFAULT '0',
  `app_is_new` int(11) NOT NULL DEFAULT '0',
  `app_description` varchar(1000) DEFAULT ' no information',
  `app_keywords` varchar(256) NOT NULL DEFAULT ' ',
  `app_last_runtime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_studio_apps`
--

INSERT INTO `ni_studio_apps` (`app_id`, `app_name`, `app_icon`, `app_bgcolor`, `app_releasetime`, `app_count`, `app_is_ondock`, `app_is_new`, `app_description`, `app_keywords`, `app_last_runtime`) VALUES
(1, 'YangRAM Market', 'yangram-market', '0', '2015-07-11', 8, 1, 0, ' no information', '', '0000-00-00 00:00:00'),
(2, 'User Center', 'users', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(3, 'YangRAM Pages', 'docs', '0', '2015-07-11', 825, 1, 0, ' no information', '', '0000-00-00 00:00:00'),
(4, 'Statistics', 'pie-chart', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(5, 'Calendar', 'calendar', '0', '2016-04-08', 106, 0, 1, ' no information', '', '0000-00-00 00:00:00'),
(11, 'Contacts', 'user', '0', '2015-07-11', 1, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(12, 'Calculator', 'calculator', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(13, 'OperPark', '', '0', '2016-04-08', 86, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(14, 'Registry', 'key', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(15, 'Healthy', '', '0', '2016-06-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1001, 'Developer', 'studio-developer', '0', '2015-07-11', 10, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1002, 'Publisher', 'studio-publisher', '0', '2015-07-11', 2727, 1, 0, ' no information', '', '0000-00-00 00:00:00'),
(1003, 'Collector', 'studio-collector', '0', '2015-07-11', 14, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1004, 'Book Keeper', 'studio-bookkeeper', '0', '2015-07-11', 9, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1005, 'Sticker', 'studio-sticker', '0', '2015-07-11', 505, 1, 0, ' no information', '', '0000-00-00 00:00:00'),
(1006, 'Designer', 'studio-designer', '0', '2015-07-11', 248, 1, 0, ' no information', '', '0000-00-00 00:00:00'),
(1007, 'Tasker', 'studio-tasker', '0', '2015-07-11', 10, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1015, 'Book+', 'notebook', '0', '2015-07-11', 58, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1016, 'Comment+', 'speech', '0', '2015-07-11', 17, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1017, 'Evaluate+', 'bar-chart', '0', '2015-07-11', 17, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1018, 'Like+', 'heart', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1019, 'Support+', 'like', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1020, 'Favorite+', 'star', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1021, 'Sign+', 'note', '0', '2015-07-11', 0, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1022, 'YangRAM Listen', 'earphones', '0', '2016-06-11', 6, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1023, 'YangRAM Watch', 'social-youtube', '0', '2016-06-11', 1, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1024, 'YangRAM Read', 'open', '0', '2016-06-11', 3, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1025, 'YangRAM Play', 'game-controller', '0', '2016-06-11', 1, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1026, 'YangRAM News', 'book-open', '0', '2016-06-11', 2, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1027, 'YangRAM Weather', 'energy', '0', '2016-06-11', 2, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1028, 'YangRAM Stock', 'graph', '0', '2016-06-11', 9, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(1234, 'Microivan FastGPS', '', '0', '2015-07-11', 76, 1, 0, ' no information', '', '0000-00-00 00:00:00'),
(8900, 'Microivan FTP', 'folder-alt', '0', '2015-07-11', 1, 0, 0, ' no information', '', '0000-00-00 00:00:00'),
(6109, 'Microivan  Blog', 'pineapple', '0', '2015-07-11', 41, 1, 0, ' no information', ' ', '0000-00-00 00:00:00'),
(6885, 'Microivan  BBS', 'garlic', '0', '2015-07-11', 41, 1, 0, ' no information', ' ', '0000-00-00 00:00:00'),
(6886, 'Microivan  Fourm', 'watermelon', '0', '2015-07-11', 41, 1, 0, ' no information', ' ', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `ni_studio_i4plazz_widgets`
--

CREATE TABLE `ni_studio_i4plazz_widgets` (
  `alias` varchar(32) NOT NULL,
  `title` char(128) NOT NULL,
  `aid` int(11) NOT NULL,
  `link_href` varchar(256) NOT NULL,
  `api_method` varchar(256) NOT NULL,
  `api_token` varchar(256) NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_studio_links`
--

CREATE TABLE `ni_studio_links` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `type` char(3) NOT NULL DEFAULT 'app',
  `name` char(255) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `href` varchar(512) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_bills`
--

CREATE TABLE `ni_users_bills` (
  `ID` int(11) NOT NULL COMMENT '主键',
  `TRANS_TYPE` tinyint(1) DEFAULT '1' COMMENT '交易类型',
  `AMOUT` int(11) NOT NULL DEFAULT '0' COMMENT '交易金额',
  `TRANS_TIME` date NOT NULL COMMENT '交易时间',
  `INCOME_ACOUNT` char(255) NOT NULL,
  `PAY_ACOUNT` char(255) NOT NULL,
  `UID` int(11) NOT NULL COMMENT '用户ID',
  `REMARK` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_events`
--

CREATE TABLE `ni_users_events` (
  `uid` int(11) NOT NULL,
  `SK_CTIME` datetime NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `repeat_type` int(11) NOT NULL,
  `kld_type` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `notice_type` int(2) NOT NULL,
  `url` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_profiles`
--

CREATE TABLE `ni_users_profiles` (
  `uid` int(11) NOT NULL,
  `realname` varchar(60) DEFAULT NULL,
  `firstname` varchar(20) DEFAULT NULL,
  `surname` varchar(20) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `language` varchar(5) DEFAULT '',
  `state` varchar(30) DEFAULT NULL,
  `province` varchar(30) DEFAULT NULL,
  `county` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `certificate_type` int(11) DEFAULT NULL,
  `certificate_id` int(30) DEFAULT NULL,
  `sex` int(11) DEFAULT '0',
  `brief` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_relationcircles`
--

CREATE TABLE `ni_users_relationcircles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_relationships`
--

CREATE TABLE `ni_users_relationships` (
  `uid` int(11) NOT NULL COMMENT '用户标识',
  `_uid` int(11) NOT NULL COMMENT '关联用户标识',
  `circle` int(11) NOT NULL DEFAULT '0' COMMENT '关联用户所属用户组',
  `_circle` int(11) NOT NULL DEFAULT '0' COMMENT '用户在关联用户的用户组',
  `follow_time` datetime NOT NULL COMMENT '关联时间',
  `followed_time` datetime NOT NULL COMMENT '被关联时间',
  `SK_STATE` int(11) NOT NULL COMMENT '关联状态:{0:没有关系,1:关联中,2:被关联中,3:相互关注,4:特别关注中,5:被特别关注中}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_tokens`
--

CREATE TABLE `ni_users_tokens` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `token` varchar(32) NOT NULL,
  `uid` int(11) NOT NULL,
  `dateline` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_users_tokens`
--

INSERT INTO `ni_users_tokens` (`id`, `type`, `token`, `uid`, `dateline`) VALUES
(1, 1, '3e9f927d40f5fabab03c766eed79e87e', 1, '2015-11-09 11:26:04');

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_wallets`
--

CREATE TABLE `ni_users_wallets` (
  `uid` int(11) NOT NULL,
  `pw_pay` varchar(32) DEFAULT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'usd',
  `balance` int(11) NOT NULL DEFAULT '0',
  `frozen` int(11) NOT NULL DEFAULT '0',
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_users_widgets`
--

CREATE TABLE `ni_users_widgets` (
  `alias` varchar(32) NOT NULL,
  `name` char(64) NOT NULL,
  `aid` int(11) NOT NULL,
  `link_url` varchar(256) NOT NULL,
  `api_method` varchar(256) NOT NULL,
  `api_params` varchar(256) NOT NULL,
  `api_on_nav` tinyint(1) NOT NULL DEFAULT '1',
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni__administrators`
--

CREATE TABLE `ni__administrators` (
  `UID` int(11) NOT NULL COMMENT '关联用户ID，属于外键',
  `OPERATORNAME` varchar(30) NOT NULL COMMENT '管理员内部别名',
  `PIN` char(32) NOT NULL DEFAULT '3fbfeb2d38abd0ddeb7976f78eb655d1' COMMENT '认证码',
  `OGROUP` int(11) NOT NULL DEFAULT '1' COMMENT '管理员组',
  `AVATAR` varchar(300) DEFAULT NULL COMMENT '管理员头像',
  `LANGUAGE` varchar(5) DEFAULT NULL COMMENT '管理员所用语言'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni__administrators`
--

INSERT INTO `ni__administrators` (`UID`, `OPERATORNAME`, `PIN`, `OGROUP`, `AVATAR`, `LANGUAGE`) VALUES
(1, 'Admin', 'c9b522cffa52175b82f89431ec68d5a7', 1, '/applications/uploads/files/cc26775220c32188228.jpg', 'zh-cn');

-- --------------------------------------------------------

--
-- 表的结构 `ni__map_emails`
--

CREATE TABLE `ni__map_emails` (
  `email` char(255) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '用户状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

-- --------------------------------------------------------

--
-- 表的结构 `ni__map_identities`
--

CREATE TABLE `ni__map_identities` (
  `identity` char(255) NOT NULL COMMENT '证件号或账户名',
  `type` int(11) NOT NULL COMMENT '实名类型',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni__map_mobiles`
--

CREATE TABLE `ni__map_mobiles` (
  `mobile` char(32) NOT NULL DEFAULT '' COMMENT '手机，加号+国家区号+国内手机号',
  `uid` int(11) NOT NULL COMMENT '用户ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

-- --------------------------------------------------------

--
-- 表的结构 `ni__map_oauths`
--

CREATE TABLE `ni__map_oauths` (
  `oid` char(64) NOT NULL DEFAULT '' COMMENT '平台提高用来绑定的ID',
  `agency` char(7) NOT NULL COMMENT '认证或授权平台',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='开放授权用户映射表';

-- --------------------------------------------------------

--
-- 表的结构 `ni__map_usergroups`
--

CREATE TABLE `ni__map_usergroups` (
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `app_id` char(32) NOT NULL COMMENT '用户组所属应用ID',
  `group_symbol` char(32) DEFAULT NULL COMMENT '用户组Symbol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni__sessions`
--

CREATE TABLE `ni__sessions` (
  `id` varchar(32) NOT NULL,
  `SK_STAMP` int(32) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni__tables`
--

CREATE TABLE `ni__tables` (
  `table_name` char(128) NOT NULL COMMENT '表名称',
  `app_id` char(32) NOT NULL DEFAULT 'settings' COMMENT '关联应用，0为不限制',
  `relation_type` int(11) NOT NULL DEFAULT '2' COMMENT '关联类型，1为创建者，2为维护者'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni__tables`
--

INSERT INTO `ni__tables` (`table_name`, `app_id`, `relation_type`) VALUES
('tables', 'settings', 2),
('session', '0', 1),
('operators', 'studio', 1),
('operators', 'settings', 2),
('guests', 'users', 1),
('tables', '0', 1),
('session', 'all', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ni_10_ads`
--
ALTER TABLE `ni_10_ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_cloud_authorities`
--
ALTER TABLE `ni_cloud_authorities`
  ADD PRIMARY KEY (`uid`,`table_name`);

--
-- Indexes for table `ni_cloud_filemeta`
--
ALTER TABLE `ni_cloud_filemeta`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_filesrc`
--
ALTER TABLE `ni_cloud_filesrc`
  ADD PRIMARY KEY (`SID`),
  ADD UNIQUE KEY `HASH` (`HASH`);

--
-- Indexes for table `ni_cloud_folders`
--
ALTER TABLE `ni_cloud_folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_cloud_schema_album`
--
ALTER TABLE `ni_cloud_schema_album`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_article`
--
ALTER TABLE `ni_cloud_schema_article`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_artwork`
--
ALTER TABLE `ni_cloud_schema_artwork`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_default`
--
ALTER TABLE `ni_cloud_schema_default`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_job`
--
ALTER TABLE `ni_cloud_schema_job`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_news`
--
ALTER TABLE `ni_cloud_schema_news`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_note`
--
ALTER TABLE `ni_cloud_schema_note`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_project`
--
ALTER TABLE `ni_cloud_schema_project`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_resume`
--
ALTER TABLE `ni_cloud_schema_resume`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_schema_wiki`
--
ALTER TABLE `ni_cloud_schema_wiki`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_tablemeta`
--
ALTER TABLE `ni_cloud_tablemeta`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `ni_cloud_tablerowmeta`
--
ALTER TABLE `ni_cloud_tablerowmeta`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_tagmaps`
--
ALTER TABLE `ni_cloud_tagmaps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag` (`tag`,`type`,`item`);

--
-- Indexes for table `ni_notifier_bat`
--
ALTER TABLE `ni_notifier_bat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u0`
--
ALTER TABLE `ni_notifier_u0`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u1`
--
ALTER TABLE `ni_notifier_u1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u2`
--
ALTER TABLE `ni_notifier_u2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u3`
--
ALTER TABLE `ni_notifier_u3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u4`
--
ALTER TABLE `ni_notifier_u4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u5`
--
ALTER TABLE `ni_notifier_u5`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u6`
--
ALTER TABLE `ni_notifier_u6`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u7`
--
ALTER TABLE `ni_notifier_u7`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u8`
--
ALTER TABLE `ni_notifier_u8`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_notifier_u9`
--
ALTER TABLE `ni_notifier_u9`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_pages_archives`
--
ALTER TABLE `ni_pages_archives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_pages_comments`
--
ALTER TABLE `ni_pages_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_pages_links`
--
ALTER TABLE `ni_pages_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_pages_options`
--
ALTER TABLE `ni_pages_options`
  ADD PRIMARY KEY (`option_name`);

--
-- Indexes for table `ni_pages_pages`
--
ALTER TABLE `ni_pages_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_reg_appdirs`
--
ALTER TABLE `ni_reg_appdirs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_reg_apps`
--
ALTER TABLE `ni_reg_apps`
  ADD UNIQUE KEY `app_id` (`app_id`) USING BTREE;

--
-- Indexes for table `ni_reg_languages`
--
ALTER TABLE `ni_reg_languages`
  ADD PRIMARY KEY (`LANG`),
  ADD KEY `lct_id` (`LOC_ID`);

--
-- Indexes for table `ni_reg_locations`
--
ALTER TABLE `ni_reg_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_reg_usergroups`
--
ALTER TABLE `ni_reg_usergroups`
  ADD PRIMARY KEY (`GUID`),
  ADD UNIQUE KEY `ALIAS` (`ALIAS`);

--
-- Indexes for table `ni_reg_users`
--
ALTER TABLE `ni_reg_users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `alias` (`unicodename`);

--
-- Indexes for table `ni_statistics_guests`
--
ALTER TABLE `ni_statistics_guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_studio_apps`
--
ALTER TABLE `ni_studio_apps`
  ADD UNIQUE KEY `aid` (`app_id`);

--
-- Indexes for table `ni_studio_i4plazz_widgets`
--
ALTER TABLE `ni_studio_i4plazz_widgets`
  ADD PRIMARY KEY (`alias`);

--
-- Indexes for table `ni_studio_links`
--
ALTER TABLE `ni_studio_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_users_bills`
--
ALTER TABLE `ni_users_bills`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UID` (`UID`);

--
-- Indexes for table `ni_users_profiles`
--
ALTER TABLE `ni_users_profiles`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `ni_users_relationcircles`
--
ALTER TABLE `ni_users_relationcircles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_users_relationships`
--
ALTER TABLE `ni_users_relationships`
  ADD PRIMARY KEY (`uid`,`_uid`);

--
-- Indexes for table `ni_users_tokens`
--
ALTER TABLE `ni_users_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_users_widgets`
--
ALTER TABLE `ni_users_widgets`
  ADD PRIMARY KEY (`alias`);

--
-- Indexes for table `ni__administrators`
--
ALTER TABLE `ni__administrators`
  ADD PRIMARY KEY (`UID`);

--
-- Indexes for table `ni__map_emails`
--
ALTER TABLE `ni__map_emails`
  ADD PRIMARY KEY (`email`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `ni__map_identities`
--
ALTER TABLE `ni__map_identities`
  ADD PRIMARY KEY (`type`,`identity`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `ni__map_mobiles`
--
ALTER TABLE `ni__map_mobiles`
  ADD PRIMARY KEY (`mobile`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `ni__map_oauths`
--
ALTER TABLE `ni__map_oauths`
  ADD PRIMARY KEY (`agency`,`oid`),
  ADD KEY `USERID` (`uid`);

--
-- Indexes for table `ni__sessions`
--
ALTER TABLE `ni__sessions`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ni__tables`
--
ALTER TABLE `ni__tables`
  ADD PRIMARY KEY (`table_name`,`app_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ni_10_ads`
--
ALTER TABLE `ni_10_ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `ni_cloud_filesrc`
--
ALTER TABLE `ni_cloud_filesrc`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `ni_cloud_folders`
--
ALTER TABLE `ni_cloud_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `ni_cloud_tablerowmeta`
--
ALTER TABLE `ni_cloud_tablerowmeta`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `ni_notifier_bat`
--
ALTER TABLE `ni_notifier_bat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u0`
--
ALTER TABLE `ni_notifier_u0`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u1`
--
ALTER TABLE `ni_notifier_u1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u2`
--
ALTER TABLE `ni_notifier_u2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u3`
--
ALTER TABLE `ni_notifier_u3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u4`
--
ALTER TABLE `ni_notifier_u4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u5`
--
ALTER TABLE `ni_notifier_u5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u6`
--
ALTER TABLE `ni_notifier_u6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u7`
--
ALTER TABLE `ni_notifier_u7`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u8`
--
ALTER TABLE `ni_notifier_u8`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_notifier_u9`
--
ALTER TABLE `ni_notifier_u9`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_pages_archives`
--
ALTER TABLE `ni_pages_archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_pages_comments`
--
ALTER TABLE `ni_pages_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_pages_links`
--
ALTER TABLE `ni_pages_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_pages_pages`
--
ALTER TABLE `ni_pages_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键';

--
-- 使用表AUTO_INCREMENT `ni_reg_appdirs`
--
ALTER TABLE `ni_reg_appdirs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `ni_reg_locations`
--
ALTER TABLE `ni_reg_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，机构ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `ni_reg_users`
--
ALTER TABLE `ni_reg_users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID', AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `ni_statistics_guests`
--
ALTER TABLE `ni_statistics_guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_studio_links`
--
ALTER TABLE `ni_studio_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_users_relationcircles`
--
ALTER TABLE `ni_users_relationcircles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
