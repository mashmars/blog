/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-04-20 09:18:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `category_id` int(5) NOT NULL,
  `tag_id` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `descript` varchar(255) DEFAULT NULL,
  `content` longtext,
  `views` int(6) DEFAULT NULL,
  `created_at` int(11) DEFAULT '0',
  `updated_at` int(11) DEFAULT '0',
  `status` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `status_reply` tinyint(4) DEFAULT '1' COMMENT '是否可回复',
  `position` tinyint(4) DEFAULT '0' COMMENT '是否首页显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1', '4', '4', 'php递归函数return会出现无法正确返回想要值的情况', '递归返回值，如果不能正常处理，程序是没有返回值的', '<pre style=\"background-color:#2b2b2b;color:#a9b7c6;font-family:\'宋体\';font-size:12.0pt;\"><span style=\"color:#629755;background-color:#232525;font-style:italic;\">/**<br></span><span style=\"color:#629755;background-color:#232525;font-style:italic;\"> *判断上级的该区是否有人，如果没有人，满足条件直接分配上  如果有人，则遍历下级的一区是否有人 没人则满足条件分配上<br></span><span style=\"color:#629755;background-color:#232525;font-style:italic;\"> * （考虑个特殊情况，如果分配的是2区，要先判断同级的1区是否有安排人，没有则直接安排上不用再遍历下级了）<br></span><span style=\"color:#629755;background-color:#232525;font-style:italic;\"> * 如果同级的1区有人，则遍历属于这个人下级且是2区的下级1区是否有人，没人则分配安排上<br></span><span style=\"color:#629755;background-color:#232525;font-style:italic;\"> * 加行锁 防止同时两个人挂一个人下面的情况<br></span><span style=\"color:#629755;background-color:#232525;font-style:italic;\"> * $userid 注册会员id $ownid 推荐人id $init_zone注册选择的分区 $pid 节点人的id $zone 要找的哪个区 遍历用<br></span><span style=\"color:#629755;background-color:#232525;font-style:italic;\"> */<br></span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">private function </span><span style=\"color:#ffc66d;background-color:#232525;\">add_zone</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#9876aa;background-color:#232525;\">$userid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#9876aa;background-color:#232525;\">$ownid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#9876aa;background-color:#232525;\">$init_zone</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#9876aa;background-color:#232525;\">$pid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#9876aa;background-color:#232525;\">$zone</span><span style=\"background-color:#232525;\">)<br></span><span style=\"background-color:#232525;\">{<br></span><span style=\"background-color:#232525;\"><br></span><span style=\"background-color:#232525;\">    </span><span style=\"color:#9876aa;background-color:#232525;\">$info </span><span style=\"background-color:#232525;\">= M(</span><span style=\"color:#6a8759;background-color:#232525;\">\'user_zone\'</span><span style=\"background-color:#232525;\">)-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">where</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">array</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#6a8759;background-color:#232525;\">\'pid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$pid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'zone\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$zone</span><span style=\"background-color:#232525;\">))-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">lock</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">true</span><span style=\"background-color:#232525;\">)-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">find</span><span style=\"background-color:#232525;\">()</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">    </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">if</span><span style=\"background-color:#232525;\">(!</span><span style=\"color:#9876aa;background-color:#232525;\">$info</span><span style=\"background-color:#232525;\">){<br></span><span style=\"background-color:#232525;\">        </span><span style=\"color:#9876aa;background-color:#232525;\">$res </span><span style=\"background-color:#232525;\">= M(</span><span style=\"color:#6a8759;background-color:#232525;\">\'user_zone\'</span><span style=\"background-color:#232525;\">)-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">add</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">array</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#6a8759;background-color:#232525;\">\'userid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$userid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'ownid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$ownid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'pid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$pid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'zone\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$zone</span><span style=\"background-color:#232525;\">))</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">        </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">if</span><span style=\"background-color:#232525;\">(!</span><span style=\"color:#9876aa;background-color:#232525;\">$res</span><span style=\"background-color:#232525;\">){<br></span><span style=\"background-color:#232525;\">            </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">return false</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">        </span><span style=\"background-color:#232525;\">}<br></span><span style=\"background-color:#232525;\">        </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">return true</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\"><br></span><span style=\"color:#cc7832;background-color:#232525;\">    </span><span style=\"background-color:#232525;\">}</span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">else</span><span style=\"background-color:#232525;\">{<br></span><span style=\"background-color:#232525;\"><br></span><span style=\"background-color:#232525;\">        </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">if</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#9876aa;background-color:#232525;\">$init_zone </span><span style=\"background-color:#232525;\">== </span><span style=\"color:#6897bb;background-color:#232525;\">2</span><span style=\"background-color:#232525;\">){<br></span><span style=\"background-color:#232525;\">            </span><span style=\"color:#9876aa;background-color:#232525;\">$init_zone </span><span style=\"background-color:#232525;\">=</span><span style=\"color:#6897bb;background-color:#232525;\">1 </span><span style=\"color:#cc7832;background-color:#232525;\">; </span><span style=\"color:#808080;background-color:#232525;\">//保证只找最初的一次<br></span><span style=\"color:#808080;background-color:#232525;\">            //同级的一区是否有人 特殊情况<br></span><span style=\"color:#808080;background-color:#232525;\">            </span><span style=\"color:#9876aa;background-color:#232525;\">$yiqu </span><span style=\"background-color:#232525;\">= M(</span><span style=\"color:#6a8759;background-color:#232525;\">\'user_zone\'</span><span style=\"background-color:#232525;\">)-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">where</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">array</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#6a8759;background-color:#232525;\">\'pid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$pid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'zone\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#6897bb;background-color:#232525;\">1</span><span style=\"background-color:#232525;\">))-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">lock</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">true</span><span style=\"background-color:#232525;\">)-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">find</span><span style=\"background-color:#232525;\">()</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">            </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">if</span><span style=\"background-color:#232525;\">(!</span><span style=\"color:#9876aa;background-color:#232525;\">$yiqu</span><span style=\"background-color:#232525;\">){<br></span><span style=\"background-color:#232525;\">                </span><span style=\"color:#9876aa;background-color:#232525;\">$res </span><span style=\"background-color:#232525;\">= M(</span><span style=\"color:#6a8759;background-color:#232525;\">\'user_zone\'</span><span style=\"background-color:#232525;\">)-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">add</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">array</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#6a8759;background-color:#232525;\">\'userid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$userid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'ownid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$ownid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'pid\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#9876aa;background-color:#232525;\">$pid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6a8759;background-color:#232525;\">\'zone\'</span><span style=\"background-color:#232525;\">=&gt;</span><span style=\"color:#6897bb;background-color:#232525;\">1</span><span style=\"background-color:#232525;\">))</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">                </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">if</span><span style=\"background-color:#232525;\">(!</span><span style=\"color:#9876aa;background-color:#232525;\">$res</span><span style=\"background-color:#232525;\">){<br></span><span style=\"background-color:#232525;\">                    </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">return false</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">                </span><span style=\"background-color:#232525;\">}<br></span><span style=\"background-color:#232525;\">                </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">return true</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">            </span><span style=\"background-color:#232525;\">}<br></span><span style=\"background-color:#232525;\">        }<br></span><span style=\"background-color:#232525;\">        </span><span style=\"color:#808080;background-color:#232525;\">//遍历 注意 此处一定要加return 否则是没有返回值的<br></span><span style=\"color:#808080;background-color:#232525;\">       </span><span style=\"color:#cc7832;background-color:#232525;font-weight:bold;\">return </span><span style=\"color:#9876aa;background-color:#232525;\">$this</span><span style=\"background-color:#232525;\">-&gt;</span><span style=\"color:#ffc66d;background-color:#232525;\">add_zone</span><span style=\"background-color:#232525;\">(</span><span style=\"color:#9876aa;background-color:#232525;\">$userid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#9876aa;background-color:#232525;\">$ownid</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#9876aa;background-color:#232525;\">$init_zone</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#9876aa;background-color:#232525;\">$info</span><span style=\"background-color:#232525;\">[</span><span style=\"color:#6a8759;background-color:#232525;\">\'userid\'</span><span style=\"background-color:#232525;\">]</span><span style=\"color:#cc7832;background-color:#232525;\">,</span><span style=\"color:#6897bb;background-color:#232525;\">1</span><span style=\"background-color:#232525;\">)</span><span style=\"color:#cc7832;background-color:#232525;\">;<br></span><span style=\"color:#cc7832;background-color:#232525;\">    </span><span style=\"background-color:#232525;\">}<br></span><span style=\"background-color:#232525;\">}</span></pre><pre style=\"background-color:#2b2b2b;color:#a9b7c6;font-family:\'宋体\';font-size:12.0pt;\"><span style=\"background-color:#232525;\">见注释</span></pre>', '1', '1524122337', '1524122337', '1', '1', '1');

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', 'Symfony', '1');
INSERT INTO `category` VALUES ('2', 'Python', '1');
INSERT INTO `category` VALUES ('3', 'Redis', '1');
INSERT INTO `category` VALUES ('4', 'Php', '1');

