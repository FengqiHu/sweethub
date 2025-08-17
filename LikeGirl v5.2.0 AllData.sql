-- 使用目标数据库
USE mylove_page;

-- 设置 SQL 模式及时区
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- 1. 文章表
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articletext` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `articletitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `articlename` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `articletime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `article` (`id`, `articletext`, `articletime`, `articletitle`, `articlename`) VALUES
(1, '<quote>引用内容样式</quote>\n\n<hr>\n\n\n<h1>H1文字大小演示</h1>\n\n<hr>\n\n\n<h2>H2文字大小演示</h2>\n\n<hr>\n\n\n<h3>H3文字大小演示</h3>\n\n<hr>\n\n\n<h4>H4文字大小演示</h4>\n\n<hr>\n\n\n<h5>H5文字大小演示</h5>\n\n<hr>\n\n\n<h6>H6文字大小演示</h6>\n\n<hr>\n\n\n<b>加粗字体</b>\n<s>删除线字体</s>\n<i>斜体</i>\n<code>强调内容</code>\n\n<center>文本居中</center>\n\n\n<!--分割线-->\n<hr>\n<quote>插入图片</quote>\n<img alt=\"\" src=\"https://lovey.kikiw.cn/Style/img/Cover.webp\">\n<!--分割线-->\n<hr>\n<quote>插入视频</quote>\n\n<video src=\"https://classpic.kikiw.cn/video/fengjing.mp4\" controls></video>\n<!--分割线-->\n<hr>', '2024-11-08 15:31:21', 'Like_Girl v5.2.0文章语法', 'Ki.');


-- 2. 自定义设置表
DROP TABLE IF EXISTS `diySet`;
CREATE TABLE `diySet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `headCon` text NOT NULL,
  `footerCon` text NOT NULL,
  `cssCon` text NOT NULL,
  `Pjaxkg` varchar(1) NOT NULL COMMENT 'pjax开关',
  `Blurkg` varchar(1) NOT NULL COMMENT '高斯模糊开关',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2;

INSERT INTO `diySet` (`id`, `headCon`, `footerCon`, `cssCon`, `Pjaxkg`, `Blurkg`) VALUES
(1, '<!-- 这里可以嵌入自定义字体CDN加速地址 -->', '&lt;!--&lt;script src=&quot;https://img-love.kikiw.cn/jsxg/yh/yinghua.js&quot;&gt;&lt;/script&gt;--&gt;', '/* 这里可以写入自定义CSS样式内容 无需带 style 标签 */', '1', '1');


-- 3. 黑名单表（原IPerror表名修正为BlackList）
DROP TABLE IF EXISTS `BlackList`;
CREATE TABLE `BlackList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipAdd` varchar(100) NOT NULL COMMENT 'ip归属地',
  `Time` varchar(200) NOT NULL COMMENT '时间',
  `State` text NOT NULL COMMENT '拉黑ip',
  `text` varchar(100) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 4. 留言表
DROP TABLE IF EXISTS `leaving`;
CREATE TABLE `leaving` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名字',
  `text` text COLLATE utf8_unicode_ci NOT NULL COMMENT '留言内容',
  `time` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ip记录',
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '省/城市',
  `updated_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `leaving` (`id`, `name`, `text`, `time`, `ip`, `city`) VALUES
(1, 'Ki.', 'LikeGirlv5.2.0留言测试', '1730984690', '112.97.203.248', '广东省');


-- 5. 违禁词表
DROP TABLE IF EXISTS `bannedWords`;
CREATE TABLE `bannedWords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jiequ` varchar(10) NOT NULL COMMENT '截取长度',
  `lanjie` varchar(500) NOT NULL COMMENT '违禁符号',
  `lanjiezf` varchar(500) NOT NULL COMMENT '违禁词',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2;

INSERT INTO `bannedWords` (`id`, `jiequ`, `lanjie`, `lanjiezf`) VALUES
(1, '100', '`~!@#$^&*()=|{}\':;\',\\\\[\\\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“\'。，、？', '操垃圾傻逼妈');


-- 6. 用户表
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) NOT NULL COMMENT '登录用户名',
  `password` char(32) NOT NULL COMMENT '登录密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2;

INSERT INTO `user` (`id`, `user`, `password`) VALUES
(1, 'admin', '6f87ada9b67a092b27bcaf094c31aa41');


