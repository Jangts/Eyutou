-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-04-01 23:15:21
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
-- Database: `gzlaili`
--

-- --------------------------------------------------------

--
-- 表的结构 `ni_1008_brands`
--

CREATE TABLE `ni_1008_brands` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL COMMENT '所属类目ID',
  `brand_name` varchar(512) NOT NULL COMMENT '品牌名称',
  `brand_logo` varchar(1024) DEFAULT NULL COMMENT '品牌LOGO地址',
  `brand_desc` longtext COMMENT '品牌描述'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_1008_brands`
--

INSERT INTO `ni_1008_brands` (`id`, `category_id`, `brand_name`, `brand_logo`, `brand_desc`) VALUES
(1, 2, '卡    慕', '/applications/uploads/files/5552e2db7e9ffc9a15223140455abcab3d7ebf3.jpg', '卡慕'),
(2, 2, '溏    心', '/applications/uploads/files/2a6ca03f4ee60f2a15223140615abcab4df1660.jpg', '溏心'),
(3, 2, '来利洪', '/applications/uploads/files/62ade0e6b42134e215223146725abcadb12a77d.jpg', '来利洪'),
(5, 2, '出口产品', '/applications/uploads/files/d3ff99c7a3aafab915203534435a9ec0a3f3026.jpg', '出口产品');

-- --------------------------------------------------------

--
-- 表的结构 `ni_1008_categories`
--

CREATE TABLE `ni_1008_categories` (
  `id` int(11) NOT NULL COMMENT '主键',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级类目',
  `category_name` varchar(256) NOT NULL COMMENT '类目名称',
  `category_desc` varchar(1024) DEFAULT NULL COMMENT '类目描述'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='类目列表';

--
-- 转存表中的数据 `ni_1008_categories`
--

INSERT INTO `ni_1008_categories` (`id`, `parent_id`, `category_name`, `category_desc`) VALUES
(2, 0, '饼干糕点', '饼干、糕点、面包等');

-- --------------------------------------------------------

--
-- 表的结构 `ni_1008_category_attrs`
--

CREATE TABLE `ni_1008_category_attrs` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL COMMENT '所属类目ID',
  `attribute_name` char(64) NOT NULL COMMENT '属性键名',
  `attribute_alias` varchar(256) NOT NULL COMMENT '属性别名',
  `attribute_type` char(64) NOT NULL DEFAULT 'string' COMMENT '属性约束类型',
  `max_value_length` int(11) NOT NULL DEFAULT '0' COMMENT '属性值最大长度',
  `max_float_length` tinyint(2) NOT NULL DEFAULT '0' COMMENT '属性最大浮点长度',
  `values_range` longtext COMMENT '属性可选值空间',
  `default_value` varchar(128) DEFAULT NULL COMMENT '首选值'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='类目属性表';

--
-- 转存表中的数据 `ni_1008_category_attrs`
--

INSERT INTO `ni_1008_category_attrs` (`id`, `category_id`, `attribute_name`, `attribute_alias`, `attribute_type`, `max_value_length`, `max_float_length`, `values_range`, `default_value`) VALUES
(1, 1, 'standard', '规格', 'string', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ni_1008_productions`
--