-- ----------------------------
-- Table structure for config
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `titile` varchar(100) DEFAULT NULL,
  `descript` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `web_close` char(1) DEFAULT '1',
  `copyright` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('1', null, null, null, '1', null);

-- ----------------------------
-- Table structure for post
-- ----------------------------
DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `article` int(6) DEFAULT NULL,
  `userid` int(6) DEFAULT NULL,
  `toid` int(6) DEFAULT NULL,
  `content` text,
  `status` tinyint(4) DEFAULT '0' COMMENT '是否审核',
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='文章评论';

-- ----------------------------
-- Records of post
-- ----------------------------
INSERT INTO `post` VALUES ('1', '1', '1', null, '11231231234', '1', '1524044459');
INSERT INTO `post` VALUES ('2', '1', '1', null, '11231231234', '1', '1524044478');
INSERT INTO `post` VALUES ('3', '1', '1', null, '11231231234', '1', '1524044496');
INSERT INTO `post` VALUES ('4', '1', '1', null, '11231231234', '1', '1524044555');
INSERT INTO `post` VALUES ('5', '1', '1', null, '11231231234', '1', '1524044556');
INSERT INTO `post` VALUES ('6', '1', '1', null, '11231231234', '1', '1524044557');
INSERT INTO `post` VALUES ('7', '1', '1', null, '11231231234', '1', '1524044558');
INSERT INTO `post` VALUES ('8', '1', '1', null, '11231231234', '1', '1524044559');
INSERT INTO `post` VALUES ('9', '1', '1', null, '11231231234', '1', '1524044559');
INSERT INTO `post` VALUES ('10', '1', '2', null, '是的发送到', '1', '1524044658');
INSERT INTO `post` VALUES ('11', '1', '3', null, '测试测试', '1', '1524046369');

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `status` char(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tag
-- ----------------------------
INSERT INTO `tag` VALUES ('1', 'Symonfy', '1');
INSERT INTO `tag` VALUES ('2', 'Python', '1');
INSERT INTO `tag` VALUES ('3', 'Redis', '1');
INSERT INTO `tag` VALUES ('4', 'Php', '1');
INSERT INTO `tag` VALUES ('5', 'Mysql', '1');
INSERT INTO `tag` VALUES ('6', 'Memcache', '1');
INSERT INTO `tag` VALUES ('7', 'Thinkphp3.2', '1');
INSERT INTO `tag` VALUES ('8', 'Thinkphp5', '1');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `status` char(1) DEFAULT '1' COMMENT '是否可用',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '1', '1@163.com', '1', '1524042723', '1', '1');
INSERT INTO `user` VALUES ('2', '2', '2@163.com', '1', '1524044658', '1', '是的发送到');
INSERT INTO `user` VALUES ('3', 'mash', 'mash@163.com', 'aaa', '1524046369', '1', '测试测试');