-- 7. 图片表
DROP TABLE IF EXISTS `loveImg`;
CREATE TABLE `loveImg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imgDatd` varchar(100) NOT NULL COMMENT '日期',
  `imgText` varchar(200) NOT NULL COMMENT '描述',
  `imgUrl` varchar(200) NOT NULL COMMENT '外链',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=10;

INSERT INTO `loveImg` (`id`, `imgDatd`, `imgText`, `imgUrl`) VALUES
(1, '2024-11-08', '夏至末至 我最喜欢听的追光者~', 'https://i.mij.rip/2024/11/06/4ccd1ebf620d99ead9b881270eef09fe.webp'),
(2, '2024-11-08', '太可爱啦 好像是叫“团团”来着~', 'https://i.mij.rip/2024/11/06/5f26be4a4f3f40ac7e754e9cbcf05005.webp'),
(3, '2024-11-08', '最近不错的一部港片 很好看', 'https://i.mij.rip/2024/11/06/8e5ddbc590fa7a1c32ef05faf58617e4.webp'),
(4, '2024-11-08', '入手了一辆公路车 骑了30公里 现在屁股发麻...', 'https://i.mij.rip/2024/11/06/eaf38a4ff44c4b01d3d4162d6df8adca.webp'),
(5, '2024-11-08', '咕噜这清澈愚蠢的眼神', 'https://i.mij.rip/2024/11/06/e4fb1f801ac5a9ea3972f0fcc2a8dd2f.webp'),
(6, '2024-11-08', '咕噜看起来还是有些颜值的呀', 'https://i.mij.rip/2024/11/06/8414967b9df32aa18daa1ee4f7aa279b.webp'),
(7, '2024-11-08', '入手的公路车已经不想骑了 来了东莞长安这边', 'https://i.mij.rip/2024/11/07/db5b6fb76f036086a7c3604eea43ed4f.webp'),
(8, '2024-11-08', 'LikeGirl v5.2.0情侣小站', 'https://blog.kikiw.cn/img/likegirlCover.png'),
(9, '2024-11-08', '下班回来的路上和小丽看到晚霞了', 'https://i.mij.rip/2024/11/07/0ee5d10be62700895febedbe7e69969e.jpeg');


-- 8. 恋爱清单表
DROP TABLE IF EXISTS `lovelist`;
CREATE TABLE `lovelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` int(1) NOT NULL COMMENT '是否完成',
  `eventname` varchar(200) CHARACTER SET utf8mb4 NOT NULL COMMENT '事件内容',
  `imgurl` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=84;