CREATE TABLE `ni_1008_productions` (
  `id` int(11) NOT NULL COMMENT '主键',
  `code` char(128) DEFAULT NULL COMMENT '产品编号',
  `name` varchar(256) NOT NULL COMMENT '产品名称',
  `description` varchar(1024) DEFAULT NULL COMMENT '产品描述',
  `category_id` int(11) NOT NULL COMMENT '产品所属类目ID',
  `brand_id` int(11) NOT NULL COMMENT '所属品牌ID',
  `type_id` int(11) NOT NULL COMMENT '产品小类ID',
  `image` varchar(512) DEFAULT NULL,
  `standard` varchar(2048) NOT NULL COMMENT '规格',
  `attributes` longtext COMMENT '自定义属性，JSON格式',
  `detail` longtext COMMENT '产品详情',
  `time_onsale` datetime DEFAULT NULL,
  `link` varchar(1024) DEFAULT NULL COMMENT '链接',
  `rank` tinyint(4) NOT NULL DEFAULT '5',
  `state` tinyint(11) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品列表';

--
-- 转存表中的数据 `ni_1008_productions`
--

INSERT INTO `ni_1008_productions` (`id`, `code`, `name`, `description`, `category_id`, `brand_id`, `type_id`, `image`, `standard`, `attributes`, `detail`, `time_onsale`, `link`, `rank`, `state`) VALUES
(25, '', '奶酪西饼300g', '', 2, 1, 10, '/applications/uploads/files/3e4ff2667a9d6e4f15210193425aa8e9cf706d9.jpg', '300g', '[]', '<div style=\"text-align: justify;\"><font size=\"2\" color=\"#999999\">采用澳洲进口奶源，奶香浓郁，奶味香醇，口感酥脆，入口即化，甜味适中，让人回味无穷。铁盒包装，精美大方。</font></div>', '2018-03-14 17:21:51', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.79.580d6e8ek6kQRc&id=564443488324&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(26, '', '柠檬夹心饼干120g', '', 2, 1, 15, '/applications/uploads/files/2e997d79b27f221815223121145abca3b39f016.jpg', '120g', '[]', '<div>精选优质阳光小麦粉，苏打饼香脆不粘牙，搭配柔滑清新的柠檬味夹心，更具风味。独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 10:09:32', '', 5, 1),
(27, '', '乳酪夹心饼干120g', '', 2, 1, 15, '/applications/uploads/files/069989b8fcfef5dc15223121315abca3c394ce8.jpg', '120g', '[]', '<div>精选优质阳光小麦粉，苏打饼香脆不粘牙，搭配柔滑香浓的乳酪夹心，更具风味。独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 10:11:08', '', 5, 1),
(28, '', '玛德琳夹心饼干（芒果味）360g', '', 2, 1, 9, '/applications/uploads/files/21317ffa88289fa415223115745abca196d4e9b.jpg', '360克', '[]', '<div><span style=\"color: rgb(0, 0, 0); font-family: Simsun; font-size: 16px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal;\">采用独特的加工工艺，玛德琳饼干格外酥脆，入口即喷发出淡淡的自然小麦香气，搭配风味浓郁的芒果味夹心，口感丰富，齿颊留香。独立小包装，锁定风味，安全卫生，携带方便。</span><br></div>', '2018-03-23 10:12:10', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.73.580d6e8ek6kQRc&id=564334773582&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(29, '', '香脆咸饼干520g', '', 2, 1, 8, '/applications/uploads/files/ec7e98b39ec4d5c815223086915abc965431e42.jpg', '520g', '[]', '<div>精选优质阳光小麦粉，加入天然酵母，刚刚好的咸香风味，松松脆脆，风味十足，一口就会爱上的自然原味。大方手提外盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 10:14:36', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.88.580d6e8ek6kQRc&id=564361620780&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12&skuId=3767684343070', 6, 1),
(30, '', '苏打夹心饼干（椰子味）600g', '', 2, 1, 15, '/applications/uploads/files/99cf263facf7e0a115223121685abca3e89ccaa.jpg', '600g', '[]', '<div>精选优质阳光小麦粉，苏打饼香脆不粘牙，搭配柔滑清甜的椰子味夹心，更具风味。大方手提外盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:03:06', '', 7, 1),
(31, '', '乳酪夹心饼干（乳酪味）600g', '', 2, 1, 15, '/applications/uploads/files/f54e22da8c87136c15223121885abca3fca55e0.jpg', '600g', '[]', '<div>精选优质阳光小麦粉，苏打饼香脆不粘牙，搭配柔滑香浓的乳酪夹心，更具风味。大方手提外盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:05:10', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.64.580d6e8ek6kQRc&id=542665799135&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(32, '', '柠檬味夹心饼干600g', '', 2, 1, 15, '/applications/uploads/files/3afda715644cc96d15223122065abca40eb2416.jpg', '600g', '[]', '<div>精选优质阳光小麦粉，苏打饼香脆不粘牙，搭配柔滑清新的柠檬味夹心，更具风味。大方手提外盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:05:57', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.64.580d6e8ek6kQRc&id=542665799135&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(33, '', '奶酪饼干180g', '', 2, 1, 11, '/applications/uploads/files/fc692f9d60c4995015223138835abcaa9c3f2a7.jpg', '180g', '[]', '<div><span style=\"font-family: \" segoe=\"\" ui\",=\"\" \"microsoft=\"\" yahei=\"\" yahei\",=\"\" sans=\"\" serif\",=\"\" \"hanhei=\"\" sc\",=\"\" pinghei,=\"\" \"pingfang=\"\" \"hiragino=\"\" gb\",=\"\" \"helvetica=\"\" neue\",=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" normal;\"=\"\">精选优质阳光小麦粉，澳洲进口奶源，精心烘焙出片片焦黄脆饼，带有浓郁奶酪香味，</span><span style=\"font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; font-size: 13px; font-family: \" segoe=\"\" ui\",=\"\" \"microsoft=\"\" yahei=\"\" yahei\",=\"\" sans=\"\" serif\",=\"\" \"hanhei=\"\" sc\",=\"\" pinghei,=\"\" \"pingfang=\"\" \"hiragino=\"\" gb\",=\"\" \"helvetica=\"\" neue\",=\"\" helvetica,=\"\" arial,=\"\" sans-serif;\"=\"\">香酥诱人。</span><span style=\"font-family: \" segoe=\"\" ui\",=\"\" \"microsoft=\"\" yahei=\"\" yahei\",=\"\" sans=\"\" serif\",=\"\" \"hanhei=\"\" sc\",=\"\" pinghei,=\"\" \"pingfang=\"\" \"hiragino=\"\" gb\",=\"\" \"helvetica=\"\" neue\",=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" normal;\"=\"\">环保纸盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。</span><br></div>', '2018-03-23 14:07:19', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.58.580d6e8ek6kQRc&id=564208468428&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(34, '', '牛乳味饼干180g', '', 2, 1, 11, '/applications/uploads/files/9037cfdb8224a3a315223137775abcaa325487e.jpg', '180g', '[]', '<div>精选优质阳光小麦粉，澳洲进口奶源，精心烘焙出片片焦黄脆饼，带有纯纯牛奶香味，<span style=\"font-family: \" segoe=\"\" ui\",=\"\" \"microsoft=\"\" yahei=\"\" yahei\",=\"\" sans=\"\" serif\",=\"\" \"hanhei=\"\" sc\",=\"\" pinghei,=\"\" \"pingfang=\"\" \"hiragino=\"\" gb\",=\"\" \"helvetica=\"\" neue\",=\"\" helvetica,=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 13px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" normal;\"=\"\"></span>香酥诱人。环保纸盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。</div>', '2018-03-23 14:08:07', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.58.580d6e8ek6kQRc&id=564208468428&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(6, NULL, '玛德琳夹心饼干(椰子味)2', NULL, 2, 2, 2, '/applications/uploads/files/a47e00d3d3c0455015207782545aa53c0e46f28.jpg', '540g×10罐/箱', NULL, '当奶油恋上芝士，香浓的爱意沉浸在芝士的丝滑里，却因同根生而被命运阻隔，于是这份恋少了些许甜腻，多了一丝酸楚。幸好，有相思豆的铺撒，让原本凄凉的爱情多了无限温暖。', '0000-00-00 00:00:00', NULL, 5, 1),
(40, '', '玛德琳夹心饼干（花生味）360g', '', 2, 1, 9, '/applications/uploads/files/9e3b31244910271c15223115085abca1552d0c7.jpg', '360g', '[]', '<div>采用独特的加工工艺，玛德琳饼干格外酥脆，入口即喷发出淡淡的自然小麦香气，搭配充满坚果香气的花生味夹心，口感丰富，齿颊留香。独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:17:17', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.73.580d6e8ek6kQRc&id=564334773582&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(41, '', '夹心巧克力饼干（牛奶味）360g', '', 2, 1, 11, '/applications/uploads/files/75225c5cc07de28615223134305abca8d689efc.jpg', '360g', '[]', '<div>双层可可风味脆饼干，搭配香浓柔滑的牛奶味夹心，口感层次丰富，甜度刚好，好吃忘不了。独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:17:51', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.94.580d6e8ek6kQRc&id=564591067309&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(8, NULL, '玛德琳夹心饼干(椰子味)4', NULL, 2, 2, 4, '/applications/uploads/files/a47e00d3d3c0455015207781805aa53bc49ab76.jpg', '540g×10罐/箱', NULL, '当奶油恋上芝士，香浓的爱意沉浸在芝士的丝滑里，却因同根生而被命运阻隔，于是这份恋少了些许甜腻，多了一丝酸楚。幸好，有相思豆的铺撒，让原本凄凉的爱情多了无限温暖。', '0000-00-00 00:00:00', NULL, 5, 1),
(9, NULL, '玛德琳夹心饼干(椰子味)1', NULL, 2, 2, 2, '/applications/uploads/files/a47e00d3d3c0455015207784455aa53ccd5785d.jpg', '540g×10罐/箱', NULL, '当奶油恋上芝士，香浓的爱意沉浸在芝士的丝滑里，却因同根生而被命运阻隔，于是这份恋少了些许甜腻，多了一丝酸楚。幸好，有相思豆的铺撒，让原本凄凉的爱情多了无限温暖。', '0000-00-00 00:00:00', NULL, 5, 1),
(42, '', '夹心巧克力饼干（草莓味）360g', '', 2, 1, 11, '/applications/uploads/files/c7d3365933e8a47515217859585ab49c674fc0e.jpg', '360g', '[]', '<div>双层可可风味脆饼干，搭配香浓柔滑的草莓味夹心，口感层次丰富，甜度刚好，好吃忘不了。独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:18:55', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.94.580d6e8ek6kQRc&id=564591067309&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(43, '', '薄饼（鲜葱味）350g', '', 2, 1, 11, '/applications/uploads/files/63ffcb134bb784f315223132835abca8459cfe5.jpg', '350g', '[]', '<div>经典脆薄饼，小巧玲珑，酥脆可口，小麦香气搭配鲜葱鲜香，香气四溢，重拾童年时的经典美味。独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:19:24', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.70.580d6e8ek6kQRc&id=564437469214&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(35, '', '奶油西饼113g', '', 2, 1, 10, '/applications/uploads/files/ba9b919290f8631615217853645ab49a15802ff.jpg', '113g', '[]', '<div><span style=\"color: rgb(153, 153, 153); font-family: &quot;Segoe UI&quot;, &quot;Microsoft Yahei UI&quot;, &quot;Microsoft Yahei&quot;, &quot;Microsoft Sans Serif&quot;, &quot;HanHei SC&quot;, PingHei, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: small; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; text-align: justify;\">采用澳洲进口奶源，奶香浓郁，奶味香醇，口感酥脆，入口即化，甜味适中，让人回味无穷。铁盒包装，精美大方。</span><br></div>', '2018-03-23 14:09:03', '', 5, 1),
(38, '', '玛德琳夹心饼干（椰子味）540g', '', 2, 1, 9, '/applications/uploads/files/e46d378386f8473115223107155abc9e3b9c7ba.jpg', '540g', '[]', '<div><div>采用独特的加工工艺，玛德琳饼干格外酥脆，入口即喷发出淡淡的自然小麦香气，搭配清甜柔滑的椰子味夹心，口感丰富，齿颊留香。大方手提外盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。</div><div></div><div></div>', '2018-03-23 14:11:06', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.49.580d6e8ek6kQRc&id=564496263746&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(37, '', '玛德琳夹心饼干（芒果味）540g', '', 2, 1, 9, '/applications/uploads/files/bcb2c49d9c528d3e15223112315abca04037b66.jpg', '540g', '[]', '<div>采用独特的加工工艺，玛德琳饼干格外酥脆，入口即喷发出淡淡的自然小麦香气，搭配风味浓郁的芒果味夹心，口感丰富，齿颊留香。大方手提外盒，内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:09:53', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.49.580d6e8ek6kQRc&id=564496263746&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(39, '', '玛德琳夹心饼干（椰子味）360g', '', 2, 1, 9, '/applications/uploads/files/02dcf9df8453b54415223114615abca126617d2.jpg', '360g', '[]', '<div><span style=\"color: rgb(0, 0, 0); font-family: Simsun; font-size: 16px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal;\">采用独特的加工工艺，玛德琳饼干格外酥脆，入口即喷发出淡淡的自然小麦香气，搭配清甜柔滑的椰子味夹心，口感丰富，齿颊留香。独立小包装，锁定风味，安全卫生，携带方便。</span><br></div>', '2018-03-23 14:14:27', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.73.580d6e8ek6kQRc&id=564334773582&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(48, '', '苏打饼干（奶盐味）350g', '', 2, 1, 8, '/applications/uploads/files/dca32ab1c52fe07115223086015abc95f9f3bac.jpg', '350g', '[]', '<div>精选优质阳光小麦粉，香脆不粘牙，口味清淡，低糖不油腻，饱腹感强，利于人体消化。加入上乘进口奶粉，滋味醇香。内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:25:09', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.43.580d6e8ek6kQRc&id=542634620193&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(49, '', '苏打饼干（芝麻味）350g', '', 2, 1, 8, '/applications/uploads/files/b497aed629b09bf115223085945abc95f2d8403.jpg', '350g', '[]', '<div>精选优质阳光小麦粉，香脆不粘牙，口味清淡，低糖不油腻，饱腹感强，利于人体消化。加入原粒白芝麻，粒粒香脆，更具风味。内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:25:41', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.43.580d6e8ek6kQRc&id=542634620193&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(50, '', '苏打饼干（香葱味）350g', '', 2, 1, 8, '/applications/uploads/files/056f5858c9e7205d15223085755abc95e0c2eb3.jpg', '350g', '[]', '<div>精选优质阳光小麦粉，香脆不粘牙，口味清淡，低糖不油腻，饱腹感强，利于人体消化。加入鲜香香葱，风味鲜美。内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:25:58', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.43.580d6e8ek6kQRc&id=542634620193&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(47, '', '薄饼（山楂味）350g', '', 2, 1, 11, '/applications/uploads/files/213f2dd4fbbea5f415223130475abca75817850.jpg', '350g', '[]', '<div>经典脆薄饼，小巧玲珑，酥脆可口，小麦香气搭配山楂清甜，清新可口，重拾童年时的经典美味。独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-23 14:24:43', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.70.580d6e8ek6kQRc&id=564437469214&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(62, '', '苏打饼干（香葱味）540g', '', 2, 1, 8, '/applications/uploads/files/cc9f4bf510c39c8f15223082285abc94852d4dd.jpg', '540g', '[]', '<div>精选优质阳光小麦粉，香脆不粘牙，口味清淡，低糖不油腻，饱腹感强，利于人体消化。加入鲜香香葱，风味鲜美。内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-29 15:23:30', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.55.580d6e8ek6kQRc&id=542668323890&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(63, '', '苏打饼干（芝麻味）540g', '', 2, 1, 8, '/applications/uploads/files/e1c16d6d4f5724ca15223082795abc94b87038e.jpg', '540g', '[]', '<div>精选优质阳光小麦粉，香脆不粘牙，口味清淡，低糖不油腻，饱腹感强，利于人体消化。加入原粒白芝麻，粒粒香脆，更具风味。内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-29 15:24:14', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.55.580d6e8ek6kQRc&id=542668323890&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(64, '', '苏打饼干（奶盐味）540g', '', 2, 1, 8, '/applications/uploads/files/b42e66f78470553a15223083025abc94cfb335a.jpg', '540g', '[]', '<div>精选优质阳光小麦粉，香脆不粘牙，口味清淡，低糖不油腻，饱腹感强，利于人体消化。加入上乘进口奶粉，滋味醇香。内袋为独立小包装，锁定风味，安全卫生，携带方便。<br></div>', '2018-03-29 15:24:43', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.55.580d6e8ek6kQRc&id=542668323890&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(65, '', '柠檬味夹心饼干360g', '', 2, 1, 15, '/applications/uploads/files/0d69bf0afbd13ef315223122465abca43757150.jpg', '360g', '[]', '<div style=\"color: rgb(0, 0, 0); font-family: Simsun; font-size: 16px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal;\">精选优质阳光小麦粉，苏打饼香脆不粘牙，搭配柔滑清新的柠檬味夹心，更具风味。独立小包装，锁定风味，安全卫生，携带方便。</div>', '2018-03-29 16:30:36', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.46.580d6e8ek6kQRc&id=542618977609&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1),
(66, '', '乳酪夹心饼干360g', '', 2, 1, 15, '/applications/uploads/files/160ca776cb3cb64115223122725abca450b7eb5.jpg', '360g', '[]', '<div><span style=\"color: rgb(0, 0, 0); font-family: Simsun; font-size: 16px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal;\">精选优质阳光小麦粉，苏打饼香脆不粘牙，搭配柔滑香浓的乳酪夹心，更具风味。独立小包装，锁定风味，安全卫生，携带方便。</span><br></div>', '2018-03-29 16:31:06', 'https://detail.tmall.com/item.htm?spm=a1z10.3-b-s.w4011-15652746195.46.580d6e8ek6kQRc&id=542618977609&rn=73741f19863d0fcbad9ab48ca67a262f&abbucket=12', 5, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ni_1008_production_attrs_map`
--

CREATE TABLE `ni_1008_production_attrs_map` (
  `production_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_val` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_1008_production_attrs_map`
--

INSERT INTO `ni_1008_production_attrs_map` (`production_id`, `attribute_id`, `attribute_val`) VALUES
(1, 1, '540g×10罐/箱'),
(2, 1, '540g×10罐/箱'),
(3, 1, '540g×10罐/箱'),
(4, 1, '540g×10罐/箱'),
(5, 1, '540g×10罐/箱'),
(6, 1, '540g×10罐/箱'),
(7, 1, '540g×10罐/箱'),
(8, 1, '540g×10罐/箱'),
(9, 1, '540g×10罐/箱'),
(10, 1, '540g×10罐/箱'),
(11, 1, '540g×10罐/箱'),
(12, 1, '540g×10罐/箱'),
(13, 1, '540g×10罐/箱'),
(14, 1, '540g×10罐/箱'),
(15, 1, '540g×10罐/箱'),
(16, 1, '540g×10罐/箱');

-- --------------------------------------------------------

--
-- 表的结构 `ni_1008_types`
--

CREATE TABLE `ni_1008_types` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL COMMENT '所属品牌ID',
  `typename` varchar(256) NOT NULL COMMENT '类型名称'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_1008_types`
--

INSERT INTO `ni_1008_types` (`id`, `brand_id`, `typename`) VALUES
(2, 2, '月饼'),
(8, 1, '苏打饼干'),
(4, 2, '面包'),
(9, 1, '玛德琳饼干'),
(10, 1, '曲奇饼干'),
(11, 1, '其它'),
(12, 2, '面食'),
(13, 3, '散装饼干'),
(14, 5, '出口产品'),
(15, 1, '苏打夹心饼干');

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
-- 表的结构 `ni_cloud_filemeta_protected`
--

CREATE TABLE `ni_cloud_filemeta_protected` (
  `ID` char(44) NOT NULL,
  `SRC_ID` int(11) NOT NULL,
  `FOLDER` int(11) NOT NULL,
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `FILE_EXTN` char(32) DEFAULT NULL,
  `SK_CTIME` datetime NOT NULL,
  `SK_MTIME` datetime NOT NULL,
  `SK_IS_RECYCLED` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_filemeta_public`
--

CREATE TABLE `ni_cloud_filemeta_public` (
  `ID` char(44) NOT NULL,
  `SRC_ID` int(11) NOT NULL,
  `FOLDER` int(11) NOT NULL,
  `FILE_NAME` varchar(128) NOT NULL,
  `FILE_TYPE` char(32) NOT NULL DEFAULT 'archive',
  `FILE_SIZE` int(11) NOT NULL DEFAULT '0',
  `FILE_EXTN` char(32) DEFAULT NULL,
  `SK_CTIME` datetime NOT NULL,
  `SK_MTIME` datetime NOT NULL,
  `SK_IS_RECYCLED` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_filemeta_public`
--

INSERT INTO `ni_cloud_filemeta_public` (`ID`, `SRC_ID`, `FOLDER`, `FILE_NAME`, `FILE_TYPE`, `FILE_SIZE`, `FILE_EXTN`, `SK_CTIME`, `SK_MTIME`, `SK_IS_RECYCLED`) VALUES
('0017a1f2c8500b9b15205830675aa2419c7200d', 32, 5, '微信图片_20170708154259.jpg', 'image', 405409, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:11:07', 0),
('0017a1f2c8500b9b15205830735aa241a18975b', 32, 13, '微信图片_20170708154259.jpg', 'image', 405409, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:11:13', 0),
('02dcf9df8453b54415223114615abca126617d2', 94, 5, '玛德琳夹心饼干（椰子味）360g.jpg', 'image', 787916, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:17:41', 0),
('0476fde04a73f10c15217852985ab499d2b55c2', 64, 5, '0O1A9911 拷贝.jpg', 'image', 507732, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:08:18', 0),
('056f5858c9e7205d15223085755abc95e0c2eb3', 88, 5, '苏打饼干（香葱味）350g.jpg', 'image', 815482, 'jpg', '0000-00-00 00:00:00', '2018-03-29 15:29:35', 0),
('069989b8fcfef5dc15223121315abca3c394ce8', 98, 5, '乳酪夹心饼干120g.jpg', 'image', 647457, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:28:51', 0),
('07df983c21c5cac715217858535ab49bff62cb9', 69, 5, '0O1A9801 拷贝.jpg', 'image', 1467832, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:17:33', 0),
('0949faef6bbc64d615205822635aa23e7835337', 27, 5, 'aIMG_0669.jpg', 'image', 289157, 'jpg', '0000-00-00 00:00:00', '2018-03-09 15:57:43', 0),
('0949faef6bbc64d615205822685aa23e7c9f0a4', 27, 13, 'aIMG_0669.jpg', 'image', 289157, 'jpg', '0000-00-00 00:00:00', '2018-03-09 15:57:48', 0),
('0d69bf0afbd13ef315223122465abca43757150', 102, 5, '柠檬味夹心饼干360g.jpg', 'image', 650141, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:30:46', 0),
('0dde0b7fba6582e615217863705ab49e037c864', 76, 5, '0O1A9868 拷贝.jpg', 'image', 1648396, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:26:10', 0),
('160ca776cb3cb64115223122725abca450b7eb5', 103, 5, '乳酪夹心饼干360g.jpg', 'image', 719388, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:31:12', 0),
('1a505fe8982750ba15217857045ab49b69320fa', 68, 5, '0O1A9796 拷贝.jpg', 'image', 1705176, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:15:04', 0),
('1c7e03a0113ebbba15205829465aa24123cb5d1', 31, 5, '微信图片_20170708141952.jpg', 'image', 381697, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:09:06', 0),
('1c7e03a0113ebbba15205829545aa2412ac610c', 31, 13, '微信图片_20170708141952.jpg', 'image', 381697, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:09:14', 0),
('205d707b550cb00015207772315aa5380f70f52', 47, 14, '4.jpg', 'image', 5239, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('21317ffa88289fa415223115745abca196d4e9b', 96, 5, '玛德琳夹心饼干（芒果味）360g.jpg', 'image', 724259, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:19:34', 0),
('213f2dd4fbbea5f415223130475abca75817850', 104, 5, '薄饼（山楂味）350g.jpg', 'image', 615681, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:44:07', 0),
('23fd8ff4a10aab3515217852225ab4998755878', 62, 5, '0O1A9784 拷贝.jpg', 'image', 478605, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:07:02', 0),
('250ed60268de1f9915205823815aa23eee6423b', 28, 5, '465232270111502849.jpg', 'image', 388997, 'jpg', '0000-00-00 00:00:00', '2018-03-09 15:59:41', 0),
('250ed60268de1f9915205823905aa23ef6c333c', 28, 13, '465232270111502849.jpg', 'image', 388997, 'jpg', '0000-00-00 00:00:00', '2018-03-09 15:59:50', 0),
('25bb595acaa139be15217716985ab464b28da79', 59, 5, '0O1A9723 拷贝_副本.jpg', 'image', 866679, 'jpg', '0000-00-00 00:00:00', '2018-03-23 10:21:38', 0),
('2a6ca03f4ee60f2a15223140615abcab4df1660', 110, 5, '微信图片_20180329165814.jpg', 'image', 84651, 'jpg', '0000-00-00 00:00:00', '2018-03-29 17:01:01', 0),
('2dd92daf5bc4972115205827325aa2404ce829d', 30, 5, '微信图片_20170422085929.jpg', 'image', 285082, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:05:32', 0),
('2dd92daf5bc4972115205827385aa2405369616', 30, 13, '微信图片_20170422085929.jpg', 'image', 285082, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:05:38', 0),
('2e997d79b27f221815223121145abca3b39f016', 97, 5, '柠檬味夹心饼干120g.jpg', 'image', 609083, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:28:34', 0),
('33c72e9ae980f2e615207767455aa5362a1a464', 38, 5, 'map.jpg', 'image', 40107, 'jpg', '0000-00-00 00:00:00', '2018-03-11 21:59:05', 0),
('39617447b969ddf515203535065a9ec0e2b5988', 18, 6, 'banner(6).jpg', 'image', 95376, 'jpg', '0000-00-00 00:00:00', '2018-03-07 00:25:06', 0),
('39617447b969ddf515203535855a9ec131e1566', 18, 6, 'banner(7).jpg', 'image', 95376, 'jpg', '0000-00-00 00:00:00', '2018-03-07 00:26:25', 0),
('3afda715644cc96d15223122065abca40eb2416', 101, 5, '乳酪夹心饼干（柠檬味）600g.jpg', 'image', 533712, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:30:06', 0),
('3e4ff2667a9d6e4f15210193425aa8e9cf706d9', 54, 5, '微信图片_20180312100351.jpg', 'image', 345789, 'jpg', '0000-00-00 00:00:00', '2018-03-14 17:22:22', 0),
('3fe397a76317ca6015207767695aa53641969c7', 39, 5, 'left.jpg', 'image', 15299, 'jpg', '0000-00-00 00:00:00', '2018-03-11 21:59:29', 0),
('3fe397a76317ca6015207786005aa53d6814d94', 39, 5, 'left(1).jpg', 'image', 15299, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:30:00', 0),
('4b8512215a30cac5346', 9, 3, 'user.jpg', 'image', 66707, 'jpg', '0000-00-00 00:00:00', '2016-03-23 18:59:35', 0),
('4bbafa4a9b086ccc15220434145ab88a178ce5e', 77, 5, 'banner.jpg', 'image', 13227, 'jpg', '0000-00-00 00:00:00', '2018-03-26 13:50:14', 0),
('4bbafa4a9b086ccc15220478825ab89b8b66e28', 77, 5, 'banner(1).jpg', 'image', 13227, 'jpg', '0000-00-00 00:00:00', '2018-03-26 15:04:42', 0),
('4e948ac89b97d9e215205855195aa24b2f7b0c9', 36, 5, '20180129093638833.png', 'image', 177703, 'png', '0000-00-00 00:00:00', '2018-03-09 16:51:59', 0),
('4e948ac89b97d9e215210768525aa9ca7586bae', 36, 15, '20180129093638833.png', 'image', 177703, 'png', '0000-00-00 00:00:00', '2018-03-15 09:20:52', 0),
('4f1652499705d8f015217854545ab49a6e849b5', 66, 5, '0O1A9935 拷贝.jpg', 'image', 493333, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:10:54', 0),
('51e9ee3dd370b30415205833175aa24295cb8ae', 33, 13, '微信图片_20170916154100.jpg', 'image', 348449, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:15:17', 0),
('51e9ee3dd370b30415205833195aa242975766e', 33, 5, '微信图片_20170916154100.jpg', 'image', 348449, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:15:19', 0),
('525b3af913176cea15217710125ab46204af7a4', 55, 5, '0O1A9728 拷贝.jpg', 'image', 1194060, 'jpg', '0000-00-00 00:00:00', '2018-03-23 10:10:12', 0),
('5552e2db7e9ffc9a15223140455abcab3d7ebf3', 109, 5, '微信图片_20180329165810.jpg', 'image', 56083, 'jpg', '0000-00-00 00:00:00', '2018-03-29 17:00:45', 0),
('5a37e9b0c55539f215205680045aa206c57e6a0', 25, 5, '0微信图片_20170601151453.jpg', 'image', 449457, 'jpg', '0000-00-00 00:00:00', '2018-03-09 12:00:04', 0),
('5a37e9b0c55539f215205680205aa206d519545', 25, 13, '0微信图片_20170601151453.jpg', 'image', 449457, 'jpg', '0000-00-00 00:00:00', '2018-03-09 12:00:20', 0),
('5b0fd5dff6fc8b1615205652375aa1fbf7e3f77', 22, 13, 'WechatIMG56.jpeg', 'image', 1783607, 'jpeg', '0000-00-00 00:00:00', '2018-03-09 11:13:57', 0),
('5b0fd5dff6fc8b1615205656875aa1fdb8299fb', 22, 13, '20171018_IMG_2982.JPG', 'image', 1783607, 'JPG', '0000-00-00 00:00:00', '2018-03-09 11:21:27', 0),
('5b221859173376cc15207772315aa5380f96e1d', 49, 14, '7.jpg', 'image', 4640, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('5c2d58d050886c7215223140685abcab54a2b3d', 111, 5, '微信图片_20180329165817.jpg', 'image', 63583, 'jpg', '0000-00-00 00:00:00', '2018-03-29 17:01:08', 0),
('5cbaf7c867edbe3b15217851205ab49920e07d3', 61, 5, '0O1A9777 拷贝.jpg', 'image', 488441, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:05:20', 0),
('5f6fa9e97db3549615207768825aa536b2f3d2a', 41, 14, 'frontview.jpg', 'image', 71246, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:01:22', 0),
('62ade0e6b42134e215223146725abcadb12a77d', 112, 5, '微信图片_20180329165817(1).jpg', 'image', 50078, 'jpg', '0000-00-00 00:00:00', '2018-03-29 17:11:12', 0),
('63a61f96643f586515205820645aa23db073d80', 26, 13, '微信图片_20170330100639.jpg', 'image', 213816, 'jpg', '0000-00-00 00:00:00', '2018-03-09 15:54:24', 0),
('63a61f96643f586515205820665aa23db2a72c5', 26, 5, '微信图片_20170330100639.jpg', 'image', 213816, 'jpg', '0000-00-00 00:00:00', '2018-03-09 15:54:26', 0),
('63ffcb134bb784f315223132835abca8459cfe5', 105, 5, '薄饼（鲜葱味）350g.jpg', 'image', 1334536, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:48:03', 0),
('673a57aaa1e9e5bd15205825165aa23f7536092', 29, 5, 'aIMG_2774.jpg', 'image', 381021, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:01:56', 0),
('673a57aaa1e9e5bd15205825415aa23f8e5aa77', 29, 13, 'aIMG_2774.jpg', 'image', 381021, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:02:21', 0),
('6ed2409fe2beb0dd15217863545ab49df33b4ba', 75, 5, '0O1A9873 拷贝.jpg', 'image', 1336591, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:25:54', 0),
('710d08eb30f33d2c15207772315aa5380fb951b', 52, 14, '10.jpg', 'image', 8043, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('75225c5cc07de28615223132995abca8550837c', 106, 5, '夹心巧克力饼干（牛奶味）360g.jpg', 'image', 607833, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:48:19', 0),
('75225c5cc07de28615223134305abca8d689efc', 106, 5, '夹心巧克力饼干（牛奶味）360g(1).jpg', 'image', 607833, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:50:30', 0),
('77044c256393b56715207772315aa5380f4d257', 45, 14, '3.jpg', 'image', 5605, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('78163c7a64be12ed15205675475aa204fc40d7f', 23, 5, '2(142).jpg', 'image', 320915, 'jpg', '0000-00-00 00:00:00', '2018-03-09 11:52:27', 0),
('78163c7a64be12ed15205675555aa20503cbc56', 23, 13, '2(142).jpg', 'image', 320915, 'jpg', '0000-00-00 00:00:00', '2018-03-09 11:52:35', 0),
('7f683280c74ca8b815217862735ab49da2da176', 73, 5, '0O1A9851 拷贝.jpg', 'image', 1375425, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:24:33', 0),
('7f683280c74ca8b815217863025ab49dbf14317', 73, 5, '0O1A9851 拷贝(1).jpg', 'image', 1375425, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:25:02', 0),
('84ab2dc93c46c52015203286085a9e5fa1a8200', 15, 6, 'banner.jpg', 'image', 49286, 'jpg', '0000-00-00 00:00:00', '2018-03-06 17:30:08', 0),
('84ab2dc93c46c52015203286215a9e5fae5e9ec', 15, 6, 'banner(1).jpg', 'image', 49286, 'jpg', '0000-00-00 00:00:00', '2018-03-06 17:30:21', 0),
('84ab2dc93c46c52015203286325a9e5fb99de6e', 15, 6, 'banner(2).jpg', 'image', 49286, 'jpg', '0000-00-00 00:00:00', '2018-03-06 17:30:32', 0),
('84ab2dc93c46c52015203286435a9e5fc42729b', 15, 6, 'banner(3).jpg', 'image', 49286, 'jpg', '0000-00-00 00:00:00', '2018-03-06 17:30:43', 0),
('9037cfdb8224a3a315223137775abcaa325487e', 107, 5, '牛乳味饼干180g.jpg', 'image', 553974, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:56:17', 0),
('94e4347ccf5c0f24112', 8, 3, 'captain.jpg', 'image', 298702, 'jpg', '0000-00-00 00:00:00', '2016-03-23 18:59:35', 0),
('972bc0d711e1233e15220489305ab89fa402091', 81, 5, 'phone.jpg', 'image', 21098, 'jpg', '0000-00-00 00:00:00', '2018-03-26 15:22:10', 0),
('99cf263facf7e0a115223121685abca3e89ccaa', 99, 5, '苏打夹心饼干（椰子味）600g.jpg', 'image', 567840, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:29:28', 0),
('9b96c29608430e5715205630555aa1f36fc3109', 21, 5, '20171018_IMG_2982.JPG', 'image', 1864863, 'JPG', '0000-00-00 00:00:00', '2018-03-09 10:37:35', 0),
('9d888f884600425915207772315aa5380f6e32c', 46, 14, '5.jpg', 'image', 8171, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('9e3b31244910271c15223115085abca1552d0c7', 95, 5, '玛德琳夹心饼干（花生味）360g.jpg', 'image', 708006, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:18:28', 0),
('9e6f75b868dd557b15217863365ab49de0c1cd9', 74, 5, '0O1A9859 拷贝.jpg', 'image', 1349431, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:25:36', 0),
('9e7d4880db3b97d815207772315aa5380f9f9ad', 50, 14, '9.jpg', 'image', 6975, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('9ead91702a45d40d15207769045aa536c91656e', 42, 5, 'illustration.jpg', 'image', 22013, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:01:44', 0),
('a1f56dc4da91445115207772315aa5380f3f56a', 43, 14, '1.jpg', 'image', 9704, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('a47e00d3d3c0455015207781635aa53bb3429da', 53, 5, 'production_9_large.jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:22:43', 0),
('a47e00d3d3c0455015207781725aa53bbc26583', 53, 5, 'production_9_large(1).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:22:52', 0),
('a47e00d3d3c0455015207781805aa53bc49ab76', 53, 5, 'production_9_large(2).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:23:00', 0),
('a47e00d3d3c0455015207781895aa53bcd32afa', 53, 5, 'production_9_large(3).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:23:09', 0),
('a47e00d3d3c0455015207781975aa53bd55b4ff', 53, 5, 'production_9_large(4).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:23:17', 0),
('a47e00d3d3c0455015207782075aa53be00a2f6', 53, 5, 'production_9_large(5).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:23:27', 0),
('a47e00d3d3c0455015207782245aa53bf07767d', 53, 5, 'production_9_large(6).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:23:44', 0),
('a47e00d3d3c0455015207782345aa53bfa59a87', 53, 5, 'production_9_large(7).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:23:54', 0),
('a47e00d3d3c0455015207782445aa53c042a9e5', 53, 5, 'production_9_large(8).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:24:04', 0),
('a47e00d3d3c0455015207782545aa53c0e46f28', 53, 5, 'production_9_large(9).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:24:14', 0),
('a47e00d3d3c0455015207782645aa53c188ea06', 53, 5, 'production_9_large(10).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:24:24', 0),
('a47e00d3d3c0455015207782915aa53c337261e', 53, 5, 'production_9_large(11).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:24:51', 0),
('a47e00d3d3c0455015207784455aa53ccd5785d', 53, 5, 'production_9_large(12).jpg', 'image', 63675, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:27:25', 0),
('b42e66f78470553a15223083025abc94cfb335a', 87, 5, '0O1A9881 拷贝.jpg', 'image', 607354, 'jpg', '0000-00-00 00:00:00', '2018-03-29 15:25:02', 0),
('b497aed629b09bf115223085945abc95f2d8403', 89, 5, '苏打饼干（芝麻味）350g.jpg', 'image', 678591, 'jpg', '0000-00-00 00:00:00', '2018-03-29 15:29:54', 0),
('b8afa0de666b4f1d15220434225ab88a1f3b866', 78, 5, 'figure.jpg', 'image', 42817, 'jpg', '0000-00-00 00:00:00', '2018-03-26 13:50:22', 0),
('b8afa0de666b4f1d15220450385ab8906f3bebe', 78, 5, 'figure(1).jpg', 'image', 42817, 'jpg', '0000-00-00 00:00:00', '2018-03-26 14:17:18', 0),
('b8afa0de666b4f1d15220477755ab89b203ca5b', 78, 5, 'figure(2).jpg', 'image', 42817, 'jpg', '0000-00-00 00:00:00', '2018-03-26 15:02:55', 0),
('ba702c3ea1fe0e5c15207758285aa532946887e', 37, 5, 'logo.common.jpg', 'image', 28123, 'jpg', '0000-00-00 00:00:00', '2018-03-11 21:43:48', 0),
('ba702c3ea1fe0e5c15207758595aa532b347ef8', 37, 5, 'logo.common(1).jpg', 'image', 28123, 'jpg', '0000-00-00 00:00:00', '2018-03-11 21:44:19', 0),
('ba9b919290f8631615217853645ab49a15802ff', 65, 5, '0O1A9916 拷贝.jpg', 'image', 791875, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:09:24', 0),
('bcb2c49d9c528d3e15223112315abca04037b66', 93, 5, '玛德琳夹心饼干（芒果味）540g.jpg', 'image', 535286, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:13:51', 0),
('bdcea5e3ec01136a15205849735aa2490dc78a0', 34, 5, 'mp60855879_1456570191542_15.jpeg', 'image', 74555, 'jpeg', '0000-00-00 00:00:00', '2018-03-09 16:42:53', 0),
('bdcea5e3ec01136a15210769075aa9caac242c6', 34, 15, 'mp60855879_1456570191542_15.jpeg', 'image', 74555, 'jpeg', '0000-00-00 00:00:00', '2018-03-15 09:21:47', 0),
('bf0148ab92bb690b15217850375ab498ce49a8a', 60, 5, '0O1A9769 拷贝.jpg', 'image', 537982, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:03:57', 0),
('c0abb241ed4940a815221148245ab9a10891081', 84, 5, 'video16x9.mp4', 'video', 835554, 'mp4', '0000-00-00 00:00:00', '2018-03-27 09:40:24', 0),
('c0f2d0dcfcff509815207768595aa5369be98c2', 40, 14, 'airscape.jpg', 'image', 87317, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:00:59', 0),
('c40ffe863850374615217855965ab49afd6e0bc', 67, 5, '0O1A9940 拷贝.jpg', 'image', 482169, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:13:16', 0),
('c5c9c14246a162dd15220489215ab89f9a9bdf7', 80, 5, 'photo2x3.jpg', 'image', 29449, 'jpg', '0000-00-00 00:00:00', '2018-03-26 15:22:01', 0),
('c7d3365933e8a47515217859585ab49c674fc0e', 71, 5, '0O1A9808 拷贝.jpg', 'image', 1169768, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:19:18', 0),
('c89ab33b7d9f4a5b15205678105aa206032c0df', 24, 13, '0IMG_7567.JPG', 'image', 384839, 'JPG', '0000-00-00 00:00:00', '2018-03-09 11:56:50', 0),
('c89ab33b7d9f4a5b15205678135aa20605e2d52', 24, 5, '0IMG_7567.JPG', 'image', 384839, 'JPG', '0000-00-00 00:00:00', '2018-03-09 11:56:53', 0),
('ca28525a8b386236136', 4, 3, 'guest.jpg', 'image', 73401, 'jpg', '0000-00-00 00:00:00', '2016-03-23 18:59:34', 0),
('cb3b6634987dd88815217852545ab499a770ad3', 63, 5, '0O1A9906 拷贝.jpg', 'image', 421805, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:07:34', 0),
('cc019d03ee21c7df15203195105a9e3c17c0acc', 14, 6, 'WeChat Image_20180110225446.jpg', 'image', 830767, 'jpg', '0000-00-00 00:00:00', '2018-03-06 14:58:30', 0),
('cc26775220c32188228', 6, 3, 'operator.jpg', 'image', 139485, 'jpg', '0000-00-00 00:00:00', '2016-03-23 18:59:35', 0),
('cc9f4bf510c39c8f15223063685abc8d41800eb', 85, 5, '0O1A9894 拷贝.jpg', 'image', 657006, 'jpg', '0000-00-00 00:00:00', '2018-03-29 14:52:48', 0),
('cc9f4bf510c39c8f15223065725abc8e0d917a9', 85, 5, '0O1A9894 拷贝(1).jpg', 'image', 657006, 'jpg', '0000-00-00 00:00:00', '2018-03-29 14:56:12', 0),
('cc9f4bf510c39c8f15223082285abc94852d4dd', 85, 5, '0O1A9894 拷贝(2).jpg', 'image', 657006, 'jpg', '0000-00-00 00:00:00', '2018-03-29 15:23:48', 0),
('ce3844ebdb5dc0cf15217712035ab462c573993', 57, 5, '0O1A9788 拷贝.jpg', 'image', 1604000, 'jpg', '0000-00-00 00:00:00', '2018-03-23 10:13:23', 0),
('ce3844ebdb5dc0cf15217856945ab49b5f802a0', 57, 5, '0O1A9788 拷贝(1).jpg', 'image', 1604000, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:14:54', 0),
('d0e8c2dabdf8233d15203534695a9ec0bd3c13d', 17, 6, 'banner(5).jpg', 'image', 102056, 'jpg', '0000-00-00 00:00:00', '2018-03-07 00:24:29', 0),
('d1dbd98ca523f11915217710945ab46256b6ad3', 56, 5, '0O1A9736 拷贝.jpg', 'image', 1274326, 'jpg', '0000-00-00 00:00:00', '2018-03-23 10:11:34', 0),
('d2fbddcf0022f66f15205852825aa24a4336f16', 35, 5, '20170925170547_52934.jpg', 'image', 181337, 'jpg', '0000-00-00 00:00:00', '2018-03-09 16:48:02', 0),
('d2fbddcf0022f66f15210768925aa9ca9c7d899', 35, 15, '20170925170547_52934.jpg', 'image', 181337, 'jpg', '0000-00-00 00:00:00', '2018-03-15 09:21:32', 0),
('d344324ac383a7bd15217859865ab49c839eb3e', 72, 5, '0O1A9844 拷贝.jpg', 'image', 1340346, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:19:46', 0),
('d3ff99c7a3aafab915203534435a9ec0a3f3026', 16, 6, 'banner(4).jpg', 'image', 95166, 'jpg', '0000-00-00 00:00:00', '2018-03-07 00:24:03', 0),
('d78cf72c9a8f4731217', 5, 3, 'yangram.jpg', 'image', 134581, 'jpg', '0000-00-00 00:00:00', '2016-03-23 18:59:35', 0),
('dca32ab1c52fe07115223086015abc95f9f3bac', 90, 5, '苏打饼干（奶盐味）350g.jpg', 'image', 654587, 'jpg', '0000-00-00 00:00:00', '2018-03-29 15:30:01', 0),
('dd2370a09e14eace15207772315aa5380f4c4b9', 44, 14, '2.jpg', 'image', 5052, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('defaultpic', 13, 6, 'DefaultPicture.jpg', 'image', 3324, 'jpg', '0000-00-00 00:00:00', '2018-02-06 09:38:46', 0),
('e1c16d6d4f5724ca15223082795abc94b87038e', 86, 5, '0O1A9889 拷贝.jpg', 'image', 639440, 'jpg', '0000-00-00 00:00:00', '2018-03-29 15:24:39', 0),
('e1fc19fea477032315207772315aa5380f7a63e', 48, 14, '6.jpg', 'image', 7079, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('e46d378386f8473115223107155abc9e3b9c7ba', 92, 5, '玛德琳夹心饼干（椰子味）540g.jpg', 'image', 503505, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:05:15', 0),
('e9b7b0566f6eabb915217713255ab4633e53719', 58, 5, '0O1A9723 拷贝.jpg', 'image', 414004, 'jpg', '0000-00-00 00:00:00', '2018-03-23 10:15:25', 0),
('ea022c42d5ffeaf215204354835aa0011c23aaa', 20, 12, 'main.jpg', 'image', 39113, 'jpg', '0000-00-00 00:00:00', '2018-03-07 23:11:23', 0),
('ea022c42d5ffeaf215207767775aa536494ae54', 20, 5, 'main.jpg', 'image', 39113, 'jpg', '0000-00-00 00:00:00', '2018-03-11 21:59:37', 0),
('ea022c42d5ffeaf215207785965aa53d645303a', 20, 5, 'main(1).jpg', 'image', 39113, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:29:56', 0),
('ec7e98b39ec4d5c815223086915abc965431e42', 91, 5, '香脆咸饼干520g.jpg', 'image', 502501, 'jpg', '0000-00-00 00:00:00', '2018-03-29 15:31:31', 0),
('f312358a7a2ef47815220483975ab89d8f0db1a', 79, 5, 'photo.jpg', 'image', 46812, 'jpg', '0000-00-00 00:00:00', '2018-03-26 15:13:17', 0),
('f43dfa60ddad47f115203536185a9ec1529ae5e', 19, 6, 'banner(8).jpg', 'image', 102037, 'jpg', '0000-00-00 00:00:00', '2018-03-07 00:26:58', 0),
('f54e22da8c87136c15223121885abca3fca55e0', 100, 5, '乳酪夹心饼干（乳酪味）600g.jpg', 'image', 533501, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:29:48', 0),
('f588a8a8eadc4dda15220957265ab9566e25c19', 82, 5, 'banner(2).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:22:06', 0),
('f588a8a8eadc4dda15220957365ab956786d57f', 82, 5, 'banner(3).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:22:16', 0),
('f588a8a8eadc4dda15220959635ab9575b86f14', 82, 5, 'banner(4).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:26:03', 0),
('f588a8a8eadc4dda15220960275ab9579bc220b', 82, 5, 'banner(5).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:27:07', 0),
('f588a8a8eadc4dda15220960525ab957b4950d3', 82, 5, 'banner(6).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:27:32', 0),
('f588a8a8eadc4dda15220960675ab957c3953fa', 82, 5, 'banner(7).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:27:47', 0),
('f588a8a8eadc4dda15220960755ab957cb338a5', 82, 5, 'banner(8).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:27:55', 0),
('f588a8a8eadc4dda15220961795ab958334f189', 82, 5, 'banner(9).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:29:39', 0),
('f588a8a8eadc4dda15220961895ab9583de5359', 82, 5, 'banner(10).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:29:49', 0),
('f588a8a8eadc4dda15220962025ab9584a22bf5', 82, 5, 'banner(11).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:30:02', 0),
('f588a8a8eadc4dda15220962175ab95859cf8a9', 82, 5, 'banner(12).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:30:17', 0),
('f588a8a8eadc4dda15220962275ab958632a784', 82, 5, 'banner(13).jpg', 'image', 221661, 'jpg', '0000-00-00 00:00:00', '2018-03-27 04:30:27', 0),
('f7f476c3a12b1f1f130', 7, 3, 'fish.jpg', 'image', 199597, 'jpg', '0000-00-00 00:00:00', '2016-03-23 18:59:35', 0),
('fa65e8557ea6588015207772315aa5380f9fd54', 51, 14, '8.jpg', 'image', 7278, 'jpg', '0000-00-00 00:00:00', '2018-03-11 22:07:11', 0),
('fc692f9d60c4995015223138835abcaa9c3f2a7', 108, 5, '奶酪饼干180g.jpg', 'image', 496421, 'jpg', '0000-00-00 00:00:00', '2018-03-29 16:58:03', 0),
('fdbf2f67144b57f215217859025ab49c2f63e15', 70, 5, '0O1A9832 拷贝.jpg', 'image', 1267643, 'jpg', '0000-00-00 00:00:00', '2018-03-23 14:18:22', 0),
('ff9d6a058647a1d015221147545ab9a0c30bc13', 83, 5, 'v2-e8f0e221c2e2a24ecba96ceef6c9d727_hd.jpg', 'image', 20867, 'jpg', '0000-00-00 00:00:00', '2018-03-27 09:39:14', 0),
('sampledocument', 1, 2, 'License.doc', 'compressed', 9216, 'zip', '0000-00-00 00:00:00', '2015-12-23 00:00:00', 0),
('samplemp3audio', 10, 2, 'LaCampanella.mp3', 'audio', 2885616, 'mp3', '0000-00-00 00:00:00', '2015-12-23 00:00:00', 0),
('sampleoggaudio', 11, 2, 'LaCampanella.ogg', 'audio', 2224551, 'ogg', '0000-00-00 00:00:00', '2015-12-23 00:00:00', 0),
('samplepicture01', 2, 2, 'trees.jpg', 'image', 370490, 'jpg', '0000-00-00 00:00:00', '2016-01-01 00:00:00', 0),
('samplepicture02', 3, 2, 'magic.jpg', 'image', 339220, 'jpg', '0000-00-00 00:00:00', '2015-12-24 00:00:00', 0),
('samplevideo', 12, 2, 'FlyToSpace.mp4', 'video', 14020722, 'mp4', '0000-00-00 00:00:00', '2015-12-23 00:00:00', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_folders`
--

CREATE TABLE `ni_cloud_folders` (
  `id` int(11) NOT NULL,
  `type` char(10) NOT NULL DEFAULT 'file',
  `name` varchar(64) NOT NULL,
  `parent` int(11) DEFAULT '6',
  `SK_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `SK_MTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_folders`
--

INSERT INTO `ni_cloud_folders` (`id`, `type`, `name`, `parent`, `SK_IS_RECYCLED`, `SK_MTIME`) VALUES
(1, 'F', 'System', 0, 0, '2015-12-30 00:00:00'),
(2, 'F', 'Sample', 1, 0, '2015-12-30 00:00:00'),
(3, 'F', 'UserAvatars', 1, 0, '2015-12-30 00:00:00'),
(4, 'F', 'Applications', 0, 0, '2016-01-04 09:50:36'),
(5, 'F', 'UPLOADER', 4, 0, '2016-01-09 20:08:45'),
(6, 'F', 'OurDocuments', 0, 0, '2015-12-31 22:26:33'),
(7, 'F', 'DefultImages', 1, 0, '2018-03-12 00:00:00'),
(8, 'F', 'ForbiddenImages', 1, 0, '2018-03-12 00:00:00'),
(9, 'F', '__UEditor', 5, 0, '2018-03-07 23:11:23'),
(10, 'F', '2018', 9, 0, '2018-03-07 23:11:23'),
(11, 'F', '03', 10, 0, '2018-03-07 23:11:23'),
(12, 'F', '07', 11, 0, '2018-03-07 23:11:23'),
(13, 'F', '09', 11, 0, '2018-03-09 11:13:57'),
(14, 'F', '11', 11, 0, '2018-03-11 22:00:59'),
(15, 'F', '15', 11, 0, '2018-03-15 09:20:52');

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_groups`
--

CREATE TABLE `ni_cloud_groups` (
  `id` int(11) NOT NULL,
  `tablename` char(64) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `parent` int(11) DEFAULT '6',
  `sortno` int(11) NOT NULL DEFAULT '0',
  `SK_IS_RECYCLED` int(11) NOT NULL DEFAULT '0',
  `SK_MTIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_groups`
--

INSERT INTO `ni_cloud_groups` (`id`, `tablename`, `name`, `description`, `parent`, `sortno`, `SK_IS_RECYCLED`, `SK_MTIME`) VALUES
(1, 'news', '集团新闻', '集团新闻', 0, 0, 0, '2018-01-21 00:00:00'),
(2, 'news', '行业动态', '行业动态', 0, 0, 0, '2018-01-21 00:00:00');

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
-- 表的结构 `ni_cloud_sources_protected`
--

CREATE TABLE `ni_cloud_sources_protected` (
  `SID` int(11) NOT NULL,
  `HASH` char(32) DEFAULT NULL,
  `LOCATION` varchar(128) NOT NULL,
  `MIME` char(128) NOT NULL,
  `IMAGE_SIZE` varchar(64) DEFAULT NULL,
  `WIDTH` int(11) NOT NULL DEFAULT '0',
  `HEIGHT` int(11) NOT NULL DEFAULT '0',
  `DURATION` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ni_cloud_sources_public`
--

CREATE TABLE `ni_cloud_sources_public` (
  `SID` int(11) NOT NULL,
  `HASH` char(32) DEFAULT NULL,
  `LOCATION` varchar(128) NOT NULL,
  `MIME` char(128) NOT NULL,
  `IMAGE_SIZE` varchar(64) DEFAULT NULL,
  `WIDTH` int(11) NOT NULL DEFAULT '0',
  `HEIGHT` int(11) NOT NULL DEFAULT '0',
  `DURATION` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ni_cloud_sources_public`
--

INSERT INTO `ni_cloud_sources_public` (`SID`, `HASH`, `LOCATION`, `MIME`, `IMAGE_SIZE`, `WIDTH`, `HEIGHT`, `DURATION`) VALUES
(1, 'c1d6380a9cd5698e8dc361c25346ae53', 'Docs/Smaple/License.doc', 'application/msword', NULL, 0, 0, 0),
(2, '85c0878cd4f2aceb891953264afcbf3e', 'Images/Sample/trees.jpg', 'image/jpeg', 'width=\"800\" height=\"600\"', 800, 600, 0),
(3, '3798a0f35fdc4184dff4c09319ce7093', 'Images/Sample/magic.jpg', 'image/jpeg', 'width=\"960\" height=\"600\"', 960, 600, 0),
(4, 'c4f57c5173781656b5d0a3566ffbc232', 'Images/DefultAvatars/guest.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0),
(5, 'adb9841975686eeae6fd60ce88bf9bdc', 'Images/DefultAvatars/yangram.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0),
(6, '7b36442749de9f5c49fad4dd0e884c9e', 'Images/DefultAvatars/operator.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0),
(7, 'c612a30507b055b6b499b0357ddbb5f6', 'Images/DefultAvatars/fish.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0),
(8, '6323083e588d801d4e8e913d5b55ab54', 'Images/DefultAvatars/captain.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0),
(9, '9bea2f588576bd36a304ea6e230e0ff9', 'Images/DefultAvatars/user.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0),
(10, '0f4e74454fba8cb493ae743539db1816', 'Audios/Sample/LaCampanella.mp3', 'audio/mpeg', NULL, 0, 0, 120),
(11, '7ef4ed4b6ba3744993aa9f6476dc639a', 'Audios/Sample/LaCampanella.ogg', 'audio/ogg', NULL, 0, 0, 120),
(12, 'df7a6b0f2c5d058ea952e00af1bdb414', 'Videos/Sample/FlyToSpace.mp4', 'video/mp4', NULL, 0, 0, 20),
(13, 'd93a85bf078ce8e4f465e2c0e204a615', 'Images/Defaults/Common.jpg', 'image/jpeg', 'width=\"300\" height=\"200\"', 300, 200, 0),
(14, 'bcd474adcc019d03ee21c7df959b9a03', 'Images/2018-03-06/bcd474adcc019d03ee21c7df959b9a03.jpg', 'image/jpeg', 'width=\"1920\" height=\"1080\"', 1920, 1080, 0),
(15, '873f569a84ab2dc93c46c520f1c6dbab', 'Images/2018-03-06/873f569a84ab2dc93c46c520f1c6dbab.jpg', 'image/jpeg', 'width=\"696\" height=\"190\"', 696, 190, 0),
(16, 'd9569bafd3ff99c7a3aafab9163b3ecc', 'Images/2018-03-07/d9569bafd3ff99c7a3aafab9163b3ecc.jpg', 'image/jpeg', 'width=\"696\" height=\"190\"', 696, 190, 0),
(17, 'c622043ad0e8c2dabdf8233db473b48a', 'Images/2018-03-07/c622043ad0e8c2dabdf8233db473b48a.jpg', 'image/jpeg', 'width=\"696\" height=\"190\"', 696, 190, 0),
(18, 'dce092e639617447b969ddf58f591315', 'Images/2018-03-07/dce092e639617447b969ddf58f591315.jpg', 'image/jpeg', 'width=\"696\" height=\"190\"', 696, 190, 0),
(19, '4a499999f43dfa60ddad47f16d15e8ae', 'Images/2018-03-07/4a499999f43dfa60ddad47f16d15e8ae.jpg', 'image/jpeg', 'width=\"696\" height=\"190\"', 696, 190, 0),
(20, '948659e3ea022c42d5ffeaf210a4cdd5', 'Images/2018-03-07/948659e3ea022c42d5ffeaf210a4cdd5.jpg', 'image/jpeg', 'width=\"696\" height=\"175\"', 696, 175, 0),
(21, '6a6eab1c9b96c29608430e572bcd737f', 'Images/2018-03-09/6a6eab1c9b96c29608430e572bcd737f.JPG', 'image/jpeg', 'width=\"3264\" height=\"2448\"', 3264, 2448, 0),
(22, 'd6a299935b0fd5dff6fc8b16357c681e', 'Images/2018-03-09/d6a299935b0fd5dff6fc8b16357c681e.jpeg', 'image/jpeg', 'width=\"2000\" height=\"1500\"', 2000, 1500, 0),
(23, '198ff0a178163c7a64be12ed7e49638f', 'Images/2018-03-09/198ff0a178163c7a64be12ed7e49638f.jpg', 'image/jpeg', 'width=\"800\" height=\"452\"', 800, 452, 0),
(24, '675d0473c89ab33b7d9f4a5b63115b49', 'Images/2018-03-09/675d0473c89ab33b7d9f4a5b63115b49.JPG', 'image/jpeg', 'width=\"800\" height=\"533\"', 800, 533, 0),
(25, 'c54014895a37e9b0c55539f27498acd0', 'Images/2018-03-09/c54014895a37e9b0c55539f27498acd0.jpg', 'image/jpeg', 'width=\"800\" height=\"600\"', 800, 600, 0),
(26, '67631bab63a61f96643f58650544308a', 'Images/2018-03-09/67631bab63a61f96643f58650544308a.jpg', 'image/jpeg', 'width=\"800\" height=\"447\"', 800, 447, 0),
(27, '58af3d7d0949faef6bbc64d6bee3c0c5', 'Images/2018-03-09/58af3d7d0949faef6bbc64d6bee3c0c5.jpg', 'image/jpeg', 'width=\"800\" height=\"514\"', 800, 514, 0),
(28, '75d92cf3250ed60268de1f99b424ad53', 'Images/2018-03-09/75d92cf3250ed60268de1f99b424ad53.jpg', 'image/jpeg', 'width=\"800\" height=\"600\"', 800, 600, 0),
(29, 'c6d0569c673a57aaa1e9e5bd7060ea16', 'Images/2018-03-09/c6d0569c673a57aaa1e9e5bd7060ea16.jpg', 'image/jpeg', 'width=\"800\" height=\"353\"', 800, 353, 0),
(30, '1f14ad1b2dd92daf5bc49721da0cabb2', 'Images/2018-03-09/1f14ad1b2dd92daf5bc49721da0cabb2.jpg', 'image/jpeg', 'width=\"800\" height=\"600\"', 800, 600, 0),
(31, 'c02bee871c7e03a0113ebbba81d4c628', 'Images/2018-03-09/c02bee871c7e03a0113ebbba81d4c628.jpg', 'image/jpeg', 'width=\"800\" height=\"450\"', 800, 450, 0),
(32, 'b7a661e20017a1f2c8500b9bdebc5870', 'Images/2018-03-09/b7a661e20017a1f2c8500b9bdebc5870.jpg', 'image/jpeg', 'width=\"800\" height=\"487\"', 800, 487, 0),
(33, '1f2bf47751e9ee3dd370b304e0e851bf', 'Images/2018-03-09/1f2bf47751e9ee3dd370b304e0e851bf.jpg', 'image/jpeg', 'width=\"800\" height=\"472\"', 800, 472, 0),
(34, '2d3add54bdcea5e3ec01136aa2a46e12', 'Images/2018-03-09/2d3add54bdcea5e3ec01136aa2a46e12.jpeg', 'image/jpeg', 'width=\"600\" height=\"374\"', 600, 374, 0),
(35, '116d0cb2d2fbddcf0022f66fecc6c26e', 'Images/2018-03-09/116d0cb2d2fbddcf0022f66fecc6c26e.jpg', 'image/jpeg', 'width=\"550\" height=\"378\"', 550, 378, 0),
(36, '4185c6354e948ac89b97d9e2150b4edf', 'Images/2018-03-09/4185c6354e948ac89b97d9e2150b4edf.png', 'image/png', 'width=\"450\" height=\"277\"', 450, 277, 0),
(37, 'bedb5640ba702c3ea1fe0e5ccbcab769', 'Images/2018-03-11/bedb5640ba702c3ea1fe0e5ccbcab769.jpg', 'image/jpeg', 'width=\"500\" height=\"90\"', 500, 90, 0),
(38, '69bd8ed533c72e9ae980f2e64777f6e1', 'Images/2018-03-11/69bd8ed533c72e9ae980f2e64777f6e1.jpg', 'image/jpeg', 'width=\"696\" height=\"380\"', 696, 380, 0),
(39, '33118d6d3fe397a76317ca60d6c31780', 'Images/2018-03-11/33118d6d3fe397a76317ca60d6c31780.jpg', 'image/jpeg', 'width=\"231\" height=\"116\"', 231, 116, 0),
(40, 'b0360614c0f2d0dcfcff50984a8f61e0', 'Images/2018-03-11/b0360614c0f2d0dcfcff50984a8f61e0.jpg', 'image/jpeg', 'width=\"696\" height=\"172\"', 696, 172, 0),
(41, '761745ba5f6fa9e97db35496afe782ee', 'Images/2018-03-11/761745ba5f6fa9e97db35496afe782ee.jpg', 'image/jpeg', 'width=\"696\" height=\"172\"', 696, 172, 0),
(42, '60f6a0179ead91702a45d40d3e294001', 'Images/2018-03-11/60f6a0179ead91702a45d40d3e294001.jpg', 'image/jpeg', 'width=\"188\" height=\"143\"', 188, 143, 0),
(43, '050cbb7ca1f56dc4da9144516963b699', 'Images/2018-03-11/050cbb7ca1f56dc4da9144516963b699.jpg', 'image/jpeg', 'width=\"251\" height=\"51\"', 251, 51, 0),
(44, '0a0046a9dd2370a09e14eace0b218eca', 'Images/2018-03-11/0a0046a9dd2370a09e14eace0b218eca.jpg', 'image/jpeg', 'width=\"209\" height=\"35\"', 209, 35, 0),
(45, '86c0515277044c256393b5677ca61228', 'Images/2018-03-11/86c0515277044c256393b5677ca61228.jpg', 'image/jpeg', 'width=\"79\" height=\"55\"', 79, 55, 0),
(46, 'cd00545d9d888f8846004259f4424f04', 'Images/2018-03-11/cd00545d9d888f8846004259f4424f04.jpg', 'image/jpeg', 'width=\"230\" height=\"31\"', 230, 31, 0),
(47, '2982881e205d707b550cb000515bfb02', 'Images/2018-03-11/2982881e205d707b550cb000515bfb02.jpg', 'image/jpeg', 'width=\"97\" height=\"51\"', 97, 51, 0),
(48, '15426afae1fc19fea4770323e298ea69', 'Images/2018-03-11/15426afae1fc19fea4770323e298ea69.jpg', 'image/jpeg', 'width=\"212\" height=\"34\"', 212, 34, 0),
(49, '85a6c3205b221859173376cc055946d7', 'Images/2018-03-11/85a6c3205b221859173376cc055946d7.jpg', 'image/jpeg', 'width=\"117\" height=\"33\"', 117, 33, 0),
(50, '1501ff249e7d4880db3b97d84547af8c', 'Images/2018-03-11/1501ff249e7d4880db3b97d84547af8c.jpg', 'image/jpeg', 'width=\"220\" height=\"46\"', 220, 46, 0),
(51, '317cb9a9fa65e8557ea658801b8a2073', 'Images/2018-03-11/317cb9a9fa65e8557ea658801b8a2073.jpg', 'image/jpeg', 'width=\"198\" height=\"34\"', 198, 34, 0),
(52, '54ee04a8710d08eb30f33d2c2a6b3e2c', 'Images/2018-03-11/54ee04a8710d08eb30f33d2c2a6b3e2c.jpg', 'image/jpeg', 'width=\"157\" height=\"43\"', 157, 43, 0),
(53, '1a767084a47e00d3d3c04550b64f02bb', 'Images/2018-03-11/1a767084a47e00d3d3c04550b64f02bb.jpg', 'image/jpeg', 'width=\"464\" height=\"313\"', 464, 313, 0),
(54, '822d94e83e4ff2667a9d6e4fea63798e', 'Images/2018-03-14/822d94e83e4ff2667a9d6e4fea63798e.jpg', 'image/jpeg', 'width=\"800\" height=\"800\"', 800, 800, 0),
(55, '9bc4f7bb525b3af913176cea410b9f7f', 'Images/2018-03-23/9bc4f7bb525b3af913176cea410b9f7f.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(56, '8e675069d1dbd98ca523f119fe5fadb6', 'Images/2018-03-23/8e675069d1dbd98ca523f119fe5fadb6.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(57, 'e01eed7fce3844ebdb5dc0cfa9dfa98c', 'Images/2018-03-23/e01eed7fce3844ebdb5dc0cfa9dfa98c.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(58, 'b9e698c3e9b7b0566f6eabb951a2cb22', 'Images/2018-03-23/b9e698c3e9b7b0566f6eabb951a2cb22.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(59, '33baa97125bb595acaa139bea291c7a4', 'Images/2018-03-23/33baa97125bb595acaa139bea291c7a4.jpg', 'image/jpeg', 'width=\"1867\" height=\"1196\"', 1867, 1196, 0),
(60, 'c003c6c3bf0148ab92bb690bdca56e04', 'Images/2018-03-23/c003c6c3bf0148ab92bb690bdca56e04.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(61, 'cdb412655cbaf7c867edbe3b260a8689', 'Images/2018-03-23/cdb412655cbaf7c867edbe3b260a8689.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(62, '210f14e923fd8ff4a10aab35da39f4a4', 'Images/2018-03-23/210f14e923fd8ff4a10aab35da39f4a4.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(63, '14eb0d7fcb3b6634987dd888c7562176', 'Images/2018-03-23/14eb0d7fcb3b6634987dd888c7562176.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(64, '182d11dd0476fde04a73f10c39217519', 'Images/2018-03-23/182d11dd0476fde04a73f10c39217519.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(65, '9208cf15ba9b919290f86316fcc7c474', 'Images/2018-03-23/9208cf15ba9b919290f86316fcc7c474.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(66, 'f14ed58f4f1652499705d8f0d9c0895d', 'Images/2018-03-23/f14ed58f4f1652499705d8f0d9c0895d.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(67, '3e97ee56c40ffe86385037466ddd2620', 'Images/2018-03-23/3e97ee56c40ffe86385037466ddd2620.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(68, '98ddda6b1a505fe8982750ba8d725763', 'Images/2018-03-23/98ddda6b1a505fe8982750ba8d725763.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(69, '6719de9007df983c21c5cac78db13156', 'Images/2018-03-23/6719de9007df983c21c5cac78db13156.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(70, 'fc960a8ffdbf2f67144b57f256bd6419', 'Images/2018-03-23/fc960a8ffdbf2f67144b57f256bd6419.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(71, 'dcff3f15c7d3365933e8a475e09bc2dc', 'Images/2018-03-23/dcff3f15c7d3365933e8a475e09bc2dc.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(72, '29091181d344324ac383a7bda9832786', 'Images/2018-03-23/29091181d344324ac383a7bda9832786.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(73, 'b0c6c9af7f683280c74ca8b868c4cce4', 'Images/2018-03-23/b0c6c9af7f683280c74ca8b868c4cce4.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(74, '9b68fdad9e6f75b868dd557b07b473c6', 'Images/2018-03-23/9b68fdad9e6f75b868dd557b07b473c6.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(75, 'f924faf56ed2409fe2beb0dda8b9eb0e', 'Images/2018-03-23/f924faf56ed2409fe2beb0dda8b9eb0e.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(76, '47d8b5230dde0b7fba6582e6a16ce52d', 'Images/2018-03-23/47d8b5230dde0b7fba6582e6a16ce52d.jpg', 'image/jpeg', 'width=\"2000\" height=\"2000\"', 2000, 2000, 0),
(77, '5c4010f24bbafa4a9b086ccc73e8f0b3', 'Images/2018-03-26/5c4010f24bbafa4a9b086ccc73e8f0b3.jpg', 'image/jpeg', 'width=\"360\" height=\"120\"', 360, 120, 0),
(78, '0489c056b8afa0de666b4f1d5110a342', 'Images/2018-03-26/0489c056b8afa0de666b4f1d5110a342.jpg', 'image/jpeg', 'width=\"160\" height=\"120\"', 160, 120, 0),
(79, '626c4f0cf312358a7a2ef47887ea9b81', 'Images/2018-03-26/626c4f0cf312358a7a2ef47887ea9b81.jpg', 'image/jpeg', 'width=\"180\" height=\"120\"', 180, 120, 0),
(80, '47106ceac5c9c14246a162dd4484343a', 'Images/2018-03-26/47106ceac5c9c14246a162dd4484343a.jpg', 'image/jpeg', 'width=\"80\" height=\"120\"', 80, 120, 0),
(81, '91526a9d972bc0d711e1233eac2eeb6d', 'Images/2018-03-26/91526a9d972bc0d711e1233eac2eeb6d.jpg', 'image/jpeg', 'width=\"120\" height=\"120\"', 120, 120, 0),
(82, '91416b2cf588a8a8eadc4dda2110f35e', 'Images/2018-03-27/91416b2cf588a8a8eadc4dda2110f35e.jpg', 'image/jpeg', 'width=\"1920\" height=\"233\"', 1920, 233, 0),
(83, 'dd3a4668ff9d6a058647a1d007c8cbdf', 'Images/2018-03-27/dd3a4668ff9d6a058647a1d007c8cbdf.jpg', 'image/jpeg', 'width=\"720\" height=\"374\"', 720, 374, 0),
(84, 'df6fb7cdc0abb241ed4940a8a04c14ac', 'Videos/2018-03-27/df6fb7cdc0abb241ed4940a8a04c14ac.mp4', 'video/mp4', '', 0, 0, 0),
(85, '5beb2c71cc9f4bf510c39c8f4b790601', 'Images/2018-03-29/5beb2c71cc9f4bf510c39c8f4b790601.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(86, '36cc5cbbe1c16d6d4f5724ca6d881b73', 'Images/2018-03-29/36cc5cbbe1c16d6d4f5724ca6d881b73.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(87, 'db3648bdb42e66f78470553a266c199c', 'Images/2018-03-29/db3648bdb42e66f78470553a266c199c.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(88, '5674d238056f5858c9e7205d9b598dcf', 'Images/2018-03-29/5674d238056f5858c9e7205d9b598dcf.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(89, '61a10631b497aed629b09bf184b5a974', 'Images/2018-03-29/61a10631b497aed629b09bf184b5a974.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(90, '1f38599edca32ab1c52fe07150f6eec8', 'Images/2018-03-29/1f38599edca32ab1c52fe07150f6eec8.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(91, '266e69b1ec7e98b39ec4d5c8f582d008', 'Images/2018-03-29/266e69b1ec7e98b39ec4d5c8f582d008.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(92, '3c9d66f2e46d378386f847312fa660ca', 'Images/2018-03-29/3c9d66f2e46d378386f847312fa660ca.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(93, 'f7f3d46fbcb2c49d9c528d3ecdf7d586', 'Images/2018-03-29/f7f3d46fbcb2c49d9c528d3ecdf7d586.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(94, '3081a09102dcf9df8453b5445a1d42e9', 'Images/2018-03-29/3081a09102dcf9df8453b5445a1d42e9.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(95, '7e4199219e3b31244910271cc37b2b9d', 'Images/2018-03-29/7e4199219e3b31244910271cc37b2b9d.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(96, 'cff95d9b21317ffa88289fa4df136a48', 'Images/2018-03-29/cff95d9b21317ffa88289fa4df136a48.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(97, '889f386d2e997d79b27f221835383227', 'Images/2018-03-29/889f386d2e997d79b27f221835383227.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(98, '70406c69069989b8fcfef5dc8dbf2cf0', 'Images/2018-03-29/70406c69069989b8fcfef5dc8dbf2cf0.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(99, '86fe08a399cf263facf7e0a174eefdd8', 'Images/2018-03-29/86fe08a399cf263facf7e0a174eefdd8.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(100, 'd6616bfdf54e22da8c87136ce0335d1b', 'Images/2018-03-29/d6616bfdf54e22da8c87136ce0335d1b.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(101, '7b16afd23afda715644cc96d357f0d99', 'Images/2018-03-29/7b16afd23afda715644cc96d357f0d99.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(102, '7f24a8960d69bf0afbd13ef3f52f266e', 'Images/2018-03-29/7f24a8960d69bf0afbd13ef3f52f266e.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(103, 'd77f8142160ca776cb3cb641a26563c0', 'Images/2018-03-29/d77f8142160ca776cb3cb641a26563c0.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(104, '9e84a599213f2dd4fbbea5f4f3ad05d8', 'Images/2018-03-29/9e84a599213f2dd4fbbea5f4f3ad05d8.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(105, '585c27ba63ffcb134bb784f32082795e', 'Images/2018-03-29/585c27ba63ffcb134bb784f32082795e.jpg', 'image/jpeg', 'width=\"1733\" height=\"1733\"', 1733, 1733, 0),
(106, 'f518c05375225c5cc07de2862cb4c19d', 'Images/2018-03-29/f518c05375225c5cc07de2862cb4c19d.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(107, '1e3508f49037cfdb8224a3a3b7c68433', 'Images/2018-03-29/1e3508f49037cfdb8224a3a3b7c68433.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(108, '16cdb5e1fc692f9d60c499505715add2', 'Images/2018-03-29/16cdb5e1fc692f9d60c499505715add2.jpg', 'image/jpeg', 'width=\"1200\" height=\"1200\"', 1200, 1200, 0),
(109, 'decf34ff5552e2db7e9ffc9ad43fb671', 'Images/2018-03-29/decf34ff5552e2db7e9ffc9ad43fb671.jpg', 'image/jpeg', 'width=\"700\" height=\"190\"', 700, 190, 0),
(110, 'bc73a6102a6ca03f4ee60f2ac40d0cae', 'Images/2018-03-29/bc73a6102a6ca03f4ee60f2ac40d0cae.jpg', 'image/jpeg', 'width=\"700\" height=\"190\"', 700, 190, 0),
(111, '50fe8b7d5c2d58d050886c724a7b7a5a', 'Images/2018-03-29/50fe8b7d5c2d58d050886c724a7b7a5a.jpg', 'image/jpeg', 'width=\"700\" height=\"190\"', 700, 190, 0),
(112, '9dcf4fda62ade0e6b42134e2b56c1d1a', 'Images/2018-03-29/9dcf4fda62ade0e6b42134e2b56c1d1a.jpg', 'image/jpeg', 'width=\"700\" height=\"190\"', 700, 190, 0);

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
  `GROUPID` int(11) DEFAULT '0',
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

INSERT INTO `ni_cloud_tablerowmeta` (`ID`, `TYPE`, `TABLENAME`, `GROUPID`, `TITLE`, `DESCRIPTION`, `UID`, `PUBTIME`, `RANK`, `LEVEL`, `SK_COMMENTS`, `SK_CTIME`, `SK_MTIME`, `SK_STATE`, `SK_IS_RECYCLED`) VALUES
(1, 'news', 'news', 1, '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', -7, '2018-01-21 00:00:00', 5, 0, 0, '0000-00-00 00:00:00', '2018-03-09 16:07:03', 1, 1),
(2, 'news', 'news', 2, '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品', '热烈祝贺2017年来利洪食品集团全国经销商大会暨新品 发布会圆满成功', -7, '2018-01-21 00:00:00', 5, 0, 0, '0000-00-00 00:00:00', '2018-03-09 16:54:01', 1, 1),
(3, 'job', 'positions', 0, '前台专员', '前台专员', -7, '2018-01-25 00:00:00', 5, 0, 0, '0000-00-00 00:00:00', '2018-03-16 09:40:25', 1, 1),
(4, 'job', 'positions', 0, '天猫运营', '天猫运营', -7, '2018-01-25 00:00:00', 5, 10, 0, '0000-00-00 00:00:00', '2018-03-16 09:40:22', 1, 1),
(6, 'job', 'positions', 0, '123456', '1234567', -7, '2018-03-06 13:49:14', 5, 0, 0, '2018-03-06 13:49:29', '2018-03-07 00:44:08', 1, 1),
(7, 'job', 'positions', 0, '34567890', '456789', -7, '2018-03-06 13:49:40', 5, 0, 0, '2018-03-06 13:49:50', '2018-03-07 00:44:06', 1, 1),
(10, 'job', 'positions', 0, '111111111111', '11111111111111111', -7, '2018-03-06 15:00:01', 5, 0, 0, '2018-03-06 15:00:07', '2018-03-07 00:44:02', 1, 1),
(12, 'news', 'news', 1, '广东广播电视台《品牌观察》栏目专访来利洪食品集团', 'p.p1 {margin: 0.0px 0.0px 0.0px 0.0px; text-align: justify; text-indent: 21.0px; font: 10.5px \'PingFang SC\'; color: #000000; -', -7, '2018-03-07 10:04:56', 5, 0, 0, '2018-03-07 10:06:26', '2018-03-08 23:35:28', 1, 1),
(13, 'news', 'news', 1, '广东广播电视台《品牌观察》栏目专访来利洪食品集团', '2017年11月5日，关于来利洪食品集团的品牌宣传视频在广东广播电视台《品牌观察》栏目播出，约有近百万市民收看节目。《品牌观察》栏目立足广东，面向全国，是肩负“品牌强国”创业使命的官方电视台栏目，对各行业的国产品牌进行苛刻筛选，旨在挖掘国内优质品牌，与万千', -7, '2017-11-09 10:35:24', 7, 0, 0, '2018-03-09 10:37:45', '2018-03-30 10:00:52', 1, 0),
(14, 'news', 'news', 1, '来利洪食品集团在厂区开展消防安全演习', ' 为了增强员工的安全防火意识，有序处理突发事故，提高在火灾中的自救、互救能力，2017年4月下旬，来利洪食品集团在厂区开展消防安全演习。演习前，大家首先进行了基本消防知识培训，了解关于火灾的预防和火场逃生技能，随后填写了调查问卷，最后在篮球场学习', -7, '2017-04-28 11:49:12', 5, 0, 0, '2018-03-09 11:50:26', '2018-03-09 11:52:40', 1, 0),
(15, 'news', 'news', 1, '欢迎回家——祝贺2017来利洪食品集团全国经销商大会暨新品发布会圆满成功', '2017年6月16日，以“欢迎回家”为主题的来利洪食品集团2017年全国经销商大会暨新品发布会在广州来利洪集团总部隆重召开，全国数百名经销商家人欢聚一堂，共襄盛会，再续情谊。大会正式开始前，在各厂生产主管的陪同下，经销商家人先后参观了瑞达饼干生产车间、溏心', -7, '2017-06-19 11:55:12', 5, 0, 0, '2018-03-09 11:57:15', '2018-03-30 10:01:36', 1, 0),
(16, 'news', 'news', 1, '畅游天堂岛屿，共谱美好回忆 ——记溏心月饼2015年度优秀经销商代表巴厘岛梦幻之旅', '位于印度尼西亚的巴厘岛，有“天堂岛屿”之称，绮丽风光闻名于世，2017年3月末，来利洪食品集团邀请溏心月饼2015年度优秀经销商代表，飞赴巴厘岛开启梦幻之旅，集团总经理刘海陶一同出行。在为期5日的悠长旅程中，大家全程入住当地五星级酒店乐心登卡帕，深度游览充', -7, '2017-04-03 11:58:36', 5, 0, 0, '2018-03-09 12:00:25', '2018-03-30 10:00:42', 1, 0),
(17, 'news', 'news', 1, '全员参与，持续改进 —— 3月质量主题培训圆满召开', '导读    产品的质量决定了产品的生命力,一个公司的质量管理水平决定了公司在市场中的竞争力，来利洪集团作为“中国质量诚信企业”，始终把产品质量放在首位，坚持不断提高检测标准，不断完善质量保证体系，力求把“来利洪出品”', -7, '2017-03-17 15:53:03', 5, 0, 0, '2018-03-09 15:54:32', '2018-03-09 15:56:26', 1, 0),
(18, 'news', 'news', 1, '品尝美食，逐浪银滩——集团优秀员工三亚之行', '凡走过必留下痕迹，凡付出总有收获。2017年3月3-6日，6位在2016年工作表现突出的优秀员工，获得集团奖励，前往拥有“东方夏威夷”美誉的国际知名旅游城市——三亚，尽情游玩。在四天的旅途中，一行人除了游览西岛、亚龙湾、玫瑰谷等多个景点和入住5星级酒店，还', -7, '2017-03-10 15:56:39', 5, 0, 0, '2018-03-09 15:57:52', '2018-03-09 15:58:28', 1, 0),
(19, 'news', 'news', 1, '外贸部6名员工获集团奖励韩国旅游', '为了表达集团对员工优异工作成绩的肯定，2017年3月6-11日，外贸部6名优秀员工获集团赞助，飞赴韩国享受长达一周的悠长假期。韩国之旅的行程安排丰富多彩。除了游玩主题乐园、浪漫海岛，漫步文化、购物街区，大家还参观了热门韩剧的拍摄地，一路上更尝试了众多异国美', -7, '2017-03-20 15:58:36', 5, 0, 0, '2018-03-09 16:00:18', '2018-03-09 16:00:40', 1, 0),
(20, 'news', 'news', 1, '生产部全体员工赴清远开展户外拓展活动', '为了提高团队凝聚力，进一步增强团队精神，2017年3月27-29日，集团组织生产部全体员工前往清远先锋拓展基地开展为期3天2夜的户外拓展活动。本次拓展活动主题为“激发无限潜能，打造卓越团队”，拓展内容包含再接再厉、怪兽过河、极限时速、信任倒和高空挑战等经典', -7, '2017-04-03 16:01:25', 5, 0, 0, '2018-03-09 16:02:39', '2018-03-09 16:02:39', 1, 0),
(21, 'news', 'news', 1, '集团生产部骨干员工赴四川学习培训', '近年来，集团生产能力逐步增强，生产品种陆续增多，为了让员工熟悉新款设备，掌握生产技巧，2017年四月中旬，来利洪食品集团生产部罗业祠等5名年轻骨干赴四川成都温江进行为期1个月的学习培训。本次学习任务繁重，但意义重大，在陌生而艰苦的学习环境中，罗业祠等员工以', -7, '2017-04-18 16:04:28', 5, 0, 0, '2018-03-09 16:05:44', '2018-03-09 16:05:44', 1, 0),
(22, 'news', 'news', 1, '来利洪食品集团参加第十八届上海中食展', '2017年5月中旬，第十八届SIAL China中国国际食品和饮料展览会在上海新国际博览中心正式开幕。本届中食展展示面积达149500平方米，规模盛大，参展商皆为行业领军及新秀，作为业内知名企业，来利洪食品集团受邀参展。会上，在瞄准中秋节的众多食品中，来利', -7, '2017-05-18 16:08:02', 5, 0, 0, '2018-03-09 16:09:18', '2018-03-09 16:09:18', 1, 0),
(23, 'news', 'news', 1, '来利洪集团热心参与阳江扶贫公益活动', '2017年6月，来利洪食品集团积极参与由阳春市岗美镇人民政府、阳春市扶贫办等单位共同举办的“大爱有声”扶贫活动，向阳春当地贫困家庭捐赠总价值10万元物资。来利洪食品集团一直热心参与各类社会慈善公益活动，多年来，已累计为公益事业及扶贫项目捐赠超过1800万元', -7, '2017-06-28 16:09:49', 5, 0, 0, '2018-03-09 16:11:18', '2018-03-09 16:11:18', 1, 0),
(24, 'news', 'news', 1, '2017中秋佳节，溏心流心月饼热潮席卷重庆', '9月中旬，来利洪食品集团旗下品牌——溏心月饼，携2017年全新力作流心月饼在山城重庆刮起一股充满香港风情的“流心风暴”，引发全城瞩目，吸引万人参与活动。活动选址重庆人气聚集的万达广场及凯德广场，在商场中庭搭建立体香港街景与大型双层巴士模型，缤纷现代的场景化', -7, '2017-10-10 16:12:18', 5, 0, 0, '2018-03-09 16:15:24', '2018-03-09 16:15:24', 1, 0),
(25, 'news', 'news', 2, '饼干选购小常识', ' 饼干是以小麦粉、糖类、油脂等为主要原料经机制焙烤而成的食品。储存时间长,口感疏松，口味多样，老少咸宜，深受广大消费者的喜爱。 　　     市场上销售的饼干种类繁多，按生产工艺可分为：酥性饼干、', -7, '2017-01-12 16:41:15', 5, 0, 0, '2018-03-09 16:42:57', '2018-03-15 09:21:50', 1, 0),
(26, 'news', 'news', 2, '“网红月饼”成了“香饽饽”，暗藏哪些隐患？', '中秋在即，花样百出的“网红月饼”越来越火，成为受人追捧的“香饽饽”。这些“网红月饼”适应了网购的新趋势，为顾客提供了诸多便利，但也存在着一些隐患。　　穿上名牌“马甲”以次充好　　近日，不少消费者投诉，通过网络购买的香港美心、半岛酒店等知名品牌月饼是假货。有', -7, '2017-09-30 16:46:23', 5, 0, 0, '2018-03-09 16:48:05', '2018-03-15 09:21:35', 1, 0),
(27, 'news', 'news', 2, '我国饼干行业积极探索传统食品发展之道', '寻找适合自身发展的突破口本报讯 （记者鲍小铁）由中国焙烤食品糖制品工业协会（以下简称“中焙糖协”）饼干专业委员会主办，广州刚奇包装有限公司承办的2017年中焙糖协饼干专业委员会理事会暨行业年会，于近日在广东省珠海市召开。中国轻工业联合会兼职副会长、中焙糖协', -7, '2018-01-29 16:51:29', 5, 0, 0, '2018-03-09 16:53:27', '2018-03-15 09:21:16', 1, 0),
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

--
-- 转存表中的数据 `ni_pageads_ads`
--

INSERT INTO `ni_pageads_ads` (`id`, `title`, `type`, `content`, `link`, `display`, `hits`) VALUES
(1, '来利洪集团天猫旗舰店', 'image', '/Applications/YangRAMPageSuit/Renderer/Views/_PAGES/default/images/button.target2tmall.jpg', 'https://lailihong.tmall.com/shop/view_shop.htm?spm=a230r.1.14.10.302430585DS3hG&user_number_id=3039509730', 0, 0),
(2, '首页大banner', 'image', '/Applications/YangRAMPageSuit/Renderer/Views/_PAGES/default/images/index/poster.jpg', '/productions/', 0, 0),
(3, '首页视频广告', 'video', '/applications/uploads/files/c0abb241ed4940a815221148245ab9a10891081.mp4', '/productions/', 0, 0);

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

--
-- 转存表中的数据 `ni_pages_archives`
--

INSERT INTO `ni_pages_archives` (`id`, `archive_name`, `archive_desc`, `archive_image`, `archive_hp`) VALUES
(1, '关于来利洪', '关于来利洪', NULL, '/about/'),
(2, '代加工服务', '代加工服务', NULL, '/oem/'),
(3, '人力招聘', '人力招聘', NULL, '/hr/'),
(4, '联系我们', '联系我们', NULL, '/contactus/');

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

--
-- 转存表中的数据 `ni_pages_links`
--

INSERT INTO `ni_pages_links` (`id`, `menu`, `parent_id`, `name`, `type`, `value`, `alt`, `sort`, `state`) VALUES
(1, 0, 0, '关于来利洪', 'achv', '1', '关于来利洪', 0, 1),
(2, 0, 0, '旗下品牌与产品', 'link', 'productions/', '旗下品牌与产品', 1, 1),
(3, 0, 0, '代加工服务', 'achv', '2', '代加工服务', 3, 1),
(4, 0, 0, '新闻资讯', 'link', 'news/', '新闻资讯', 4, 1),
(5, 0, 0, '人力招聘', 'page', '11', '人力招聘', 5, 1),
(6, 0, 0, '联系我们', 'page', '12', '联系我们', 6, 1);

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
('logo', '/applications/uploads/files/ba702c3ea1fe0e5c15207758595aa532b347ef8.jpg', 1),
('default_page_content', '', 1),
('common_bottom', '版权所有© 2017<span style=\"font-family: &quot;Segoe UI&quot;, &quot;Microsoft Yahei UI&quot;, &quot;Microsoft Yahei&quot;, &quot;Microsoft Sans Serif&quot;, &quot;HanHei SC&quot;, PingHei, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">&nbsp; &nbsp;</span><span>广州来利洪食品集团&nbsp; &nbsp;地 址: 广州市白云区人和镇秀盛路三盛工业区自编1号<span style=\"font-family: &quot;Segoe UI&quot;, &quot;Microsoft Yahei UI&quot;, &quot;Microsoft Yahei&quot;, &quot;Microsoft Sans Serif&quot;, &quot;HanHei SC&quot;, PingHei, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">&nbsp; &nbsp;</span><span>粤ICP备14012344号<span style=\"font-family: &quot;Segoe UI&quot;, &quot;Microsoft Yahei UI&quot;, &quot;Microsoft Yahei&quot;, &quot;Microsoft Sans Serif&quot;, &quot;HanHei SC&quot;, PingHei, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 13px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400;\">&nbsp; &nbsp;</span>技术支持：<a href=\"http://www.yangram.com\" target=\"_blank\">唐云科技</a>', 1),
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

--
-- 转存表中的数据 `ni_pages_pages`
--

INSERT INTO `ni_pages_pages` (`id`, `alias`, `archive`, `parent`, `title`, `crttime`, `modtime`, `pubtime`, `thumb_inlist`, `description`, `banner`, `banner_link`, `content`, `template`, `more`, `state`) VALUES
(1, 'about', 1, 0, '集团简介', '2018-01-20 00:00:00', '2018-01-20 00:00:00', '2018-01-20 00:00:00', '/applications/uploads/files/9ead91702a45d40d15207769045aa536c91656e.jpg', '广州市来利洪饼业有限公司是广州市来利洪食品集团旗下一家集生产与销售，内贸与外贸于一体的专业做饼干的公司,地处美丽的花城广州市北郊......', '/applications/uploads/files/f588a8a8eadc4dda15220962275ab958632a784.jpg', NULL, '<p><img src=\"/applications/uploads/files/c0f2d0dcfcff509815207768595aa5369be98c2.jpg\" title=\"airscape.jpg\" alt=\"airscape.jpg\"/>\n &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p>来利洪食品集团坐落于广州市空港经济区，自1982年创办至今，历经三十余年飞速发展，现已成为旗下拥有来利洪饼业有限公司、来利快食面有限公司、来利饮用水有限公司等近10家子公司，占地面积500余亩、建筑面积超20万平方米的大型现代化食品生产基地，市场营销网络遍布全国。</p><p>来利洪食品集团专注于食品行业，实行多品牌发展战略，旗下拥“来利洪”、“溏心”、“卡慕”、“丹娜斯”、“瑞达”等多个品牌，其中“来利洪”及“卡慕”分别为“广东省著名商标”和“广州市著名商标”，在市场上已具有较高的知名度和美誉度。</p><p>来利洪食品集团以品质驱动企业发展，不懈追求产品质量提升，目前已建立起完善的质量保证体系，多次获评“中国质量诚信企业”，为企业的持续良性发展提供强有力的保障。</p><p>集团在不断发展的同时，不忘回报社会，热心参与慈善公益事业，秉承“回馈社会”的公益理念，已累积为公益事业及扶贫项目捐赠近2000万元。2015年，集团响应国家环保号召，斥资4000万元兴建工厂太阳能光伏发电站，实现绿色生产。</p><p>下一阶段，来利洪集团将积极顺应市场需求，以经济效益和社会效益并重，继续推陈出新，迈开生产技术革新的步伐，扩大品牌影响力，提升市场竞争力，努力达成“制造中国好食品，成就来利人梦想，回馈社会”的使命，力争成为世界一流的食品品牌运营商，为消费者送上更多美味享受。</p><p><br/></p>', 'about.niml', NULL, 1),
(2, 'history', 1, 1, '发展历程', '2018-01-20 00:00:00', '2018-01-20 00:00:00', '2018-01-20 00:00:00', '', '发展历程', '/applications/uploads/files/f588a8a8eadc4dda15220962175ab95859cf8a9.jpg', NULL, '<p>发展历程</p>', NULL, NULL, 1),
(3, 'culture', 1, 1, '企业文化', '2018-01-20 00:00:00', '2018-01-20 00:00:00', '2018-01-20 00:00:00', '', '企业文化', '/applications/uploads/files/f588a8a8eadc4dda15220962025ab9584a22bf5.jpg', NULL, '<p>企业愿景 ：成为世界一流食品品牌运营商。</p><p><br/></p><p>企业使命：制造中国好食品；成就来利人梦想；回馈社会。</p><p><br/></p><p>六大企业理念展现</p><p>经营理念：依法经营、规范管理。</p><p>管理理念：关注人本、关注过程、关注效率、关注细节、群策群立。</p><p>服务理念：以客户为中心，创造超越客户期望的服务体验。</p><p>安全理念：员工安全至上，发展安全为先质量理念：规范管理、质量第一。</p><p>人才理念：以爱勤修善学勇创来利洪历史新篇章。</p><p><br/></p><p>六大核心价值观展现</p><p>开放包容：尊重不同的看法，鼓励发表不同的意见；相互合作，相互支持，步调一致。</p><p>力求简洁：专注核心问题，明确决定与行动；复杂问题简单化。</p><p>实事求是：切中要点，表达清楚，言出必行，树立榜样；对事不对人。</p><p>相互信任：相互信任，严格保密，做一个可信赖的合作伙伴。</p><p>探讨、决定、实现：研究事物核心，积极参与讨论，言出必行。</p><p>主人翁精神：想主人事、干主人活、尽主人责、享主人乐。</p>', NULL, NULL, 1),
(5, 'responsibility', 1, 1, '社会责任', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '社会责任', '/applications/uploads/files/f588a8a8eadc4dda15220961795ab958334f189.jpg', NULL, '<p>社会责任</p>', NULL, NULL, 1),
(4, 'honor', 1, 1, '资质荣誉', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '资质荣誉', '/applications/uploads/files/f588a8a8eadc4dda15220961895ab9583de5359.jpg', NULL, '<p>资质荣誉</p>', NULL, NULL, 1),
(6, 'oem', 2, 0, '合作品牌', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '合作品牌', '/applications/uploads/files/f588a8a8eadc4dda15220960275ab9579bc220b.jpg', NULL, '<h2 class=\"title-cn\">合作品牌</h2><h3 class=\"title-en\">Cooperative brand</h3><p class=\"brand-logos\"><img src=\"/applications/uploads/files/a1f56dc4da91445115207772315aa5380f3f56a.jpg\" title=\"1.jpg\"/><img src=\"/applications/uploads/files/dd2370a09e14eace15207772315aa5380f4c4b9.jpg\" title=\"2.jpg\"/><img src=\"/applications/uploads/files/77044c256393b56715207772315aa5380f4d257.jpg\" title=\"3.jpg\"/><img src=\"/applications/uploads/files/9d888f884600425915207772315aa5380f6e32c.jpg\" title=\"5.jpg\"/><img src=\"/applications/uploads/files/205d707b550cb00015207772315aa5380f70f52.jpg\" title=\"4.jpg\"/><img src=\"/applications/uploads/files/e1fc19fea477032315207772315aa5380f7a63e.jpg\" title=\"6.jpg\"/><img src=\"/applications/uploads/files/5b221859173376cc15207772315aa5380f96e1d.jpg\" title=\"7.jpg\"/><img src=\"/applications/uploads/files/9e7d4880db3b97d815207772315aa5380f9f9ad.jpg\" title=\"9.jpg\"/><img src=\"/applications/uploads/files/fa65e8557ea6588015207772315aa5380f9fd54.jpg\" title=\"8.jpg\"/><img src=\"/applications/uploads/files/710d08eb30f33d2c15207772315aa5380fb951b.jpg\" title=\"10.jpg\"/></p><p><br/></p>', 'oem.niml', NULL, 1),
(7, 'scale', 2, 6, '生产规模', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '生产规模', '/applications/uploads/files/f588a8a8eadc4dda15220960525ab957b4950d3.jpg', NULL, '<p><br/></p><p>饼干OEM/ODM</p><p>集团下属广州市来利洪饼业有限公司，拥有超过30年的丰富饼干生产经验，多年来始终秉承“安全、卫生、品质、美味”的生产理念，具有完备的生产资质，不断引入先进生产设备和检测仪器，实现高效的机械化、自动化生产。我们从原材料开始苛求食品质量，2015年企业获评为“中国质量诚信企业”，2017年自主品牌“来利洪”荣获“广东省著名商标”称号。</p><p>企业现拥有专业高效的OEM/ODM一站式合作模式，自有厂房面积_______平方米，自主研发产品配方200多种，为奥飞、昌大昌等多家知名企业提供代加工服务，我公司所提供的产品质量和服务均得到高度评价。</p><p>现公司设有4条饼干生产线，2个包装车间。日生产能力达：苏打饼干13吨、曲奇饼干12吨……</p><p>欢迎国内外企业/公司/机构与本单位建立合作关系，热诚欢迎各界朋友前来参观、考察、洽谈业务。</p><p><br/></p>', NULL, NULL, 1),
(8, 'qualification', 2, 6, '生产资质', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '生产资质', '/applications/uploads/files/f588a8a8eadc4dda15220960755ab957cb338a5.jpg', NULL, '<p>生产资质</p>', NULL, NULL, 1),
(9, 'photos', 2, 6, '工厂实景', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '工厂实景', '/applications/uploads/files/f588a8a8eadc4dda15220960675ab957c3953fa.jpg', NULL, '<p>工厂实景</p>', NULL, NULL, 1),
(10, 'contact', 2, 6, '联系方式', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '联系方式', '/applications/uploads/files/f588a8a8eadc4dda15220959635ab9575b86f14.jpg', NULL, '<p>联系方式</p><p><br/></p>', NULL, NULL, 1),
(11, 'hr', 3, 0, '招聘岗位', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '/applications/uploads/files/3fe397a76317ca6015207786005aa53d6814d94.jpg', '招聘岗位', '/applications/uploads/files/ea022c42d5ffeaf215207785965aa53d645303a.jpg', NULL, '<p>联系人：刘小姐 13501473716</p><p>联系人：潘小姐 18926134286</p><p>地址：广州市白云区人和镇秀盛路三盛工业区自编1号</p>', 'hr.niml', NULL, 1),
(12, 'contactus', 4, 0, '联系方式', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '2018-01-25 00:00:00', '', '联系方式', '/applications/uploads/files/f588a8a8eadc4dda15220957365ab956786d57f.jpg', NULL, '<p>地址：广州市白云区人和镇秀盛路三盛工业区自编1号</p><p>电话：020-36251098</p>', 'contactus.niml', '{\n\"title\":\"广州来利洪饼业有限公司\",\n\"desc\": \"广州市白云区人和镇秀盛路三盛工业区自编1号\",\n\"location\":[113.330953, 23.316237]\n}', 1);

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
(6, 0, '<STD>', '/admin/', 'GZLAILI', 0, '{}', 1),
(7, 0, '<STD>', '/eyutouadmin/', 'EYUTOUADMIN', 0, '{}', 1),
(8, 0, '<ANY>', '/wechat', '1054', 0, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ni_reg_apps`
--

CREATE TABLE `ni_reg_apps` (
  `app_id` int(11) NOT NULL COMMENT '应用ID',
  `app_name` varchar(128) NOT NULL COMMENT '应用名',
  `app_scode` char(2) NOT NULL COMMENT '开发者内部短编号',
  `app_authorname` char(32) NOT NULL DEFAULT 'YangRAM' COMMENT '开发者',
  `app_installpath` varchar(128) DEFAULT NULL COMMENT '安装路径',
  `app_usedb` int(3) NOT NULL DEFAULT '0' COMMENT '所用数据库索引',
  `app_build_time` date NOT NULL COMMENT '应用发布时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='已购装商城应用登记表';

--
-- 转存表中的数据 `ni_reg_apps`
--

INSERT INTO `ni_reg_apps` (`app_id`, `app_name`, `app_scode`, `app_authorname`, `app_installpath`, `app_usedb`, `app_build_time`) VALUES
(0, 'YangRAM Market', 'MK', 'YangRAM', 'AdminSuit/Market/', 0, '0000-00-00'),
(1, 'YangRAM Contacts', 'CT', 'YangRAM', 'AdminSuit/Contacts/', 0, '0000-00-00'),
(2, 'YangRAM Registry', 'RG', 'YangRAM', 'AdminSuit/Registry/', 0, '0000-00-00'),
(1001, 'YangRAM CloudNotes', 'CN', 'YangRAM', 'CloudTableHelpers/CloudNotes/', 0, '2018-01-19'),
(1002, 'YangRAM Encyclopedia', 'EP', 'YangRAM', 'CloudTableHelpers/Encyclopedia/', 0, '2018-01-19'),
(1003, 'YangRAM Gallery', 'GL', 'YangRAM', 'CloudTableHelpers/Gallery/', 0, '2018-01-19'),
(1004, 'YangRAM Jobs', 'JB', 'YangRAM', 'CloudTableHelpers/Jobs/', 0, '2018-01-19'),
(1005, 'YangRAM Persons', 'PS', 'YangRAM', 'CloudTableHelpers/Persons/', 0, '2018-01-19'),
(1006, 'YangRAM Press', 'Pr', 'YangRAM', 'CloudTableHelpers/Press/', 0, '2018-01-19'),
(1007, 'YangRAM Videos', 'VD', 'YangRAM', 'CloudTableHelpers/Videos/', 0, '2018-01-19'),
(1008, 'YangRAM Storage Rack', 'SR', 'YangRAM', 'MallSuite/Storage/', 0, '0000-00-00'),
(1015, 'Book+', 'Bp', 'Tangram', 'IP/BookPlus/', 0, '0000-00-00'),
(1016, 'Comment+', 'Cm', 'Tangram', 'IP/CommentPlus/', 0, '0000-00-00'),
(1017, 'Evaluate+', 'Ep', 'Tangram', 'IP/EvaluatePlus/', 0, '0000-00-00'),
(1018, 'Like+', 'Lk', 'Tangram', 'IP/LikePlus/', 0, '0000-00-00'),
(1019, 'Support+', 'Su', 'Tangram', 'IP/SupportPlus/', 0, '0000-00-00'),
(1020, 'Favorite+', 'Fa', 'Tangram', 'IP/FavoritePlus/', 0, '0000-00-00'),
(1021, 'Sign+', 'Sp', 'Tangram', 'IP/SignPlus/', 0, '0000-00-00'),
(1022, 'YangRAM Listen', 'Lt', 'YangRAM', 'NFA/Listen/', 0, '0000-00-00'),
(1023, 'YangRAM Watch', 'Gk', 'YangRAM', 'NFA/Watch/', 0, '0000-00-00'),
(1024, 'YangRAM Read', 'Yd', 'YangRAM', 'NFA/Read/', 0, '0000-00-00'),
(1025, 'YangRAM Play', 'Yx', 'YangRAM', 'NFA/Play/', 0, '0000-00-00'),
(1026, 'YangRAM News', 'Xw', 'YangRAM', 'NFA/News/', 0, '0000-00-00'),
(1027, 'YangRAM Weather', 'Tq', 'YangRAM', 'NFA/Weather/', 0, '0000-00-00'),
(1028, 'YangRAM Stock', 'Gp', 'YangRAM', 'NFA/Stock/', 0, '0000-00-00'),
(1234, 'Microivan FastGPS', 'Fp', 'MicroIvan', 'FastGPS/', 0, '0000-00-00'),
(6109, 'Microivan  Blog', 'Bg', 'MicroIvan', 'Multilingual/Blog/', 0, '0000-00-00'),
(6885, 'Microivan  BBS', 'Bg', 'MicroIvan', 'BBS/', 0, '0000-00-00'),
(6886, 'Microivan  Fourm', 'Bg', 'MicroIvan', 'Fourm/', 0, '0000-00-00'),
(1054, 'YangRAM WeChat Tools', 'WX', 'YangRAM', 'WeChatTools', 0, '0000-00-00');

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
(1, 'en-US', 'Your Tangram', 'your name', 'no content', 'no content', 'no content');

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
(1, 0, 'admin', 'Administrator', 'Administrator', '/applications/uploads/files/4b8512215a30cac5346.jpg', '2b1af9bdd3a5e5b816fefe468d8dac82', '2015-07-12 18:17:37', '这个家伙很懒'),
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
(1, 'Admin', 'c9b522cffa52175b82f89431ec68d5a7', 1, '/applications/uploads/files/cc26775220c32188228.jpg', 'zh-CN');

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
-- Indexes for table `ni_1008_brands`
--
ALTER TABLE `ni_1008_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_1008_categories`
--
ALTER TABLE `ni_1008_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_1008_category_attrs`
--
ALTER TABLE `ni_1008_category_attrs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `ni_1008_productions`
--
ALTER TABLE `ni_1008_productions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_1008_production_attrs_map`
--
ALTER TABLE `ni_1008_production_attrs_map`
  ADD PRIMARY KEY (`production_id`,`attribute_id`);

--
-- Indexes for table `ni_1008_types`
--
ALTER TABLE `ni_1008_types`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `ni_cloud_filemeta_protected`
--
ALTER TABLE `ni_cloud_filemeta_protected`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_filemeta_public`
--
ALTER TABLE `ni_cloud_filemeta_public`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ni_cloud_folders`
--
ALTER TABLE `ni_cloud_folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ni_cloud_groups`
--
ALTER TABLE `ni_cloud_groups`
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
-- Indexes for table `ni_cloud_sources_protected`
--
ALTER TABLE `ni_cloud_sources_protected`
  ADD PRIMARY KEY (`SID`),
  ADD UNIQUE KEY `HASH` (`HASH`);

--
-- Indexes for table `ni_cloud_sources_public`
--
ALTER TABLE `ni_cloud_sources_public`
  ADD PRIMARY KEY (`SID`),
  ADD UNIQUE KEY `HASH` (`HASH`);

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
-- 使用表AUTO_INCREMENT `ni_1008_brands`
--
ALTER TABLE `ni_1008_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `ni_1008_categories`
--
ALTER TABLE `ni_1008_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `ni_1008_category_attrs`
--
ALTER TABLE `ni_1008_category_attrs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `ni_1008_productions`
--
ALTER TABLE `ni_1008_productions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=67;

--
-- 使用表AUTO_INCREMENT `ni_1008_types`
--
ALTER TABLE `ni_1008_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
-- 使用表AUTO_INCREMENT `ni_cloud_folders`
--
ALTER TABLE `ni_cloud_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `ni_cloud_groups`
--
ALTER TABLE `ni_cloud_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `ni_cloud_sources_protected`
--
ALTER TABLE `ni_cloud_sources_protected`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_cloud_sources_public`
--
ALTER TABLE `ni_cloud_sources_public`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `ni_pages_archives`
--
ALTER TABLE `ni_pages_archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `ni_pages_comments`
--
ALTER TABLE `ni_pages_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ni_pages_links`
--
ALTER TABLE `ni_pages_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用表AUTO_INCREMENT `ni_pages_pages`
--
ALTER TABLE `ni_pages_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `ni_reg_appdirs`
--
ALTER TABLE `ni_reg_appdirs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=9;

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
