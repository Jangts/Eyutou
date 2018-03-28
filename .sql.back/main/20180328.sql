-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-03-28 22:32:51
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
-- 表的结构 `ni_cloud_authorities`
--

CREATE TABLE `ni_cloud_authorities` (
  `id` int(11) NOT NULL,
  `tablename` char(64) NOT NULL,
  `auth_type` char(1) NOT NULL DEFAULT 'R',
  `usergroup` char(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_authorities`
--

INSERT INTO `ni_cloud_authorities` (`id`, `tablename`, `auth_type`, `usergroup`) VALUES
(5, 'cloudnotes', 'A', 'Administrators'),
(4, 'news', 'R', 'EveryOne');

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_comments`
--

CREATE TABLE `ni_cloud_comments` (
  `id` int(11) NOT NULL,
  `row_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `pubtime` datetime NOT NULL,
  `content` longtext NOT NULL,
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '0'
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
  `FILE_EXTN` char(32) DEFAULT NULL,
  `SK_MTIME` datetime NOT NULL,
  `SK_IS_RECYCLED` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_filemeta`
--

INSERT INTO `ni_cloud_filemeta` (`ID`, `SRC_ID`, `FOLDER`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `FILE_EXTN`, `SK_MTIME`, `SK_IS_RECYCLED`) VALUES
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
(7, 'file', NULL, 'DefultImages', NULL, 1, 0, '2018-03-12 00:00:00'),
(8, 'file', NULL, 'ForbiddenImages', NULL, 1, 0, '2018-03-12 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_album`
--

CREATE TABLE `ni_cloud_schema_album` (
  `ID` int(11) NOT NULL,
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
(4, NULL, '天猫运营', '', '', 1, '0', '&lt;p&gt;负责收集潜在客户的有效信息； 负责协助中心学员招募，配合公司产品宣传；&lt;/p&gt;\n                                    &lt;p&gt;负责早教中心的行政事务； 负责早教中心的接待工作，如：接听来电、接待来访者；&lt;/p&gt;\n                                    &lt;p&gt;协助处理内/外部的各种事宜，统计出勤率以及客户服务； 玩具和产品的销售&lt;/p&gt;', 0, '', 0, NULL, '&lt;p&gt;专科及以上学历；善于沟通和普通话标准,英文流利，可简单对话；积极乐观，有爱心，喜爱儿&lt;/p&gt;\n                                    &lt;p&gt;童，乐于助人；有服务标准意识；良好的沟通技巧及协调能力；形象好，气质佳&lt;/p&gt;', 0, '', NULL),
(6, '', '1234567', '123456', '123456', 1, '', '&lt;div&gt;1234567&lt;/div&gt;', 0, '', 0, '', '&lt;div&gt;12345678&lt;/div&gt;', 0, '', ''),
(7, '', '34567890', '', '', 1, '', '&lt;div&gt;456789&lt;/div&gt;', 0, '', 0, '', '&lt;div&gt;34567890&lt;/div&gt;', 0, '', ''),
(10, '', '111111111111111111', '', '11111111111111', 1, '', '&lt;div&gt;122222222222&lt;/div&gt;', 0, '', 0, '', '&lt;div&gt;11111111111111111111111&lt;/div&gt;', 0, '', ''),
(28, '', '行政专员', '面议', '广州', 1, '', '&lt;p&gt;1、前台工作；2、工牌、饭卡管理工作；3、办公用品管理登记工作；4、其它后勤辅助工作。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;工作细致认真，善于沟通。&lt;/p&gt;', 1, '', ''),
(29, '', '淘宝客服', '', '广州', 1, '', '&lt;p&gt;1、及时回复旺旺咨询及留言，准确有效地为不同顾客做推荐参考；2、能单独处理售前、售中、售后问题；3、配合领导完成既定工作指标。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;善于沟通和交流，有团队合作精神，工作细致、认真。&lt;/p&gt;', 0, '', ''),
(30, '', '市场专员', '', '', 1, '', '&lt;p&gt;1.对目标市场进行资料搜集和数据分析与整理；2.主动参与市场活动前期准备及活动现场的相关工作（例如：促销活动、订货会、展会）；3..负责部门文档的建设与管理，以及为市场人员提供行政服务和支持；4.负责本部门与其他部门的协调工作，以及传达通知，通告等文件；5.协助市场部经理进行业务拓展、客户维护和外部供应商合作沟通；6.监控公司市场费用预算使用进度及产品库存管理，及时跟进费用核销以及反馈。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;大专以上学历，工作认真细致，责任心强。&lt;/p&gt;', 0, '', ''),
(31, '', '品质经理', '', '', 1, '', '&lt;p&gt;1、人员的管理与工作的监督；2、品质控制与异常处理；3、工作的指导与标准的确认；4、仪器设备工装夹具的管理；5、6S的推行与监督；6、建立质量信息库；7、参与本组质量管理体的建立、推行、维护和相关文件、记录的整理和宣导；8、参与讨论或提出品质改善提案及建议。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;2年以上相关从业经验。&lt;/p&gt;', 0, '', ''),
(32, '', '采购跟单', '', '', 1, '', '&lt;p&gt;1、主持采购部工作，领导采购部门配合相关部们做好本职工作。2，根据项目销售计划和生产计划制订采购计划，并督导实施。3、制定本部门的物资管理相关制度，使之规范化。4、制定物料采购原则，并督导实施。5、做好采购的预测工作，根据资金运作情况，材料堆放程度，合理进行预先采购。6、定期组织员工进行采购业务知识的学习，精通采购业务和技巧，培养采购人员廉洁奉公的情操。7、带头遵守采购制度，杜绝不良行为的产生。8、控制好物料批量进购，避开由于市场不稳定所带来的风险。9、进行采购单据的规范指导和审批工作，协助财会进行往来账单的审核及成本的控制。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;责任心强，有一定抗压能力。&lt;/p&gt;', 0, '', ''),
(33, '', '维修电工', '', '', 1, '', '&lt;p&gt;1.维修办公大楼的水电，对办公大楼的水电每周进行一次检查及维护。2.维修员工宿舍楼的水电，保障员工的福利和生活环境。3.维修饭堂水电，保证饭堂的正常运行。4.根据本司需求做一些简单的水泥工程。5.收集办公大楼、员工宿舍和饭堂的环境信息。6.根据工作环境对上级提出合理的整改建议。7.及时完成上级交付的工作任务。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;认真负责，有相关从业经验。&lt;/p&gt;', 0, '', ''),
(34, '', '生产计划/计划专员', '', '', 1, '', '&lt;p&gt;1.根据销售部门订单，做出每个月的生产滚动计划；2.分析长期生产计划，根据定员，做出滚动人员需求计划；&lt;/p&gt;&lt;p&gt;3.定期召开计划会议，充分沟通，保证生产计划的顺利进行；4.做好部门的耗材计划，保证生产活动的正常进行；5.关注半成品仓库存，减少过期不良品的出现；6.关注次饼仓库存，保证库存的正常消耗；7.及时完成上级交付的工作任务。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;责任心强。&lt;/p&gt;', 0, '', ''),
(35, '', '面包师傅', '', '', 1, '', '&lt;p&gt;1、面包车间生产管理工作；2、面包研发工作；3、培训管理工作。&lt;/p&gt;', 0, '', 0, '', '', 0, '', ''),
(36, '', '销售代表', '', '', 1, '', '&lt;p&gt;1、制定并完成业绩指标，保证公司销售策略的贯彻推行，以及完成主管交待的其他任务。2、根据公司产品策略，协助开展策划案及品牌推广；3、提升区域或渠道重点产品的渗透率，均衡发展品类并优化产品结构，扩大市场份额；4、与行业风向标客户以及重点终端客户建立紧密的合作关系，创造共享价值；5、市场信息的收集反馈，市场活动的建议和执行；6、协助终端供货商/经销商，提高产品分销能力。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;1、2年以上快速消费品行业销售经验或优秀应届毕业生，适应快速消费品行业工作压力；2、较强的团队合作精神，能在较强的工作压力下工作，并适应出差；3、良好的沟通能力及谈判技巧，强烈的责任心及团队合作精神；4、工作认真细致，做事严谨；5、会写工作总结，熟练使用Office 办公软件及ERP，现代办公设备。&lt;/p&gt;', 0, '', ''),
(37, '', '总账会计', '', '', 1, '', '&lt;p&gt;1.负责费用报销的审核，审批报销后及时录入系统，并完成账务处理；2.每月核对考勤表和工资表，上级领导审批签字后给出纳，发放后在系统制单；3.负责收付款项单据的账务处理，月末与总公司对账；4.了解公司产品组成并在系统做物料清单；5.与采购及销售核对应收及应付；6.负责固定资产卡片的录入及账务处理，并及时更新资产状态；7.负责仓库的盘点；8.负责凭证的整理及装订；9.负责账套结账和报表生成。&lt;/p&gt;', 0, '', 0, '', '', 0, '', ''),
(38, '', '物业管理经理', '', '', 1, '', '&lt;p&gt;1、抓好每月商户的各项费用抄录、收缴工作。2、配合处理水电突发事件的抢修工作，做好公共设施设备、场地的保养、维护、修补、改造等工作。4、监督检查外联商户的生产经营并掌握商户的基本情况。5、负责厂区防火安全，对公共消防设施、设备进行每天巡视检查发现问题及时处理，确保产区安全生产和无安全事故发生。6、加强对所管区域内环境卫生的管理。7、做好商户的投诉处理以及回访工作，搞好与商户之间的友好关系。8、加强安保工作的监管工作，严禁打架斗殴做到文明执勤、礼貌待人，为公司树立良好的形象。&lt;/p&gt;', 0, '', 0, '', '', 0, '', ''),
(39, '', '财务经理', '', '', 1, '', '&lt;p&gt;1、分析检查公司财务收支和预算的执行情况；2、为生产经营会议、合同协议的签订等工作提供信息，参与决策；3、组织其他会计人员学习业务，考核下属会计人员；4、定期组织财务部人员与其他部门的账务核对工作，定期组织与客户核对往来账务；5、督促公司应收账款的回收工作；6、组织财务部门参与对库存商品和固定资产的盘点、抽点工作；7、组织现金、银行存款的盘点工作；8、按程序做好与相关部门的横向联系，积极接受上级和有关人员的监督检查，对财务部门与其他部门之间的争议进行协调；9、完成上级领导交办的其他工作任务。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;1、广州本地人；财会、金融、经济、管理等相关专业大专以上学历，有中级会计资格证优先；2、5年以上财务管理或3年以上制造业管理工作经验；3、熟悉国家金融政策、企业财务制度及流程、会计电算化，精通相关财税法律法规；4、熟悉金蝶K3系统运用。&lt;/p&gt;', 0, '', ''),
(40, '', '财务经理', '', '', 1, '', '&lt;p&gt;1、分析检查公司财务收支和预算的执行情况；2、为生产经营会议、合同协议的签订等工作提供信息，参与决策；3、组织其他会计人员学习业务，积极为会计人员的调配晋升、聘任、辞退 等工作搞好服务；4、定期组织财务部人员与其他部门的账务核对工作，定期组织与客户核对往来账务；5、督促公司应收账款的回收工作；6、组织财务部门参与对库存商品和固定资产的盘点、抽点工作；7、组织现金、银行存款的盘点工作；8、按程序做好与相关部门的横向联系，积极接受上级和有关人员的监督检查，对财务部门与其他部门之间的争议进行协调；9、完成上级领导交办的其他工作任务。&lt;/p&gt;', 0, '', 0, '', '&lt;p&gt;1、广州本地人；财会、金融、经济、管理等相关专业大专以上学历，有中级会计资格证优先；2、5年以上财务管理或3年以上制造业管理工作经验；3、熟悉国家金融政策、企业财务制度及流程、会计电算化，精通相关财税法律法规；4、熟悉金蝶K3系统运用。&lt;/p&gt;', 0, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_news`
--

CREATE TABLE `ni_cloud_schema_news` (
  `ID` int(11) NOT NULL,
  `PRIMER` varchar(512) DEFAULT NULL,
  `SUBTITLE` varchar(512) DEFAULT NULL,
  `REPORTER` char(255) DEFAULT NULL,
  `MARKED` tinyint(1) DEFAULT '0',
  `PRESS` varchar(512) DEFAULT NULL,
  `ORIGINATE_URL` varchar(512) DEFAULT NULL,
  `PRESS_DATE` date DEFAULT NULL,
  `THUMB` varchar(512) DEFAULT NULL,
  `ABSTRACT` varchar(1024) DEFAULT NULL,
  `CONTENT` longtext,
  `X_ATTRS` longtext,
  `PHOTOGRAPHER` char(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_schema_news`
--

INSERT INTO `ni_cloud_schema_news` (`ID`, `PRIMER`, `SUBTITLE`, `REPORTER`, `MARKED`, `PRESS`, `ORIGINATE_URL`, `PRESS_DATE`, `THUMB`, `ABSTRACT`, `CONTENT`, `X_ATTRS`, `PHOTOGRAPHER`) VALUES
(1, '', '', NULL, 0, NULL, NULL, NULL, '/Users/Public/Themes/_PAGES/default/images/news/thumb.jpg', NULL, '&lt;img src=&quot;/Users/Public/Themes/_PAGES/default/images/news/figure.jpg&quot;&gt;\r\n&lt;p&gt;2017年6月16日，以“欢迎回家”为主题的来利洪食品集团2017年全国经销商大会暨新品发布会在广州来利洪集团总部隆重召开，全国数百名经销商家人欢聚一堂，共襄盛会，再续情谊。&lt;/p&gt;\r\n&lt;p&gt;大会正式开始前，在各厂生产主管的陪同下，经销商家人先后参观了瑞达饼干生产车间、溏心月饼生产车间以及来利洪饼干生产车间，了解近年更新的生产设备和生产技术。参观过程中，一尘不染的环境和先进的设备给大家留下了深刻的印象。&lt;/p&gt;\r\n&lt;p&gt;午后，会议在激动人心的倒数声中开始。集团创始人、董事长刘启洪在致辞中表示，来利洪创办至今已有35年历史，在座不乏合作时间长达十年以上的老客户，多年来相互合作，相互信任，相互支持，彼此间的情谊早已超越生意伙伴，胜似家人。今天很高兴能够与大家相聚于来利洪的大本营，预祝本次会议获得圆满成功，同时也祝愿各位家人朋友身体健康，事业顺利。&lt;/p&gt;\r\n&lt;p&gt;会上，集团总经理刘海陶重点介绍了集团旗下品牌溏心月饼的未来发展规划，伴随着品牌战略的调整，下阶段溏心将从“调性升级、形象升级、产品升级”三大方面进行品牌“迭代”，垂直细分市场，从“溏心1.0”跨入“溏心2.0”。此外，刘总揭幕了本次会议发布的主题新品——流心月饼，同时安排了现场试吃环节。流心月饼美味的口感让大家为之惊艳，不少经销商向工作人员要求“再来一碟”。刘总表示，作为溏心的匠心之作，流心月饼拟以品牌拳头产品的定位推向市场，集团将提供“渠道优化、形象提升、地推助力、媒体推广”四大维度的大力度市场支持。&lt;/p&gt;\r\n&lt;p&gt;为了进一步提高产品研发水平，集团特别聘请来自台湾的烘焙大师林甫青先生出任集团首席技术顾问，聘任仪式在本次大会中举行，总经理刘海陶代表集团，向林先生颁发了聘书及锦旗。林老师表示，他被来利洪的匠心精神深深打动，希望双方能共同研发出更多精致、纯粹的美食。&lt;/p&gt;', NULL, NULL),
(2, '', '', NULL, 0, NULL, NULL, NULL, '/Users/Public/Themes/_PAGES/default/images/news/thumb.jpg', NULL, '&lt;p&gt;&lt;img src=&quot;http://img.baidu.com/hi/jx2/j_0015.gif&quot;/&gt;&lt;/p&gt;&lt;p&gt;2017年6月16日，以“欢迎回家”为主题的来利洪食品集团2017年全国经销商大会暨新品发布会在广州来利洪集团总部隆重召开，全国数百名经销商家人欢聚一堂，共襄盛会，再续情谊。&lt;/p&gt;&lt;p&gt;大会正式开始前，在各厂生产主管的陪同下，经销商家人先后参观了瑞达饼干生产车间、溏心月饼生产车间以及来利洪饼干生产车间，了解近年更新的生产设备和生产技术。参观过程中，一尘不染的环境和先进的设备给大家留下了深刻的印象。&lt;/p&gt;&lt;p&gt;午后，会议在激动人心的倒数声中开始。集团创始人、董事长刘启洪在致辞中表示，来利洪创办至今已有35年历史，在座不乏合作时间长达十年以上的老客户，多年来相互合作，相互信任，相互支持，彼此间的情谊早已超越生意伙伴，胜似家人。今天很高兴能够与大家相聚于来利洪的大本营，预祝本次会议获得圆满成功，同时也祝愿各位家人朋友身体健康，事业顺利。&lt;/p&gt;&lt;p&gt;会上，集团总经理刘海陶重点介绍了集团旗下品牌溏心月饼的未来发展规划，伴随着品牌战略的调整，下阶段溏心将从“调性升级、形象升级、产品升级”三大方面进行品牌“迭代”，垂直细分市场，从“溏心1.0”跨入“溏心2.0”。此外，刘总揭幕了本次会议发布的主题新品——流心月饼，同时安排了现场试吃环节。流心月饼美味的口感让大家为之惊艳，不少经销商向工作人员要求“再来一碟”。刘总表示，作为溏心的匠心之作，流心月饼拟以品牌拳头产品的定位推向市场，集团将提供“渠道优化、形象提升、地推助力、媒体推广”四大维度的大力度市场支持。&lt;/p&gt;&lt;p&gt;为了进一步提高产品研发水平，集团特别聘请来自台湾的烘焙大师林甫青先生出任集团首席技术顾问，聘任仪式在本次大会中举行，总经理刘海陶代表集团，向林先生颁发了聘书及锦旗。林老师表示，他被来利洪的匠心精神深深打动，希望双方能共同研发出更多精致、纯粹的美食。&lt;/p&gt;', NULL, NULL),
(12, '', '', '', 0, '', '', '2018-03-07', '', '', '&lt;p&gt;&lt;span style=&quot;font-size:18px&quot;&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;234&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;56789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;1234567898765434&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;56&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;12345678987&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;6543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;span style=&quot;font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;&quot;&gt;123456789876543456&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;display: none;&quot;&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(13, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/9b96c29608430e5715205630555aa1f36fc3109.JPG', '', '&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;img src=&quot;/applications/uploads/files/5b0fd5dff6fc8b1615205656875aa1fdb8299fb.JPG&quot; title=&quot;20171018_IMG_2982.JPG&quot; alt=&quot;20171018_IMG_2982.JPG&quot; width=&quot;800&quot; height=&quot;500&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;2017年11月5日，关于来利洪食品集团的品牌宣传视频在广东广播电视台《品牌观察》栏目播出，约有近百万市民收看节目。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;《品牌观察》栏目立足广东，面向全国，是肩负“品牌强国”创业使命的官方电视台栏目，对各行业的国产品牌进行苛刻筛选，旨在挖掘国内优质品牌，与万千观众共同见证中国品牌的崛起。来利洪食品集团作为一家广东本土企业，创办至今已有三十余年，历经风雨屹立不倒，近年来更加快了前进的步伐，受到了《品牌观察》栏目的关注与邀请。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了客观、清晰地展示品牌背后的实力依托，栏目制作组获得集团的特别允许，2017年10月中旬，在集团总部开展为期2天的实地拍摄，并采访了多位部门主管。本次栏目重点聚焦于集团的现状与突破，作为一家已有三十多年历史的老牌企业，来利洪是否能够重新焕发新的活力。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;集团总经理刘海陶在采访中表示，为了在市场洪流中立于不败之地，自他上任以来，企业内部大力推行改革与创新，原有品牌要跟上市场变化，保住市场存量，同时也要推出新的品牌，吸引市场增量，可谓传承、创新两手抓。刘总坦言，在如今的市场环境中，不管是发展企业品牌还是产品品牌，面临的压力都要大于以往，但压力也是动力，作为企业家必须认准目标，带领团队勇往直前。刘总还提到，他的座右铭是感恩、饮水思源和利他，他也一直以这种处世态度经营企业，“企业背后的文化最终会影响到品牌，我们的品牌是有温度的，我想这也是消费者选择我们的原因之一。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;发展至今，来利洪食品集团旗下自有的生产线总价值超过亿元，日产能超过百吨，在国内与沃尔玛、永旺等大型商超建立了长久、稳固的合作关系，在海外市场亦表现不俗，受到全球零售商的青睐。如今，中国进入了品牌高速发展时期，市场竞争已经步入品牌竞争阶段。近年来，集团在品牌建设方面的投入不断增加，推广渠道不断拓宽，集团旗下卡慕、溏心、来利洪品牌的知名度获得了显著提升。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;本次有幸登上广东广播电视台《品牌观察》栏目，有赖于公众对来利洪食品集团及集团旗下各品牌的认可，未来，品牌建设与推广仍然是来集团的聚焦点之一，力争以品牌升级为驱动，助力企业加速发展。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(14, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/78163c7a64be12ed15205675475aa204fc40d7f.jpg', '', '&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&amp;nbsp;&lt;img src=&quot;/applications/uploads/files/78163c7a64be12ed15205675555aa20503cbc56.jpg&quot; title=&quot;2(142).jpg&quot; alt=&quot;2(142).jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了增强员工的安全防火意识，有序处理突发事故，提高在火灾中的自救、互救能力，2017年4月下旬，来利洪食品集团在厂区开展消防安全演习。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;演习前，大家首先进行了基本消防知识培训，了解关于火灾的预防和火场逃生技能，随后填写了调查问卷，最后在篮球场学习灭火器的正确使用方式。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;演习开始后，工厂各部门员工按照各部门负责人指示，从安全通道有序撤离，在规定的时间内，全体人员快速抵达紧急集合地点。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;本次消防安全演练，使员工详细了解了生产中的消防安全知识和预防火灾的对策，掌握了一定的灭火、火场逃生方法，为营造良好的工厂消防环境打下基础。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(15, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/c89ab33b7d9f4a5b15205678135aa20605e2d52.JPG', '', '&lt;p&gt;&lt;img src=&quot;/applications/uploads/files/c89ab33b7d9f4a5b15205678105aa206032c0df.JPG&quot; title=&quot;0IMG_7567.JPG&quot; alt=&quot;0IMG_7567.JPG&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;2017年6月16日，以“欢迎回家”为主题的来利洪食品集团2017年全国经销商大会暨新品发布会在广州来利洪集团总部隆重召开，全国数百名经销商家人欢聚一堂，共襄盛会，再续情谊。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;大会正式开始前，在各厂生产主管的陪同下，经销商家人先后参观了瑞达饼干生产车间、溏心月饼生产车间以及来利洪饼干生产车间，了解近年更新的生产设备和生产技术。参观过程中，一尘不染的环境和先进的设备给大家留下了深刻的印象。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;午后，会议在激动人心的倒数声中开始。集团创始人、董事长刘启洪在致辞中表示，来利洪创办至今已有35年历史，在座不乏合作时间长达十年以上的老客户，多年来相互合作，相互信任，相互支持，彼此间的情谊早已超越生意伙伴，胜似家人。今天很高兴能够与大家相聚于来利洪的大本营，预祝本次会议获得圆满成功，同时也祝愿各位家人朋友身体健康，事业顺利。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;会上，集团总经理刘海陶重点介绍了集团旗下品牌溏心月饼的未来发展规划，伴随着品牌战略的调整，下阶段溏心将从“调性升级、形象升级、产品升级”三大方面进行品牌“迭代”，垂直细分市场，从“溏心1.0”跨入“溏心2.0”。此外，刘总揭幕了本次会议发布的主题新品——流心月饼，同时安排了现场试吃环节。流心月饼美味的口感让大家为之惊艳，不少经销商向工作人员要求“再来一碟”。刘总表示，作为溏心的匠心之作，流心月饼拟以品牌拳头产品的定位推向市场，集团将提供“渠道优化、形象提升、地推助力、媒体推广”四大维度的大力度市场支持。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了进一步提高产品研发水平，集团特别聘请来自台湾的烘焙大师林甫青先生出任集团首席技术顾问，聘任仪式在本次大会中举行，总经理刘海陶代表集团，向林先生颁发了聘书及锦旗。林老师表示，他被来利洪的匠心精神深深打动，希望双方能共同研发出更多精致、纯粹的美食。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;此外，为了感谢经销商的支持和信任，董事长刘启洪和总经理刘海陶作为集团代表，向5位2016年度优秀经销商颁发了“突出贡献奖”证书，奖品为价值5888元的奖券。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;夜间，集团精心准备了豪华海鲜盛宴、热情歌舞表演以及总价值超过万元的抽奖礼品，与各位家人朋友们共享美好时光。品尝美食佳肴，欣赏精彩演出，抽取心动奖品，欢乐在空气中荡漾。最后，大家共同为来利洪大家庭举杯欢呼，比美酒更醇厚的情谊在觥筹交错间传递，本次会议至此圆满落幕。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(16, '', '', '', 0, '市场部', '', '2018-03-09', '/applications/uploads/files/5a37e9b0c55539f215205680045aa206c57e6a0.jpg', '', '&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;&lt;img src=&quot;/applications/uploads/files/5a37e9b0c55539f215205680205aa206d519545.jpg&quot; title=&quot;0微信图片_20170601151453.jpg&quot; alt=&quot;0微信图片_20170601151453.jpg&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;位于印度尼西亚的巴厘岛，有“天堂岛屿”之称，绮丽风光闻名于世，2017年3月末，来利洪食品集团邀请溏心月饼2015年度优秀经销商代表，飞赴巴厘岛开启梦幻之旅，集团总经理刘海陶一同出行。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;在为期5日的悠长旅程中，大家全程入住当地五星级酒店乐心登卡帕，深度游览充满魅力的巴厘风光，领略当地浪漫风土人情。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;碧玉般的南湾长滩、无尽浪漫的情人崖、暮色壮阔的金巴兰、神秘优雅的海神庙、气势恢宏的乌布皇宫、澄净动人的蓝梦岛、繁华热闹的库塔商圈……风情各异的景点，独一无二的民俗艺术，无不让人印象深刻，流连忘返。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;除了迷人美景，大家还体验了众多娱乐项目。如享受让人身心放松的特色SPA，乘坐惊涛骇浪就在脚下的玻璃船，在海龟岛上和长蛇、蝙蝠等让人心跳加速的动物“亲密接触，来一场激情四射的沙滩排球运动，自由选择浮潜、冲浪、香蕉船、滑水、拖曳伞等或休闲或刺激的水上运动，畅游于景致壮阔的无边泳池……尖叫和欢笑声中，所有的烦恼消失无踪，只留下轻松畅快的心情。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;人在他乡，异国美食自然不能少。数日里，大家尝遍巴厘岛美食，从特色烤肉、巴厘岛式中餐、海滩BBQ到精致海鲜大餐，更品尝了岛上独有的“脏鸭餐”。生猛的海鲜、独特的香料、与众不同的风味……诱人美食让人大快朵颐，酒酣耳热之际，也向彼此敞开心扉。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;气氛融洽的异国旅途中，欢声笑语不曾间断,大家互相帮助，共享美好时光，彼此从陌生到熟悉，从拘谨到放松，同为来利人的情谊在大家庭式的温暖中升华。留在沙滩上的脚印会被海水抹去，但留在心中充满了友爱与感动的美好记忆难以磨灭！&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;集团总经理刘海陶表示，感谢各位经销商一直以来对来利洪的支持和信任，大家来自天南海北，因为来利洪而有缘相聚，来利洪大家庭也因为大家的情谊变得温暖。愿所有来利人欢乐与共，情谊长存，年年岁岁，相聚来利洪！&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(17, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/63a61f96643f586515205820665aa23db2a72c5.jpg', '', '&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;img src=&quot;/applications/uploads/files/63a61f96643f586515205820645aa23db073d80.jpg&quot; title=&quot;微信图片_20170330100639.jpg&quot; alt=&quot;微信图片_20170330100639.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;导读&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;产品的质量决定了产品的生命力,一个公司的质量管理水平决定了公司在市场中的竞争力，来利洪集团作为“中国质量诚信企业”，始终把产品质量放在首位，坚持不断提高检测标准，不断完善质量保证体系，力求把“来利洪出品”打造成为优良品质的代名词。为了更进一步提高产品质量，强化员工质量意识，加强质量管理控制工作，在刚过去的3月，集团面向生产部和品质部全体员工，连续举办多场有关产品质量的主题培训。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;第一场：狠抓产品质量&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;3月8日上午，以产品质量为主题的第一场全员培训在集团大礼堂举行，由品质部主管梁福兴担任培训讲师。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;本场培训针对所有生产及品控一线员工，培训内容从基础知识循序渐，梁福兴以“什么是品质”的发问切入，分别从大众角度和专业角度详细阐述了品质的概念，明确指出“品质就是产品质量需要达到符合消费者要求和使用目的的程度”，纠正此前部分员工对品质概念的错误理解，批评对品质的抽象、感性认知，强调要以制定的产品标准为质量准绳，可以从合格率和经济成本等方面准确评估。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;根据事先的调查、研究及案例，梁福兴从第一步的筛粉到最后包装，详细列明在每道生产工序中存在的一些问题及可以继续改进的细节，从以毫克为单位的产品重量偏差到外包装日期喷码的清晰程度，涵盖每一步操作，每一个岗位，要求全员增强品质意识。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;梁福兴指出，“来利洪是‘中国质量诚信企业’，数十年来，消费者和经销商的信任扎根于稳定的产品质量。如今来利洪产品已经走出国门，在全球范围内销售，我们对品质的理念和要求也必须及时与国际接轨，这需要大家的一致观念和共同努力。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;本场培训引起了大家的热烈讨论和深刻反思，达到了更新品质观念和上紧质量发条的目的。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;第二场：严控安全卫生&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;3月8日下午，品管专员蔡远山面向全员开展“食品安全卫生知识”专题培训，培训内容涵盖食品安全和品质保证的重要性、食品中的危害、食品生产必须遵守的安全和卫生事项、工作现场6S管理和如何预防异物混入这五大方面的知识与要求。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了强调食品安全的重要性，蔡远山列举出食品有可能遭受的各类污染和可能造成的严重后果，随后给出十分详尽的预防措施供大家学习。此外他重点进行了安全和卫生事项培训，包括生产员工在入车间前、工作前、工作中和工作后的注意事项，具体到工作服的规范穿着和正确的净手方式，讲解细致入微。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;蔡远山指出，“食品是用良心托起的事业，我们要最大限度的预防食品安全卫生危害的发生！”&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;第三场：强调验厂标准&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了让员工们了解验厂的形式和审核要求，形成强烈的规范意识，3月17日，以“验厂要求”为主题的第三场培训面向全员举行，主讲人为品管专员蔡远山。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;经过不懈努力，集团目前已通过HACCP、ISO9001、ISO22000、BRC、FDA等多重组织机构的认证，并获得相应的资质证书，也因此每年需要经过各方总共多达近20次的工厂检验。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;验厂对生产现场、仓库、化验室，甚至整个厂区都有严格要求，这要求全体员工都要具备规范意识，做好每一道细节，顺利通过每一次的检验，也在不断提高的标准下，共同推进产品品质的提升。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;培训中展示了大量的不达标场景和正确示例，以实拍图片形式简明清晰的让大家了解到每一条标准，得到了大家的高度认可，取得良好的培训效果。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;第四场：生产过程中CCP点的分析和预防&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;3月17日，现场品管组长欧媚针对以往偶有出现的异物问题，开展“CCP点的分析和预防”专题培训（CCP点：食品安全关键控制点）。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;欧媚在培训一开始就指出，产品中出现异物，不仅会遭到客户投诉，更有可能影响到企业的正常经营，这要求全员高度重视异物问题，在生产和品控过程中要做到严防死守，全力杜绝。为了进一步强调异物的危害性，她还向大家普及了国家《食品安全法》中的相关法规。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;培训中，欧媚展示了客户在投诉时发来的实物佐证图，直观的让大家看到异物的种类和存在的形式，并对异物出现的原因进行详细分析。她表示，“虽然大多数都是绒毛、小黑点等细微之物，可是一旦出现，就会对企业形象带来极为负面的影响。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了从源头杜绝异物，欧媚从人、机、法、料、环，详细列出了有可能引入异物的每一点漏洞和需要额外注意之处，随后一一指出了预防或改善的措施，最后要求每个班组寻找3个有异物风险的物件，力求做到发现并解决每一个异物隐患。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;&lt;br/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;总结&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;民以食为天，近年来我国食品安全事故频发，大众也对国产食品质量心存疑虑，在这种背景下，来利洪集团坚持抓生产、提质量，以高于国家标准的质量指标进行自我要求，希望把品质安全、优良、稳定的食品带给中国，乃至全球的消费者。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;在本次质量月中，集团面向生产、品管、化验、仓储、后勤等全线岗位，开展了共计4场全员培训及多场针对性部门培训，达到了较为理想的培训效果。未来，集团将继续不定时开展各类培训，不忘初心，坚持追求、打造高质量产品。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(18, '', '', '', 0, '', '', '2018-03-09', '/applications/uploads/files/0949faef6bbc64d615205822635aa23e7835337.jpg', '', '&lt;p&gt;&lt;img src=&quot;/applications/uploads/files/0949faef6bbc64d615205822685aa23e7c9f0a4.jpg&quot; title=&quot;aIMG_0669.jpg&quot; alt=&quot;aIMG_0669.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;凡走过必留下痕迹，凡付出总有收获。2017年3月3-6日，6位在2016年工作表现突出的优秀员工，获得集团奖励，前往拥有“东方夏威夷”美誉的国际知名旅游城市——三亚，尽情游玩。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;在四天的旅途中，一行人除了游览西岛、亚龙湾、玫瑰谷等多个景点和入住5星级酒店，还遍尝了沿途的特色美食。正如集团所望，大家住得舒适、吃得开心、玩得尽兴。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;作为此行的参与者，人事部的曹嘉妍表示，“感谢集团的奖励，一直以来，我只是努力尽最大程度的做好本职工作，很感动自己的付出得到了认可。在来利洪，我相信只要努力付出了，就一定会有收获。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;来利洪一向重视人才，鼓励员工树立主人翁精神——“想主人事、干主人活、尽主人责、享主人乐”，希望与越来越多的优秀员工共同推动企业发展，分享更多的成果和快乐。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(19, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/250ed60268de1f9915205823815aa23eee6423b.jpg', '', '&lt;p style=&quot;text-align: justify; margin-bottom: 10px;&quot;&gt;&lt;img src=&quot;/applications/uploads/files/250ed60268de1f9915205823905aa23ef6c333c.jpg&quot; title=&quot;465232270111502849.jpg&quot; alt=&quot;465232270111502849.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify; margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了表达集团对员工优异工作成绩的肯定，2017年3月6-11日，外贸部6名优秀员工获集团赞助，飞赴韩国享受长达一周的悠长假期。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify; margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;韩国之旅的行程安排丰富多彩。除了游玩主题乐园、浪漫海岛，漫步文化、购物街区，大家还参观了热门韩剧的拍摄地，一路上更尝试了众多异国美食，体验韩式民俗文化。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify; margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;在众多景点中，紫菜博物馆给一行人留下了深刻的印象，作为亚洲人日常饮食中常见的一员，不起眼的紫菜中原来包含了许多学问。这一如来利洪对产品品质的极致追求，从每一袋原材料，到每一道工艺，只有深入研究和反复尝试，最终才能造就优良品质。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify; margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;工作也是如此，在出色的成绩背后，是认真的态度、严谨的细节和不计较的付出。天道酬勤，2017年，希望各部门都能有更佳表现，收获更多的成就和奖励。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(20, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/673a57aaa1e9e5bd15205825165aa23f7536092.jpg', '', '&lt;p&gt;&lt;img src=&quot;/applications/uploads/files/673a57aaa1e9e5bd15205825415aa23f8e5aa77.jpg&quot; title=&quot;aIMG_2774.jpg&quot; alt=&quot;aIMG_2774.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;为了提高团队凝聚力，进一步增强团队精神，2017年3月27-29日，集团组织生产部全体员工前往清远先锋拓展基地开展为期3天2夜的户外拓展活动。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;本次拓展活动主题为“激发无限潜能，打造卓越团队”，拓展内容包含再接再厉、怪兽过河、极限时速、信任倒和高空挑战等经典项目。活动以小组形式开展，从打破隔阂开始，逐步深入，考验团队的团队意识、沟通能力、任务分解能力、协作水平、责任感和意志力等综合素质。在活动的过程中，众人挥洒汗水，越挫越勇，最终收获掌声。历经一次次勇敢的爆发、不放弃的坚持，每个人都完成了新一轮的成长蜕变。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;通过高度参与充满挑战的拓展活动，大家深刻认识到，团队的力量远远大于个人，团队的重要性远远高于个人，而团队的成功离不开团队成员高度的责任感、互助的合作精神和温和有效的沟通，团队精神在完成共同目标后得以升华。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;一位在来利洪工作十余年的老员工动情地表示：“小组由个人组成，部门由小组组成，企业由部门组成，都是大大小小的团队。小团队的进退会影响到大团队的进退，身为团队里的一员，我们一定要有强烈的责任感，心怀感恩，共同进退。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;拓展活动是短暂的，但相信已经给每一位参与者带去了深刻的启示，团队的凝聚力和向心力都得到提高，生产部全员日后必将以更佳的状态投入到工作当中。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(21, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/2dd92daf5bc4972115205827325aa2404ce829d.jpg', '', '&lt;p&gt;&lt;img src=&quot;/applications/uploads/files/2dd92daf5bc4972115205827385aa2405369616.jpg&quot; title=&quot;微信图片_20170422085929.jpg&quot; alt=&quot;微信图片_20170422085929.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;近年来，集团生产能力逐步增强，生产品种陆续增多，为了让员工熟悉新款设备，掌握生产技巧，2017年四月中旬，来利洪食品集团生产部罗业祠等5名年轻骨干赴四川成都温江进行为期1个月的学习培训。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;本次学习任务繁重，但意义重大，在陌生而艰苦的学习环境中，罗业祠等员工以坚定的毅力，克服困难，虚心求知，在较短时间内掌握了某款大型新设备的拆、装步骤和零部件更换方法，达到了较好的学习效果。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(22, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/1c7e03a0113ebbba15205829465aa24123cb5d1.jpg', '', '&lt;p&gt;&lt;img src=&quot;/applications/uploads/files/1c7e03a0113ebbba15205829545aa2412ac610c.jpg&quot; title=&quot;微信图片_20170708141952.jpg&quot; alt=&quot;微信图片_20170708141952.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;2017年5月中旬，第十八届SIAL China中国国际食品和饮料展览会在上海新国际博览中心正式开幕。本届中食展展示面积达149500平方米，规模盛大，参展商皆为行业领军及新秀，作为业内知名企业，来利洪食品集团受邀参展。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;会上，在瞄准中秋节的众多食品中，来利洪食品集团旗下的溏心月饼一如既往受到新老客户热捧。溏心月饼为集团引入内地的香港经典月饼品牌，凭借浓郁的港式情怀、老少咸宜的口味和精美大气的包装，畅销多年，现已成为港式月饼的品牌代表之一。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;卡慕品牌饼干同样大受欢迎，来利洪食品集团拥有三十余年的饼干生产经验，近年更引入了欧洲生产工艺，生产的饼干片片香脆，滋味浓郁，多重风味不断出新，现已逐渐成为广为消费者喜爱的大众休闲零食。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;一位合作长达十余年的客户朋友专程来到集团展位，他表示，“合作多年，见证了来利洪的发展，尤其是在产品质量方面的不断提升，我很有信心。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;来利洪食品集团始终关注市场发展，迎合市场需求，在烘焙食品业内不断深耕，以生产技术革新和产品创新保持企业的生命力。未来，集团将透过精准的市场定位，抓紧新时代消费者的产品诉求点，持续拓深市场，愿为消费者送上更多优质食品，携手各位合作伙伴再创辉煌！&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(23, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/0017a1f2c8500b9b15205830675aa2419c7200d.jpg', '', '&lt;p&gt;&lt;img src=&quot;/applications/uploads/files/0017a1f2c8500b9b15205830735aa241a18975b.jpg&quot; title=&quot;微信图片_20170708154259.jpg&quot; alt=&quot;微信图片_20170708154259.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;2017年6月，来利洪食品集团积极参与由阳春市岗美镇人民政府、阳春市扶贫办等单位共同举办的“大爱有声”扶贫活动，向阳春当地贫困家庭捐赠总价值10万元物资。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;来利洪食品集团一直热心参与各类社会慈善公益活动，多年来，已累计为公益事业及扶贫项目捐赠超过1800万元，不断达成集团“制造中国好食品、成就来利人梦想、回馈社会”的企业使命。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(24, '', '', '', 0, '来利洪', '', '2018-03-09', '/applications/uploads/files/51e9ee3dd370b30415205833195aa242975766e.jpg', '', '&lt;p&gt;&lt;img src=&quot;/applications/uploads/files/51e9ee3dd370b30415205833175aa24295cb8ae.jpg&quot; title=&quot;微信图片_20170916154100.jpg&quot; alt=&quot;微信图片_20170916154100.jpg&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;9月中旬，来利洪食品集团旗下品牌——溏心月饼，携2017年全新力作流心月饼在山城重庆刮起一股充满香港风情的“流心风暴”，引发全城瞩目，吸引万人参与活动。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;活动选址重庆人气聚集的万达广场及凯德广场，在商场中庭搭建立体香港街景与大型双层巴士模型，缤纷现代的场景化身拍照胜地，让路人纷纷驻足留影。除了流心月饼试吃小推车，活动现场更准备了夹娃娃机及上万份精美礼品，同时联合手机端微信小游戏，在趣味互动中送出总价值超过30万元的流心月饼优惠券，提前为重庆消费者献上中秋礼物及祝福。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;溏心月饼起源于香港，壮大于内地城市，大气的品牌风格及独特的港式月饼口味一直备受好评，近年来在重庆市场尤其受欢迎。通过本次活动，品牌把港式滋味和港式风情进一步传播至重庆等地，获得更多消费者的认可，现场准备的数千盒流心月饼迅速被一扫而空，周边商超也几度出现断货现象。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;“有新鲜感”、“口感很特别”、“好吃”、“颜值高”……这些都是重庆吃货为流心月饼贴上的标签。据了解，多位重庆美食圈的意见领袖，还在朋友圈自发为溏心流心月饼进行宣传，而在腾讯大渝网发起的“十款流心月饼试吃测评”活动中，溏心流心月饼更力压同品类，一举夺冠，一度出现在多个网络平台的新闻报道中。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;流心月饼作为2017年品牌主打产品，采用先进芝士、奶黄注心工艺，原材料精挑细选，成品色泽金黄，口感更加细腻，层次丰富，流心层一口惊艳，是溏心月饼的又一用心之作，完成了2017年港式月饼的传承与创新。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;下一个中秋佳节，期待溏心月饼的更佳表现。&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL),
(25, '', '', '', 0, '中国食品科技网', '', '2018-03-09', '/applications/uploads/files/bdcea5e3ec01136a15205849735aa2490dc78a0.jpeg', '', '&lt;p style=&quot;text-align: center; margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;&amp;nbsp;&lt;img src=&quot;/applications/uploads/files/bdcea5e3ec01136a15210769075aa9caac242c6.jpeg&quot; title=&quot;mp60855879_1456570191542_15.jpeg&quot; alt=&quot;mp60855879_1456570191542_15.jpeg&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-align: justify; margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;饼干是以小麦粉、糖类、油脂等为主要原料经机制焙烤而成的食品。储存时间长,口感疏松，口味多样，老少咸宜，深受广大消费者的喜爱。&amp;nbsp;　　&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 市场上销售的饼干种类繁多，按生产工艺可分为：酥性饼干、韧性饼干、发酵饼干、压缩饼干、曲奇饼干、夹心饼干、威化饼干、蛋圆饼干、蛋卷及煎饼、装饰饼干、水泡饼干等。&amp;nbsp;　　&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 饼干选购注意事项：&amp;nbsp;　　&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 1、购买场所：首选正规或规模比较大的商场和超市。&amp;nbsp;　　&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 2、购买品牌：选择规模比较大的企业和知名品牌，这些企业的质量意识比较强，产品质量较好。&amp;nbsp;　　&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 3、看标签：应看产品名称、生产厂名、厂址、电话、执行标准、配料表是否齐全，是否在保质期内，还应看清配料表中的各种配料；同时标签上必须有QS标志。&amp;nbsp;　　&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 4、看包装：选购包装质量好的产品，好的产品包装可避免流通过程中引起的二次污染。&amp;nbsp;&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 5、看感官：质量好的饼干应外形完整，无收缩、变形。用手掰易折断，无杂质。&amp;nbsp;　　&amp;nbsp;&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; 6、气味和滋味鉴别：质量好的饼干应具有产品特有的香味，甜味纯正，酥松香脆，无异味，不粘牙。&lt;/span&gt;&lt;/p&gt;', '', NULL),
(26, '', '', '', 0, '中国食品网', '', '2018-03-09', '/applications/uploads/files/d2fbddcf0022f66f15205852825aa24a4336f16.jpg', '', '&lt;p style=&quot;margin-bottom: 10px; text-align: center;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;&lt;img src=&quot;/applications/uploads/files/d2fbddcf0022f66f15210768925aa9ca9c7d899.jpg&quot; title=&quot;20170925170547_52934.jpg&quot; alt=&quot;20170925170547_52934.jpg&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;中秋在即，花样百出的“网红月饼”越来越火，成为受人追捧的“香饽饽”。这些“网红月饼”适应了网购的新趋势，为顾客提供了诸多便利，但也存在着一些隐患。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;穿上名牌“马甲”以次充好&lt;/span&gt;&lt;/strong&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;近日，不少消费者投诉，通过网络购买的香港美心、半岛酒店等知名品牌月饼是假货。有的月饼已经发霉，有的馅里夹杂头发、铁丝等异物。深圳警方侦查发现，这些假冒大牌的月饼来自于当地一家&lt;/span&gt;&lt;a href=&quot;http://www.cnfood.cn/&quot; target=&quot;_blank&quot; style=&quot;color: rgb(127, 127, 127); text-decoration: underline;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;食品&lt;/span&gt;&lt;/a&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;公司，被冒牌的月饼多达数十种。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;据知情人士透露，这些“网红月饼”从广东陆丰一家月饼生产厂进货，成本低廉，批发价是８５元左右，在深圳包装后，售价２００多元一盒。主要在淘宝和微信销售，遍及广东、浙江、云南、江苏等全国多地。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;制售冒牌月饼的团伙分工明确，从月饼生产、包装生产到销售环节，都有专人负责，独立运作。记者在微信上看到，从今年８月起，团伙在朋友圈发布广告招揽生意，视频和图片上，金灿灿的奶黄月饼，令人垂涎欲滴。商家声称：“２００箱半岛月饼发往上海，第二天即可到货”。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;与编造的美丽假象相比，生产“网红月饼”的“黑作坊”不堪入目。厂房臭气熏天，大量长毛发霉的过期月饼堆放着，等候回炉再造，苍蝇老鼠四处乱窜。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;令人想不到的是，这样制作的月饼竟然供不应求。月饼厂老板由于产能不足，曾四处打听哪里能提供每天一万个以上的月饼。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;穿着名牌“马甲”吸引消费者，内里却是滥竽充数。不久前，贵州警方也查处了一个假冒伪劣月饼的黑窝点。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;据民警调查，这间工厂的老板雇佣几名工人，以１０元一斤的价格在社会上购买散装月饼，再用高档月饼包装盒加以包装，销往乡镇农村等地。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;手工”成噱头，“私家”月饼工厂造&lt;/span&gt;&lt;/strong&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;除了假冒知名品牌，记者观察发现，在淘宝、微店等网络平台，贴着“私家”“手工自制”等标签的“私房”月饼备受热捧。淘宝上输入“私家”“手工”月饼关键词，网页会跳出各种标签为“网红”月饼、纯手工月饼、自制月饼等的商家，不少商家月销量五六千件。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;记者点开其中一家自称“手工月饼纯礼盒包装”的店铺，一盒６只装、标价９８元的商品中，有的月饼是黄色心型、有的是粉红色花朵型，有的是金黄色菠萝造型，色泽诱人造型精致。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;除了月饼本身“颜值”颇高，包装也颇具“调性”，硬纸壳加红色礼带装帧，精美而典雅。有买家留言，“包装很精致，月饼样式也非常好看。送礼也显得高档。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;然而，不少标榜“手工制作”的月饼，只是吸引消费者的噱头。有商家客服向记者透露，这些“手工”月饼主要依靠工厂机器生产，只在部分制作流程上，使用了手工代替机器。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;手工烘焙爱好者一鑫告诉记者，虽然会做些月饼自己吃或送朋友，但真要是对外销售商业化，成本上扛不住。自己做馅料繁琐、费时，网购皮和馅料，材料制作过程是否卫生、是否含有添加剂就难讲了。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;华南农业大学&lt;/span&gt;&lt;a href=&quot;http://www.cnfood.cn/&quot; target=&quot;_blank&quot; style=&quot;color: rgb(127, 127, 127); text-decoration: underline;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;食品&lt;/span&gt;&lt;/a&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;学院教授赵力超认为，根据食品安全国家标准，月饼的检验项目有２０多个，朋友圈等渠道销售的“私家”月饼没有经过相关检验，存在安全隐患。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;strong&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;强化监管，不让“问题月饼”漏网&lt;/span&gt;&lt;/strong&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;广东胜伦律师事务所律师郑明认为，月饼从实体店走向虚拟网店，销售渠道日趋网络化，隐蔽性强，监管难度越来越大。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;“食品销售的主体资质门槛低，网上海量存在的小商家，更难以有效监管。销售平台基本审查义务不到位，执法层面也需要形成合力。”郑明说。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;但网络不是法外之地。郑明告诉记者，按照现行法律法规，将过期月饼回炉再造，以次充好等行为，涉嫌生产、销售不符合安全标准的食品罪等多个刑法罪名，如果加入非食品原料，甚至涉嫌生产销售有毒有害食品罪，最高可判死刑。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;“随着相关法规的完善，违法成本并不低，只是被查办的概率低。”郑明说，“没有体现法律的威慑力。”&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;打击假冒伪劣，离不开群众的支持。一名法官告诉记者，根据现行法律，销售明知是不符合安全标准的食品，消费者可以主张“１０倍赔偿”，加重对经营者欺诈行为的惩罚力度。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;margin-bottom: 10px;&quot;&gt;&lt;span style=&quot;color: rgb(127, 127, 127);&quot;&gt;此外，执法层面也需整合资源、精准打击。广东省食品药品监督管理局表示，将加强与电商平台建立协作机制；利用信息化手段对互联网食品药品重点违法信息进行动态监测，及时发现查处违法行为；并加强与公安、检察院的联动协作，完善互联网领域食品药品行政执法与刑事司法衔接，提升打击成效。（记者毛一竹、周颖、周科）&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL);
INSERT INTO `ni_cloud_schema_news` (`ID`, `PRIMER`, `SUBTITLE`, `REPORTER`, `MARKED`, `PRESS`, `ORIGINATE_URL`, `PRESS_DATE`, `THUMB`, `ABSTRACT`, `CONTENT`, `X_ATTRS`, `PHOTOGRAPHER`) VALUES
(27, '', '', '', 0, '中国食品报', '', '2018-03-09', '/applications/uploads/files/4e948ac89b97d9e215205855195aa24b2f7b0c9.png', '', '&lt;p style=&quot;text-align: center;&quot;&gt;&lt;img src=&quot;/applications/uploads/files/4e948ac89b97d9e215210768525aa9ca7586bae.png&quot; title=&quot;20180129093638833.png&quot; alt=&quot;20180129093638833.png&quot;/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;寻找适合自身发展的突破口&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;本报讯 （记者鲍小铁）由中国焙烤&lt;a href=&quot;http://www.cnfood.cn/&quot; target=&quot;_blank&quot;&gt;食品&lt;/a&gt;糖制品工业协会（以下简称“中焙糖协”）饼干专业委员会主办，广州刚奇包装有限公司承办的2017年中焙糖协饼干专业委员会理事会暨行业年会，于近日在广东省珠海市召开。中国轻工业联合会兼职副会长、中焙糖协理事长朱念琳，中焙糖协副理事长兼秘书长张九魁，国家&lt;a href=&quot;http://www.cnfood.cn/&quot; target=&quot;_blank&quot;&gt;食品&lt;/a&gt;安全风险评估中心二处主任张俭波，协会技术法规专业委员会秘书长陈岩，全国糕点专业委员会副秘书长俞嘉毅，广州刚奇包装有限公司董事长苏明智等出席。&lt;/p&gt;&lt;p&gt;会上，朱念琳理事长就2017年焙烤行业形势做了分析。朱念琳指出，2017年糕点面包业销售额增长在10%以上，利润由两位数降为个位数，而饼干业销售额增长则在10%以下，利润出现负增长。总体看来，主要原因来自以下几方面：人力及原材料等成本上升、企业税负重、环保治理、出口萎缩、个性化定制及纯进口食品的冲击等。“饼干业正面临着转型，在新形势下探索一条传统饼干产品的发展之道，是目前大家急需探讨和思考的。”朱念琳说。&lt;/p&gt;&lt;p&gt;张俭波主任通报了食品安全国家标准相关情况及企业应该关注的重点，他从标准制定的意义、流程及后期跟踪反馈等做了详细阐述，使与会代表对标准有了清晰全面的认识。&lt;/p&gt;&lt;p&gt;陈岩秘书长作了行业相关标准情况介绍。参会企业代表就目前现状、存在的问题和发展方向进行了深入的探讨与交流。&lt;/p&gt;&lt;p&gt;中焙糖协饼干专委会秘书处向大会提议俞嘉毅同志担任全国饼干专业委员会副秘书长，并获得一致通过。会议还向参会代表印发了《2016～2017年度饼干及概况与展望》、《2016～2017年度焙烤食品糖制品行业发展概况及中焙糖协工作情况通报》。&lt;/p&gt;&lt;p&gt;朱念琳理事长在总结时强调，目前饼干行业不管从数据还是行业实情来看，都可以说形势严峻，企业利润下滑比较严重。建议大家从以下几方面入手：一是创新，不单是产品品种，还有与之相配套的设备、产品理念、宣传文案等；二是定位，要精准清晰，直抵目标消费人群痛点；三是精简大流通品种，提供差异化的产品、定制化的服务，这也是越来越多消费者所需求的；四是新时代的新消费模式，即互联网销售模式的运用；五是加快人才队伍培养、注意人才梯队建设。目前国内食品业大都面临着转型升级，新形势下要有新思路，积极寻找适合自身发展的突破口，保证行业健康可持续发展。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '', NULL);

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
-- 表的结构 `ni_cloud_schema_raw`
--

CREATE TABLE `ni_cloud_schema_raw` (
  `ID` int(11) NOT NULL,
  `GUID` char(64) NOT NULL,
  `NAME` varchar(128) NOT NULL,
  `X_ATTRS` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_schema_resume`
--

CREATE TABLE `ni_cloud_schema_resume` (
  `ID` int(11) NOT NULL,
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
  `review` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要复审',
  `comments` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许评论',
  `fields` longtext,
  `app_id` char(32) NOT NULL DEFAULT '0',
  `app_data` longtext COMMENT '应用为表拓展的属性，JSON',
  `SK_STATE` tinyint(1) NOT NULL DEFAULT '1',
  `SK_REVIEW` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_tablemeta`
--

INSERT INTO `ni_cloud_tablemeta` (`name`, `type`, `item`, `review`, `comments`, `fields`, `app_id`, `app_data`, `SK_STATE`, `SK_REVIEW`) VALUES
('articles', 'article', 'Article', 0, 1, '{}', 'WRITER', '{}', 1, 0),
('cloudnotes', 'note', 'Notes', 0, 1, '{}', '1001', '{}', 1, 0),
('encyclopedia', 'wiki', 'Article', 0, 1, '{}', '1002', '{}', 1, 1),
('gallery', 'album', 'Groups', 0, 1, '{}', '1003', '{}', 1, 1),
('news', 'news', 'News', 0, 1, '[]', '1006', '{}', 1, 0),
('persons', 'resume', 'Persons', 0, 1, '{}', '1005', '{}', 1, 1),
('positions', 'job', 'position', 0, 1, '[]', '1004', '{}', 1, 0),
('videos', 'artcle', 'Video', 0, 1, '{}', '1007', '{}', 1, 1);

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
  `UID` int(11) NOT NULL DEFAULT '-7' COMMENT '创建者用户ID',
  `PUBTIME` datetime DEFAULT NULL,
  `RANK` tinyint(1) NOT NULL DEFAULT '5' COMMENT '归档星等',
  `LEVEL` int(11) NOT NULL DEFAULT '0' COMMENT '排序级数',
  `SK_COMMENTS` tinyint(1) NOT NULL,
  `SK_CTIME` datetime NOT NULL,
  `SK_MTIME` datetime DEFAULT NULL,
  `SK_STATE` int(11) DEFAULT '1',
  `SK_IS_RECYCLED` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_tablerowmeta`
--

INSERT INTO `ni_cloud_tablerowmeta` (`ID`, `TYPE`, `TABLENAME`, `FOLDER`, `TITLE`, `DESCRIPTION`, `UID`, `PUBTIME`, `RANK`, `LEVEL`, `SK_COMMENTS`, `SK_CTIME`, `SK_MTIME`, `SK_STATE`, `SK_IS_RECYCLED`) VALUES
(1, 'news', 'news', 17, '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', -7, '2018-01-21 00:00:00', 5, 0, 0, '0000-00-00 00:00:00', '2018-03-09 16:07:03', 1, 1),
(2, 'news', 'news', 16, '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品', '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', -7, '2018-01-21 00:00:00', 5, 0, 0, '0000-00-00 00:00:00', '2018-03-09 16:54:01', 1, 1),
(3, 'job', 'positions', 0, '前台专员', '前台专员', -7, '2018-01-25 00:00:00', 5, 0, 0, '0000-00-00 00:00:00', '2018-03-16 09:40:25', 1, 1),
(4, 'job', 'positions', 0, '天猫运营', '天猫运营', -7, '2018-01-25 00:00:00', 5, 10, 0, '0000-00-00 00:00:00', '2018-03-16 09:40:22', 1, 1),
(6, 'job', 'positions', 0, '123456', '1234567', -7, '2018-03-06 13:49:14', 5, 0, 0, '2018-03-06 13:49:29', '2018-03-07 00:44:08', 1, 1),
(7, 'job', 'positions', 0, '34567890', '456789', -7, '2018-03-06 13:49:40', 5, 0, 0, '2018-03-06 13:49:50', '2018-03-07 00:44:06', 1, 1),
(10, 'job', 'positions', 0, '111111111111', '11111111111111111', -7, '2018-03-06 15:00:01', 5, 0, 0, '2018-03-06 15:00:07', '2018-03-07 00:44:02', 1, 1),
(12, 'news', 'news', 17, '广东广播电视台《品牌观察》栏目专访来利洪食品集团', 'p.p1 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: justify; text-indent: 21.0px; font: 10.5px \'PingFang SC\'; color: #000000; -', -7, '2018-03-07 10:04:56', 5, 0, 0, '2018-03-07 10:06:26', '2018-03-08 23:35:28', 1, 1),
(13, 'news', 'news', 17, '广东广播电视台《品牌观察》栏目专访来利洪食品集团', '2017年11月5日，关于来利洪食品集团的品牌宣传视频在广东广播电视台《品牌观察》栏目播出，约有近百万市民收看节目。《品牌观察》栏目立足广东，面向全国，是肩负“品牌强国”创业使命的官方电视台栏目，对各行业的国产品牌进行苛刻筛选，旨在挖掘国内优质品牌，与万千', -7, '2017-11-09 10:35:24', 5, 0, 0, '2018-03-09 10:37:45', '2018-03-09 11:48:57', 1, 0),
(14, 'news', 'news', 17, '来利洪食品集团在厂区开展消防安全演习', ' 为了增强员工的安全防火意识，有序处理突发事故，提高在火灾中的自救、互救能力，2017年4月下旬，来利洪食品集团在厂区开展消防安全演习。演习前，大家首先进行了基本消防知识培训，了解关于火灾的预防和火场逃生技能，随后填写了调查问卷，最后在篮球场学习', -7, '2017-04-28 11:49:12', 5, 0, 0, '2018-03-09 11:50:26', '2018-03-09 11:52:40', 1, 0),
(15, 'news', 'news', 16, '欢迎回家——祝贺2017来利洪食品集团全国经销商大会暨新品发布会圆满成功', '2017年6月16日，以“欢迎回家”为主题的来利洪食品集团2017年全国经销商大会暨新品发布会在广州来利洪集团总部隆重召开，全国数百名经销商家人欢聚一堂，共襄盛会，再续情谊。大会正式开始前，在各厂生产主管的陪同下，经销商家人先后参观了瑞达饼干生产车间、溏心', -7, '2017-06-19 11:55:12', 5, 0, 0, '2018-03-09 11:57:15', '2018-03-27 05:27:18', 1, 0),
(16, 'news', 'news', 17, '畅游天堂岛屿，共谱美好回忆 ——记溏心月饼2015年度优秀经销商代表巴厘岛梦幻之旅', '位于印度尼西亚的巴厘岛，有“天堂岛屿”之称，绮丽风光闻名于世，2017年3月末，来利洪食品集团邀请溏心月饼2015年度优秀经销商代表，飞赴巴厘岛开启梦幻之旅，集团总经理刘海陶一同出行。在为期5日的悠长旅程中，大家全程入住当地五星级酒店乐心登卡帕，深度游览充', -7, '2017-04-03 11:58:36', 7, 0, 0, '2018-03-09 12:00:25', '2018-03-27 06:00:09', 1, 0),
(17, 'news', 'news', 17, '全员参与，持续改进 —— 3月质量主题培训圆满召开', '导读    产品的质量决定了产品的生命力,一个公司的质量管理水平决定了公司在市场中的竞争力，来利洪集团作为“中国质量诚信企业”，始终把产品质量放在首位，坚持不断提高检测标准，不断完善质量保证体系，力求把“来利洪出品”', -7, '2017-03-17 15:53:03', 5, 0, 0, '2018-03-09 15:54:32', '2018-03-09 15:56:26', 1, 0),
(18, 'news', 'news', 17, '品尝美食，逐浪银滩——集团优秀员工三亚之行', '凡走过必留下痕迹，凡付出总有收获。2017年3月3-6日，6位在2016年工作表现突出的优秀员工，获得集团奖励，前往拥有“东方夏威夷”美誉的国际知名旅游城市——三亚，尽情游玩。在四天的旅途中，一行人除了游览西岛、亚龙湾、玫瑰谷等多个景点和入住5星级酒店，还', -7, '2017-03-10 15:56:39', 5, 0, 0, '2018-03-09 15:57:52', '2018-03-09 15:58:28', 1, 0),
(19, 'news', 'news', 17, '外贸部6名员工获集团奖励韩国旅游', '为了表达集团对员工优异工作成绩的肯定，2017年3月6-11日，外贸部6名优秀员工获集团赞助，飞赴韩国享受长达一周的悠长假期。韩国之旅的行程安排丰富多彩。除了游玩主题乐园、浪漫海岛，漫步文化、购物街区，大家还参观了热门韩剧的拍摄地，一路上更尝试了众多异国美', -7, '2017-03-20 15:58:36', 5, 0, 0, '2018-03-09 16:00:18', '2018-03-09 16:00:40', 1, 0),
(20, 'news', 'news', 17, '生产部全体员工赴清远开展户外拓展活动', '为了提高团队凝聚力，进一步增强团队精神，2017年3月27-29日，集团组织生产部全体员工前往清远先锋拓展基地开展为期3天2夜的户外拓展活动。本次拓展活动主题为“激发无限潜能，打造卓越团队”，拓展内容包含再接再厉、怪兽过河、极限时速、信任倒和高空挑战等经典', -7, '2017-04-03 16:01:25', 5, 0, 0, '2018-03-09 16:02:39', '2018-03-09 16:02:39', 1, 0),
(21, 'news', 'news', 17, '集团生产部骨干员工赴四川学习培训', '近年来，集团生产能力逐步增强，生产品种陆续增多，为了让员工熟悉新款设备，掌握生产技巧，2017年四月中旬，来利洪食品集团生产部罗业祠等5名年轻骨干赴四川成都温江进行为期1个月的学习培训。本次学习任务繁重，但意义重大，在陌生而艰苦的学习环境中，罗业祠等员工以', -7, '2017-04-18 16:04:28', 5, 0, 0, '2018-03-09 16:05:44', '2018-03-09 16:05:44', 1, 0),
(22, 'news', 'news', 17, '来利洪食品集团参加第十八届上海中食展', '2017年5月中旬，第十八届SIAL China中国国际食品和饮料展览会在上海新国际博览中心正式开幕。本届中食展展示面积达149500平方米，规模盛大，参展商皆为行业领军及新秀，作为业内知名企业，来利洪食品集团受邀参展。会上，在瞄准中秋节的众多食品中，来利', -7, '2017-05-18 16:08:02', 5, 0, 0, '2018-03-09 16:09:18', '2018-03-09 16:09:18', 1, 0),
(23, 'news', 'news', 17, '来利洪集团热心参与阳江扶贫公益活动', '2017年6月，来利洪食品集团积极参与由阳春市岗美镇人民政府、阳春市扶贫办等单位共同举办的“大爱有声”扶贫活动，向阳春当地贫困家庭捐赠总价值10万元物资。来利洪食品集团一直热心参与各类社会慈善公益活动，多年来，已累计为公益事业及扶贫项目捐赠超过1800万元', -7, '2017-06-28 16:09:49', 5, 0, 0, '2018-03-09 16:11:18', '2018-03-09 16:11:18', 1, 0),
(24, 'news', 'news', 17, '2017中秋佳节，溏心流心月饼热潮席卷重庆', '9月中旬，来利洪食品集团旗下品牌——溏心月饼，携2017年全新力作流心月饼在山城重庆刮起一股充满香港风情的“流心风暴”，引发全城瞩目，吸引万人参与活动。活动选址重庆人气聚集的万达广场及凯德广场，在商场中庭搭建立体香港街景与大型双层巴士模型，缤纷现代的场景化', -7, '2017-10-10 16:12:18', 5, 0, 0, '2018-03-09 16:15:24', '2018-03-09 16:15:24', 1, 0),
(25, 'news', 'news', 16, '饼干选购小常识', ' 饼干是以小麦粉、糖类、油脂等为主要原料经机制焙烤而成的食品。储存时间长,口感疏松，口味多样，老少咸宜，深受广大消费者的喜爱。 　　     市场上销售的饼干种类繁多，按生产工艺可分为：酥性饼干、', -7, '2017-01-12 16:41:15', 5, 0, 0, '2018-03-09 16:42:57', '2018-03-15 09:21:50', 1, 0),
(26, 'news', 'news', 16, '“网红月饼”成了“香饽饽”，暗藏哪些隐患？', '中秋在即，花样百出的“网红月饼”越来越火，成为受人追捧的“香饽饽”。这些“网红月饼”适应了网购的新趋势，为顾客提供了诸多便利，但也存在着一些隐患。　　穿上名牌“马甲”以次充好　　近日，不少消费者投诉，通过网络购买的香港美心、半岛酒店等知名品牌月饼是假货。有', -7, '2017-09-30 16:46:23', 5, 0, 0, '2018-03-09 16:48:05', '2018-03-15 09:21:35', 1, 0),
(27, 'news', 'news', 16, '我国饼干行业积极探索传统食品发展之道', '寻找适合自身发展的突破口本报讯 （记者鲍小铁）由中国焙烤食品糖制品工业协会（以下简称“中焙糖协”）饼干专业委员会主办，广州刚奇包装有限公司承办的2017年中焙糖协饼干专业委员会理事会暨行业年会，于近日在广东省珠海市召开。中国轻工业联合会兼职副会长、中焙糖协', -7, '2018-01-29 16:51:29', 5, 0, 0, '2018-03-09 16:53:27', '2018-03-15 09:21:16', 1, 0),
(28, 'job', 'positions', 0, '行政专员', '1、前台工作2、工牌、饭卡管理工作3、办公用品管理登记工作4、其它后勤辅助工作', -7, '2018-03-15 09:24:09', 5, 0, 1, '2018-03-15 09:27:27', '2018-03-23 08:52:03', 1, 1),
(29, 'job', 'positions', 0, '淘宝客服', '1、及时回复旺旺咨询及留言，准确有效地为不同顾客做推荐参考；2、能单独处理售前、售中、售后问题；3、配合领导完成既定工作指标。', -7, '2018-03-15 09:29:25', 5, 0, 1, '2018-03-15 09:31:33', '2018-03-23 08:50:55', 1, 1),
(30, 'job', 'positions', 0, '市场专员', '1.对目标市场进行资料搜集和数据分析与整理；2.主动参与市场活动前期准备及活动现场的相关工作（例如：促销活动、订货会、展会）；3..负责部门文档的建设与管理，以及为市场人员提供行政服务和支持；4.负责本部门与其他部门的协调工作，以及传达通知，通告等文件；5', -7, '2018-03-16 09:40:28', 5, 0, 1, '2018-03-16 09:42:19', '2018-03-23 08:51:05', 1, 1),
(31, 'job', 'positions', 0, '品质经理', '1、人员的管理与工作的监督；2、品质控制与异常处理；3、工作的指导与标准的确认；4、仪器设备工装夹具的管理；5、6S的推行与监督；6、建立质量信息库；7、参与本组质量管理体的建立、推行、维护和相关文件、记录的整理和宣导；8、参与讨论或提出品质改善提案及建议', -7, '2018-03-16 09:42:30', 5, 0, 1, '2018-03-16 09:44:01', '2018-03-16 09:47:58', 1, 0),
(32, 'job', 'positions', 0, '采购跟单', '1、主持采购部工作，领导采购部门配合相关部们做好本职工作。2，根据项目销售计划和生产计划制订采购计划，并督导实施。3、制定本部门的物资管理相关制度，使之规范化。4、制定物料采购原则，并督导实施。5、做好采购的预测工作，根据资金运作情况，材料堆放程度，合理进', -7, '2018-03-16 09:44:17', 5, 0, 1, '2018-03-16 09:45:54', '2018-03-16 09:45:54', 1, 0),
(33, 'job', 'positions', 0, '维修电工', '1.维修办公大楼的水电，对办公大楼的水电每周进行一次检查及维护。2.维修员工宿舍楼的水电，保障员工的福利和生活环境。3.维修饭堂水电，保证饭堂的正常运行。4.根据本司需求做一些简单的水泥工程。5.收集办公大楼、员工宿舍和饭堂的环境信息。6.根据工作环境对上', -7, '2018-03-16 09:46:34', 5, 0, 1, '2018-03-16 09:47:29', '2018-03-16 10:03:42', 1, 1),
(34, 'job', 'positions', 0, '生产计划/计划专员', '1.根据销售部门订单，做出每个月的生产滚动计划；2.分析长期生产计划，根据定员，做出滚动人员需求计划；3.定期召开计划会议，充分沟通，保证生产计划的顺利进行；4.做好部门的耗材计划，保证生产活动的正常进行；5.关注半成品仓库存，减少过期不良品的出现；6.关', -7, '2018-03-16 09:49:33', 5, 0, 1, '2018-03-16 09:50:29', '2018-03-16 09:50:29', 1, 0),
(35, 'job', 'positions', 0, '面包师傅', '1、面包车间生产管理（1）严格拟定和执行本部门的产品工艺流程和配方的配比和本部门相关的成品检验标准，保密公司有关产品研发、制作等文件。（2）按工作计划精心加工和独立制作面包类产品，严格把控配料配比，督导检查产品的制作方法、操作规程、制作质量、摆放形式、方式', -7, '2018-03-16 09:51:03', 5, 0, 1, '2018-03-16 09:52:33', '2018-03-22 14:26:49', 1, 0),
(36, 'job', 'positions', 0, '销售代表', '1、制定并完成业绩指标，保证公司销售策略的贯彻推行，以及完成主管交待的其他任务。2、根据公司产品策略，协助开展策划案及品牌推广；3、提升区域或渠道重点产品的渗透率，均衡发展品类并优化产品结构，扩大市场份额；4、与行业风向标客户以及重点终端客户建立紧密的合作', -7, '2018-03-16 09:53:21', 5, 0, 1, '2018-03-16 09:53:54', '2018-03-16 09:58:38', 1, 0),
(37, 'job', 'positions', 0, '总账会计', '1.负责费用报销的审核，审批报销后及时录入系统，并完成账务处理；2.每月核对考勤表和工资表，上级领导审批签字后给出纳，发放后在系统制单；3.负责收付款项单据的账务处理，月末与总公司对账；4.了解公司产品组成并在系统做物料清单；5.与采购及销售核对应收及应付', -7, '2018-03-16 09:54:16', 5, 0, 1, '2018-03-16 09:54:57', '2018-03-16 09:54:57', 1, 0),
(38, 'job', 'positions', 0, '物业管理经理', '1、抓好每月商户的费用收缴，对拖欠费用的商户进行及时催缴，严禁有商户长期欠费或欠费走人情况存在。2、做好每月水电表的抄录以及收费通知单的派发工作。3、配合处理水电突发事件的抢修工作，做好公共设施设备、场地的保养、维护、修补、改造等工作。4、监督检查外联商户', -7, '2018-03-16 09:55:08', 5, 0, 1, '2018-03-16 09:56:07', '2018-03-22 14:25:43', 1, 0),
(39, 'job', 'positions', 0, '财务经理', '1、分析检查公司财务收支和预算的执行情况；2、为生产经营会议、合同协议的签订等工作提供信息，参与决策；3、组织其他会计人员学习业务，积极为会计人员的调配晋升、聘任、辞退 等工作搞好服务；4、定期组织财务部人员与其他部门的账务核对工作，定期组织与客户核对往来', -7, '2018-03-16 09:56:15', 5, 0, 1, '2018-03-16 09:57:33', '2018-03-22 14:20:44', 1, 0),
(40, 'job', 'positions', 0, '财务经理', '1、分析检查公司财务收支和预算的执行情况；2、为生产经营会议、合同协议的签订等工作提供信息，参与决策；3、组织其他会计人员学习业务，积极为会计人员的调配晋升、聘任、辞退 等工作搞好服务；4、定期组织财务部人员与其他部门的账务核对工作，定期组织与客户核对往来', -7, '2018-03-16 09:56:15', 5, 0, 1, '2018-03-16 09:57:33', '2018-03-16 09:57:50', 1, 1);

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
-- 表的结构 `ni_pageads_ads`
--

CREATE TABLE `ni_pageads_ads` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `type` char(10) NOT NULL DEFAULT 'image',
  `content` longtext,
  `link` varchar(512) DEFAULT NULL,
  `display` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
('logo', '/applications/uploads/files/ba702c3ea1fe0e5c15205657045aa1fdc9c18d9.jpg', 1),
('default_page_content', '', 1),
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
(6, 0, '<DEF>', '/admin/', 'EYUTOUADMIN', 0, '{}', 1);

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
(1, 0, 'YangRAM Contacts', 'CT', 'YangRAM', 'AdminSuit/Contacts/', 0, '0000-00-00 00:00:00'),
(2, 0, 'YangRAM Registry', 'RG', 'YangRAM', 'AdminSuit/Registry/', 0, '0000-00-00 00:00:00'),
(1001, 1, 'YangRAM CloudNotes', 'CN', 'YangRAM', 'CloudTableHelpers/CloudNotes/', 0, '2018-01-19 05:06:03'),
(1002, 2, 'YangRAM Encyclopedia', 'EP', 'YangRAM', 'CloudTableHelpers/Encyclopedia/', 0, '2018-01-19 05:06:03'),
(1003, 3, 'YangRAM Gallery', 'GL', 'YangRAM', 'CloudTableHelpers/Gallery/', 0, '2018-01-19 05:06:03'),
(1004, 4, 'YangRAM Jobs', 'JB', 'YangRAM', 'CloudTableHelpers/Jobs/', 0, '2018-01-19 05:06:03'),
(1005, 5, 'YangRAM Persons', 'PS', 'YangRAM', 'CloudTableHelpers/Persons/', 0, '2018-01-19 05:06:03'),
(1006, 6, 'YangRAM Press', 'Pr', 'YangRAM', 'CloudTableHelpers/Press/', 0, '2018-01-19 05:06:03'),
(1007, 7, 'YangRAM Videos', 'VD', 'YangRAM', 'CloudTableHelpers/Videos/', 0, '2018-01-19 05:06:03'),
(1008, 1, 'YangRAM Storage Rack', 'SR', 'YangRAM', 'MallSuite/Storage/', 0, '0000-00-00 00:00:00'),
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
(6886, 0, 'Microivan  Fourm', 'Bg', 'MicroIvan', 'Fourm/', 0, '0000-00-00 00:00:00');

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
('administrators', 'studio', 1),
('administrators', 'settings', 2),
('tables', '0', 1),
('session', 'all', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ni_cloud_authorities`
--
ALTER TABLE `ni_cloud_authorities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tablename` (`tablename`,`auth_type`,`usergroup`);

--
-- Indexes for table `ni_cloud_comments`
--
ALTER TABLE `ni_cloud_comments`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `ni_cloud_schema_raw`
--
ALTER TABLE `ni_cloud_schema_raw`
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
-- Indexes for table `ni_pageads_ads`
--
ALTER TABLE `ni_pageads_ads`
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
-- 使用表AUTO_INCREMENT `ni_cloud_authorities`
--
ALTER TABLE `ni_cloud_authorities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `ni_cloud_comments`
--
ALTER TABLE `ni_cloud_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_cloud_filesrc`
--
ALTER TABLE `ni_cloud_filesrc`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- 使用表AUTO_INCREMENT `ni_cloud_folders`
--
ALTER TABLE `ni_cloud_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `ni_cloud_tablerowmeta`
--
ALTER TABLE `ni_cloud_tablerowmeta`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
-- 使用表AUTO_INCREMENT `ni_pageads_ads`
--
ALTER TABLE `ni_pageads_ads`
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