INSERT INTO `lovelist` (`id`, `icon`, `eventname`, `imgurl`) VALUES
(1, 1, '一起去电影院看一场电影🎬', 'https://i.mij.rip/2024/11/06/8e5ddbc590fa7a1c32ef05faf58617e4.webp'),
(2, 0, '一起穿情侣装逛街🧡', '0'),
(3, 0, '一起去一趟迪士尼游乐园🍉', '0'),
(4, 0, '一起去游泳🍇', '0'),
(5, 0, '一起唱次歌并且录下来🍓', '0'),
(6, 0, '一起在厨房做次饭🍈', '0'),
(7, 0, '一起过次烛光晚餐🍒', '0'),
(8, 0, '一起过生日🍑', '0'),
(9, 0, '一起打扫卫生🥭', '0'),
(10, 0, '一起给对方写信，然后读给对方听🍍', '0'),
(11, 0, '一起去一次鬼屋🥥', '0'),
(12, 0, '一起去蹦极🥝', '0'),
(13, 0, '一起养一只宠物🍅', '0'),
(14, 0, '一起研究口红色号🌽', '0'),
(15, 0, '一起给对方化妆🧅', '0'),
(16, 0, '一起为对方抹指甲油🥕', '0'),
(17, 0, '一起去做次陶艺🥗', '0'),
(18, 0, '一起去吃一次全家桶🥔', '0'),
(19, 0, '一起熬夜通宵跨年🍠', '0'),
(20, 0, '一起去旅游🥯', '0'),
(21, 0, '一起去爬山⛰🧇', '0'),
(22, 0, '一起坐一次摩天轮🥞', '0'),
(23, 0, '一起拍视频记录生活🧀', '0'),
(24, 0, '一起为对方刷牙，然后亲亲🍗', '0'),
(25, 0, '一起去看一次海，去沙滩🍖', '0'),
(26, 0, '互穿对方的衣服，拍照留念🥩', '0'),
(27, 0, '一起逛超市买好吃的🍤', '0'),
(28, 0, '一起坐一次热气球🥓', '0'),
(29, 0, '一起看书，分享自己喜欢的书籍🍔', '0'),
(30, 0, '一起在下雨天追剧🍟', '0'),
(31, 0, '一起做一次蛋糕甜点🌭', '0'),
(32, 0, '一起看日出看日落🍕', '0'),
(33, 0, '一起上下班，坐地铁🍝', '0'),
(34, 0, '一起坐一次飞机🥪', '0'),
(35, 0, '一起种花草🌮', '0'),
(36, 0, '一起用情侣手机壳🌯', '0'),
(37, 0, '一起去一次海底世界🥙', '0'),
(38, 0, '一起喝醉一次🧆', '0'),
(39, 0, '一起打扑克牌🍜', '0'),
(40, 0, '一起修理电器🍲', '0'),
(41, 0, '一起看烟花🥘', '0'),
(42, 0, '一起吃火锅🧂', '0'),
(43, 0, '一起庆祝恋爱纪念日🧈', '0'),
(44, 0, '一起看雪，堆雪人🍥', '0'),
(45, 0, '一起和朋友们去吃饭🍱', '0'),
(46, 0, '一起跳舞🍣', '0'),
(47, 0, '一起听音乐，听同一首歌🍙', '0'),
(48, 0, '一起坐一次船🍛', '0'),
(49, 0, '一起露营，住一次帐篷🍘', '0'),
(50, 0, '一起DIY手工🍚', '0'),
(51, 0, '给对方准备礼物🥟', '0'),
(52, 0, '一起去我们上过的小学，中学，大学🍢', '0'),
(53, 0, '一起在沙发上躺着🍡', '0'),
(54, 0, '一起睡个懒觉，赖个床🍧', '0'),
(55, 0, '偷偷为对方买喜欢又舍不得的东西🍨', '0'),
(56, 0, '一起坐一次巴士，在没去过的地方下车🍦', '0'),
(57, 0, '一起为布置小家出主意🍰', '0'),
(58, 0, '一起在午夜看一次恐怖片🎂', '0'),
(59, 0, '一起去挑选一束花🧁', '0'),
(60, 0, '一起去跳一次广场舞🥧', '0'),
(61, 0, '一起为对方按摩一次🍮', '0'),
(62, 0, '一起放一次风筝🍭', '0'),
(63, 0, '一起吐槽一次对方的缺点🍬', '0');


-- 9. 网站信息表
DROP TABLE IF EXISTS `text`;
CREATE TABLE `text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boy` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '男name',
  `girl` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '女name',
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站标题',
  `logo` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站logo',
  `writing` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站文案',
  `boyimg` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '男QQ',
  `girlimg` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '女QQ',
  `startTime` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '开始时间',
  `icp` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站备案号',
  `Copyright` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站版权',
  `card1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `card2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `card3` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `deci1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `deci2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `deci3` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bgimg` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '首页背景图片地址',
  `userQQ` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '站长QQ',
  `userName` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'userName',
  `Animation` int(1) NOT NULL COMMENT '动画开关',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `text` (`id`, `boy`, `girl`, `title`, `logo`, `writing`, `boyimg`, `girlimg`, `startTime`, `icp`, `Copyright`, `card1`, `card2`, `card3`, `deci1`, `deci2`, `deci3`, `bgimg`, `userQQ`, `userName`, `Animation`) VALUES
(1, 'Ki', 'Li', 'Like_Girl v5.2.0', 'Like_Girl v5.2.0', '喜欢花 喜欢浪漫 喜欢你~', '647159607', '917640289', '2022-06-05T00:07', '粤ICP备2021037776号', 'Copyright © 2022 - 2024 Like_Girl All Rights Reserved.', '点点滴滴', '留言板', '关于我们', '有人愿意听你碎碎念念也很浪漫', '在这里写下我们的留言祝福', '我们之间认识的经历回忆', 'https://lovey.kikiw.cn/Style/img/Cover.webp', '3439780232', 'Ki', 1);


-- 10. 警告日志表
DROP TABLE IF EXISTS `warning`;
CREATE TABLE `warning` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL COMMENT 'ip地址',
  `gsd` varchar(50) NOT NULL COMMENT '归属地',
  `time` varchar(80) NOT NULL COMMENT '时间',
  `file` varchar(100) NOT NULL COMMENT '路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 提交事务，完成数据库初始化
COMMIT;
