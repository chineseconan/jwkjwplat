/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : jwkjwxmps

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2020-01-17 09:43:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dic
-- ----------------------------
DROP TABLE IF EXISTS `dic`;
CREATE TABLE `dic` (
  `dic_id` varchar(100) NOT NULL,
  `dic_name` varchar(100) NOT NULL,
  `dic_value` varchar(100) NOT NULL,
  `dic_type` varchar(100) NOT NULL,
  `dic_createtime` varchar(100) DEFAULT NULL,
  `dic_createuser` varchar(100) DEFAULT NULL,
  `dic_lastmodifytime` varchar(100) DEFAULT NULL,
  `dic_lastmodifyuser` varchar(100) DEFAULT NULL,
  `dic_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`dic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dic
-- ----------------------------
INSERT INTO `dic` VALUES ('T1243E0EF74794FBBA0FA9FD9', '2019', '2019', 'TA066C9BBE4A044EAB338C445', '1528437668', 'safe', null, null, null);
INSERT INTO `dic` VALUES ('T718BBE91EABE46038251018F', '机电组', '机电组', 'T939567124D0C41ADBECA780E', '1528436107', 'safe', null, null, null);
INSERT INTO `dic` VALUES ('T71E4A0A5D3BD4E0DAB9E2BB0', '新技术组', '新技术组', 'T939567124D0C41ADBECA780E', '1528436130', 'safe', null, null, null);
INSERT INTO `dic` VALUES ('T88B89ED074074DB18AFEF458', '总体组', '总体组', 'T939567124D0C41ADBECA780E', '1528436090', 'safe', null, null, null);
INSERT INTO `dic` VALUES ('TF43ACD0958EB4443AF3D062A', '2018', '2018', 'TA066C9BBE4A044EAB338C445', '1528437657', 'safe', '1528437661', 'safe', null);

-- ----------------------------
-- Table structure for dic_copy
-- ----------------------------
DROP TABLE IF EXISTS `dic_copy`;
CREATE TABLE `dic_copy` (
  `dic_id` varchar(100) NOT NULL,
  `dic_name` varchar(100) NOT NULL,
  `dic_value` varchar(100) NOT NULL,
  `dic_type` varchar(100) NOT NULL,
  `dic_createtime` varchar(100) DEFAULT NULL,
  `dic_createuser` varchar(100) DEFAULT NULL,
  `dic_lastmodifytime` varchar(100) DEFAULT NULL,
  `dic_lastmodifyuser` varchar(100) DEFAULT NULL,
  `dic_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`dic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dic_copy
-- ----------------------------
INSERT INTO `dic_copy` VALUES ('T1243E0EF74794FBBA0FA9FD9', '2019', '2019', 'TA066C9BBE4A044EAB338C445', '1528437668', 'safe', null, null, null);
INSERT INTO `dic_copy` VALUES ('T718BBE91EABE46038251018F', '机电组', '机电组', 'T939567124D0C41ADBECA780E', '1528436107', 'safe', null, null, null);
INSERT INTO `dic_copy` VALUES ('T71E4A0A5D3BD4E0DAB9E2BB0', '新技术组', '新技术组', 'T939567124D0C41ADBECA780E', '1528436130', 'safe', null, null, null);
INSERT INTO `dic_copy` VALUES ('T88B89ED074074DB18AFEF458', '总体组', '总体组', 'T939567124D0C41ADBECA780E', '1528436090', 'safe', null, null, null);
INSERT INTO `dic_copy` VALUES ('TF43ACD0958EB4443AF3D062A', '2018', '2018', 'TA066C9BBE4A044EAB338C445', '1528437657', 'safe', '1528437661', 'safe', null);

-- ----------------------------
-- Table structure for dic_type
-- ----------------------------
DROP TABLE IF EXISTS `dic_type`;
CREATE TABLE `dic_type` (
  `dic_type_id` varchar(200) NOT NULL,
  `type_name` varchar(200) DEFAULT NULL,
  `dic_type_createtime` varchar(30) DEFAULT NULL,
  `dic_type_createuser` varchar(200) DEFAULT NULL,
  `dic_type_lastmodifytime` varchar(30) DEFAULT NULL,
  `dic_type_lastmodifyuser` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`dic_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dic_type
-- ----------------------------
INSERT INTO `dic_type` VALUES ('T939567124D0C41ADBECA780E', '分组', '1528355323', 'safe', null, null);
INSERT INTO `dic_type` VALUES ('TA066C9BBE4A044EAB338C445', '年度', '1528437644', 'safe', null, null);

-- ----------------------------
-- Table structure for modelinfo
-- ----------------------------
DROP TABLE IF EXISTS `modelinfo`;
CREATE TABLE `modelinfo` (
  `mi_id` varchar(100) NOT NULL,
  `mi_name` varchar(100) DEFAULT NULL,
  `mi_createtime` varchar(100) DEFAULT NULL,
  `mi_createuser` varchar(100) DEFAULT NULL,
  `mi_lastmodifytime` varchar(100) DEFAULT NULL,
  `mi_lastmodifyuser` varchar(100) DEFAULT NULL,
  `mi_sort` int(11) DEFAULT NULL,
  `mi_url` varchar(200) DEFAULT NULL,
  `mi_issystem` varchar(100) DEFAULT NULL,
  `mi_type` varchar(100) DEFAULT NULL,
  `mi_pid` varchar(100) DEFAULT NULL,
  `mi_isdefault` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`mi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of modelinfo
-- ----------------------------
INSERT INTO `modelinfo` VALUES ('T2857459C8ACA4AF18ACEA755', '明细查询', '1528418343', 'safe', null, null, '80', 'Admin/MxQuery/index', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('T3A62E07E49B94413BF9C8E5E', '模块授权', '1522202442', 'safe', null, null, '81', 'Admin/RoleAuth/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('T68588C53EC674BB389EFE3BB', '用户授权', '1522202454', 'safe', null, null, '78', 'Admin/UserAuth/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('T6CBD13D50B8B4DF5946A29CE', '专家管理', '1522202403', 'safe', null, null, '79', 'Admin/User/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('T73ED91364FC84B81979F3FC0', '用户状态管理', '1522202430', 'safe', null, null, '75', 'Admin/UserSafe/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('T8A86398AC5FC4F39944FDBA1', '日志查询', '1522202465', 'safe', null, null, '83', 'Admin/LogManage/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('T8FF84931BD364BB6B0D5FD5C', '字典类型管理页面', '1522372818', 'safe', null, null, '15', 'Admin/DicType/index', '是', '页面类型', '2', '否');
INSERT INTO `modelinfo` VALUES ('T94277F330BAE48E0AE1C9BFA', '角色管理', '1522202373', 'safe', null, null, '76', 'Admin/RoleInfo/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('TB1F58C240D2A4C47AD58E377', '数据交互', '1528967210', 'safe', null, null, '200', 'Admin/DataCollect/index', null, '页面类型', '', null);
INSERT INTO `modelinfo` VALUES ('TBA684FA78040438C946D2B31', '项目评审', '1559294478', 'system', '1572846076', 'T32855831508743FC8A28A3D3', '1', 'Admin/XmpsNew/index', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TBBEE81C030EA4D529920AAE8', '进度查询', '1528418383', 'safe', null, null, '90', 'Admin/JdQuery/index', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TD2E907966B824F72ACE07E55', '字典管理页面', '1522371879', 'safe', null, null, '99', 'Admin/Dictionary/index', '是', '页面类型', '1', '否');
INSERT INTO `modelinfo` VALUES ('TD6B35D17E00D45A8805FEA79', '模块管理', '1522202360', 'safe', null, null, '73', 'Admin/ModelInfo/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('TE7FC3A0E1FA54D4DAE429497', '项目审查', '1528355636', 'safe', null, null, '101', 'Admin/Xmps/index', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TF250F0BCDF5D42F8AAF5802C', '项目管理', '1528355499', 'safe', null, null, '10', 'Admin/XM/index', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TF404DA085875493D92E30184', '计划重点项目', '1559290345', 'system', '1559291178', 'system', '201', 'Admin/XMNew/manage', null, '页面类型', '', '否');

-- ----------------------------
-- Table structure for oplog
-- ----------------------------
DROP TABLE IF EXISTS `oplog`;
CREATE TABLE `oplog` (
  `opl_id` varchar(100) NOT NULL,
  `opl_user` varchar(100) DEFAULT NULL,
  `opl_ip` varchar(100) DEFAULT NULL,
  `opl_time` varchar(100) DEFAULT NULL,
  `opl_type` varchar(100) DEFAULT NULL,
  `opl_object` varchar(100) DEFAULT NULL,
  `opl_content` varchar(2000) DEFAULT NULL,
  `opl_result` varchar(100) DEFAULT NULL,
  `opl_firstcontent` varchar(2000) DEFAULT NULL,
  `opl_logtype` varchar(100) DEFAULT NULL,
  `opl_logcode` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oplog
-- ----------------------------

-- ----------------------------
-- Table structure for roleauth
-- ----------------------------
DROP TABLE IF EXISTS `roleauth`;
CREATE TABLE `roleauth` (
  `ra_id` varchar(100) NOT NULL,
  `ra_roleid` varchar(100) DEFAULT NULL,
  `ra_miid` varchar(100) DEFAULT NULL,
  `ra_createtime` varchar(100) DEFAULT NULL,
  `ra_createuser` varchar(100) DEFAULT NULL,
  `ra_lastmodifytime` varchar(100) DEFAULT NULL,
  `ra_lastmodifyuser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ra_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of roleauth
-- ----------------------------
INSERT INTO `roleauth` VALUES ('T0D2CAFF72A4E4DA1AE2FFFA6', '', 'TF404DA085875493D92E30184', '1560158180', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T1B48D9EADA7C4C7FBA367FED', '3', 'TD6B35D17E00D45A8805FEA79', '1528354759', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T30263A6D17D54B47B5DC70D7', '', 'TBBEE81C030EA4D529920AAE8', '1573375807', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T34FA869FC9C942BCB1DEAEE1', 'TCAAB0FF33F1D45348204EB46', 'TE7FC3A0E1FA54D4DAE429497', '1528355698', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T47C3F8EBFE1F4CB6B33D6C15', '3', 'T73ED91364FC84B81979F3FC0', '1528356339', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T749A2BB61F5F4552A0161B72', '', 'TB1F58C240D2A4C47AD58E377', '1573375907', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T7EAC2BE133084ED193EF50CB', '', 'T8A86398AC5FC4F39944FDBA1', '1528355293', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T9F441B2ADD31470184863E7C', '3', 'T8FF84931BD364BB6B0D5FD5C', '1528436064', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TA4777F9CB90544E9A2EBF77D', '3', 'T3A62E07E49B94413BF9C8E5E', '1528354752', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TB08B00E0990340E9B7BD32AF', '1', 'T6CBD13D50B8B4DF5946A29CE', '1528356375', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TB3B51452804F405591B09879', '1', 'T2857459C8ACA4AF18ACEA755', '1528418423', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TCC20A141DD7F4C82A68E6F49', '', 'T94277F330BAE48E0AE1C9BFA', '1528355281', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TD3D455415853482DA1C4AB32', '3', 'T68588C53EC674BB389EFE3BB', '1528355827', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TEA545AF7035F43D6BB10214D', '1', 'TF250F0BCDF5D42F8AAF5802C', '1528355691', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TF690FFDF86C14E2486FA7313', '3', 'TD2E907966B824F72ACE07E55', '1528436061', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('TFEC6A9CF20274090B6F99B61', 'TEA7ECCADA3784874A074E57D', 'TBA684FA78040438C946D2B31', '1559555615', 'TC8728CA511A34A07ACE8C7F3', null, null);

-- ----------------------------
-- Table structure for sysconfig
-- ----------------------------
DROP TABLE IF EXISTS `sysconfig`;
CREATE TABLE `sysconfig` (
  `sc_id` varchar(100) NOT NULL,
  `sc_itemname` varchar(200) DEFAULT NULL,
  `sc_itemvalue` varchar(100) DEFAULT NULL,
  `sc_itemcode` varchar(200) DEFAULT NULL,
  `sc_itemtype` varchar(100) DEFAULT NULL,
  `sc_itemclass` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sysconfig
-- ----------------------------
INSERT INTO `sysconfig` VALUES ('1', '密码复杂度检测', '0', 'SEC_PWDCHECK', '0', '保密配置');
INSERT INTO `sysconfig` VALUES ('2', '超时登录（时间）', '600', 'SEC_LOGINTIMEOUTTIME', '1', '保密配置');
INSERT INTO `sysconfig` VALUES ('3', '七天修改密码', '0', 'SEC_CHANGEPWD', '0', '保密配置');
INSERT INTO `sysconfig` VALUES ('4', '超时登录（检测）', '0', 'SEC_LOGINTIMEOUTCHECK', '0', '保密配置');
INSERT INTO `sysconfig` VALUES ('5', '系统LOGO', '总体部职称评审系统', 'SYS_LOGO', null, '系统配置');
INSERT INTO `sysconfig` VALUES ('6', '系统名称', '总体部职称评审系统', 'SYS_NAME', null, '系统配置');
INSERT INTO `sysconfig` VALUES ('7', '背景图', null, 'SYS_BGIMG', null, '系统配置');
INSERT INTO `sysconfig` VALUES ('8', '安全管理员被锁定时长', '1800', 'SEC_LOCKTIME', null, '保密配置');
INSERT INTO `sysconfig` VALUES ('9', '系统是否涉密', '0', 'SEC_ISSECRET', null, '系统配置');

-- ----------------------------
-- Table structure for sysrole
-- ----------------------------
DROP TABLE IF EXISTS `sysrole`;
CREATE TABLE `sysrole` (
  `role_id` varchar(100) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_createtime` varchar(100) DEFAULT NULL,
  `role_createuser` varchar(100) DEFAULT NULL,
  `role_lastmodifytime` varchar(100) DEFAULT NULL,
  `role_lastmodifyuser` varchar(100) DEFAULT NULL,
  `role_isdefault` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sysrole
-- ----------------------------
INSERT INTO `sysrole` VALUES ('TCAAB0FF33F1D45348204EB46', '专家', '1528110552', 'system', '1528349453', 'system', '否');
INSERT INTO `sysrole` VALUES ('2', '审计管理员', null, null, null, null, '否');
INSERT INTO `sysrole` VALUES ('3', '安全管理员', null, null, null, null, '否');
INSERT INTO `sysrole` VALUES ('1', '系统管理员', null, null, null, null, '否');
INSERT INTO `sysrole` VALUES ('TEA7ECCADA3784874A074E57D', '特殊专家', '1559294159', 'system', null, null, '否');

-- ----------------------------
-- Table structure for sysuser
-- ----------------------------
DROP TABLE IF EXISTS `sysuser`;
CREATE TABLE `sysuser` (
  `user_id` varchar(100) NOT NULL,
  `user_realusername` varchar(100) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_role` varchar(100) DEFAULT NULL,
  `user_secretlevel` varchar(100) DEFAULT NULL,
  `user_orgid` varchar(100) DEFAULT NULL,
  `user_enable` varchar(100) DEFAULT NULL,
  `user_issystem` varchar(100) DEFAULT NULL,
  `user_secretlevelcode` varchar(100) DEFAULT NULL,
  `user_passworderrornum` varchar(100) DEFAULT NULL,
  `user_passworderrortime` varchar(100) DEFAULT NULL,
  `user_createtime` varchar(100) DEFAULT NULL,
  `user_createuser` varchar(100) DEFAULT NULL,
  `user_lastmodifytime` varchar(100) DEFAULT NULL,
  `user_lastmodifyuser` varchar(100) DEFAULT NULL,
  `user_frozentime` int(11) DEFAULT '0',
  `user_isdelete` varchar(100) DEFAULT '0',
  `user_class` varchar(100) DEFAULT NULL,
  `user_zhiwu` varchar(100) DEFAULT NULL,
  `user_zhicheng` varchar(100) DEFAULT NULL,
  `user_mobile` varchar(100) DEFAULT NULL,
  `user_sessionid` varchar(100) DEFAULT NULL,
  `user_ip` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sysuser
-- ----------------------------
INSERT INTO `sysuser` VALUES ('system', '系统管理员', 'sysadmin', '1', '1', '绝密', null, '启用', '是', null, '0', '1559206875', null, null, '1528354890', 'safe', '1528348089', '0', null, null, null, null, 'b26p09djfjnsuv37g862s6dmr5', '::1');
INSERT INTO `sysuser` VALUES ('audit', '审计管理员', 'auditadmin', 'Guanli2018', '2', '绝密', null, '启用', '是', '07b3c32099f3f28b0a9c92ae6dd035e3', '0', '1526548169', null, null, '1526548202', 'audit', '1525913729', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('safe', '安全管理员', 'secadmin', 'Guanli2018', '3', '绝密', null, '启用', '是', '61f88b780aa1682ed6d1545d69c9d0b9', '0', '1528626025', null, null, '1528110584', 'safe', null, '0', null, null, null, null, '', '::1');
INSERT INTO `sysuser` VALUES ('system1', '系统管理员', 'sysadmin1', '1', '1', null, null, '启用', '是', null, '0', '1559206875', null, null, '1528354890', 'safe', '0', '0', null, null, null, null, '', '::1');

-- ----------------------------
-- Table structure for userauth
-- ----------------------------
DROP TABLE IF EXISTS `userauth`;
CREATE TABLE `userauth` (
  `ua_id` varchar(100) NOT NULL,
  `ua_roleid` varchar(100) DEFAULT NULL,
  `ua_userid` varchar(100) DEFAULT NULL,
  `ua_createtime` varchar(100) DEFAULT NULL,
  `ua_createuser` varchar(100) DEFAULT NULL,
  `ua_lastmodifytime` varchar(100) DEFAULT NULL,
  `ua_lastmodifyuser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ua_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userauth
-- ----------------------------
INSERT INTO `userauth` VALUES ('safe', '3', 'safe', null, null, null, null);
INSERT INTO `userauth` VALUES ('system', '1', 'system', null, null, null, null);
INSERT INTO `userauth` VALUES ('system1', '1', 'system1', null, null, null, null);

-- ----------------------------
-- Table structure for votesetting
-- ----------------------------
DROP TABLE IF EXISTS `votesetting`;
CREATE TABLE `votesetting` (
  `v_id` varchar(255) NOT NULL,
  `class` varchar(255) DEFAULT NULL COMMENT '分组',
  `round` varchar(255) DEFAULT NULL COMMENT '第几轮',
  `maxnum` int(11) DEFAULT NULL COMMENT '最大投票数',
  `xmtype` varchar(255) DEFAULT NULL COMMENT '类别',
  `xmgroup` varchar(255) DEFAULT NULL COMMENT '小组',
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`v_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of votesetting
-- ----------------------------

-- ----------------------------
-- Table structure for xmps_typeorder
-- ----------------------------
DROP TABLE IF EXISTS `xmps_typeorder`;
CREATE TABLE `xmps_typeorder` (
  `type_order` int(10) DEFAULT NULL COMMENT '类型排序',
  `type_name` varchar(255) DEFAULT NULL COMMENT '类型名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xmps_typeorder
-- ----------------------------

-- ----------------------------
-- Table structure for xmps_xm
-- ----------------------------
DROP TABLE IF EXISTS `xmps_xm`;
CREATE TABLE `xmps_xm` (
  `xm_id` varchar(255) NOT NULL,
  `xm_code` varchar(255) DEFAULT NULL,
  `xm_name` varchar(255) DEFAULT NULL,
  `xm_company` varchar(255) DEFAULT NULL,
  `xm_createuser` varchar(255) DEFAULT NULL,
  `xm_class` varchar(255) DEFAULT NULL,
  `xm_tmfs` varchar(255) DEFAULT '',
  `xm_ordernum` int(10) DEFAULT '0' COMMENT '排序号',
  `xm_type` varchar(255) DEFAULT NULL COMMENT '项目分类',
  `xm_group` varchar(255) DEFAULT NULL COMMENT '技术方向',
  `xm_target` mediumtext COMMENT '研究目标',
  `xm_expect` varchar(255) DEFAULT NULL COMMENT '预期成果',
  `xm_cycle` varchar(255) DEFAULT NULL COMMENT '研究周期',
  `xm_funds` varchar(255) DEFAULT NULL COMMENT '经费概算（万元）',
  `xm_remark` varchar(10240) DEFAULT NULL COMMENT '备注',
  `xm_oldfenzu` varchar(255) DEFAULT NULL COMMENT '原分组',
  `xm_oldscore` decimal(8,3) DEFAULT NULL COMMENT '原得分',
  `xm_oldrank` int(10) DEFAULT NULL COMMENT '原排名',
  `xm_oldcommand` varchar(1024) DEFAULT NULL COMMENT '原资助意见',
  `vote1option` varchar(8) DEFAULT '1' COMMENT '投票阶段可选',
  `vote2option` varchar(8) DEFAULT '0' COMMENT '投票阶段可选',
  `vote3option` varchar(8) DEFAULT '0' COMMENT '投票阶段可选',
  PRIMARY KEY (`xm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xmps_xm
-- ----------------------------

-- ----------------------------
-- Table structure for xmps_xmrelation
-- ----------------------------
DROP TABLE IF EXISTS `xmps_xmrelation`;
CREATE TABLE `xmps_xmrelation` (
  `xr_id` varchar(255) DEFAULT NULL,
  `xr_user_id` varchar(50) NOT NULL,
  `xr_xm_id` varchar(50) NOT NULL,
  `xr_status` varchar(255) DEFAULT NULL,
  `ps_item1` decimal(8,0) DEFAULT NULL,
  `ps_item2` decimal(8,2) DEFAULT NULL,
  `ps_item3` decimal(8,2) DEFAULT NULL,
  `ps_item4` decimal(8,2) DEFAULT NULL,
  `ps_item5` decimal(8,2) DEFAULT NULL,
  `ps_item6` decimal(8,2) DEFAULT NULL,
  `ps_item7` decimal(8,2) DEFAULT NULL,
  `ps_item8` decimal(8,2) DEFAULT NULL,
  `ps_item9` decimal(8,2) DEFAULT NULL,
  `ps_item10` decimal(8,2) DEFAULT NULL,
  `ps_zz` varchar(255) DEFAULT NULL COMMENT '资助意见',
  `ps_detail` varchar(500) DEFAULT NULL COMMENT '与战斗力关联程度/专家意见',
  `ps_time` varchar(255) DEFAULT NULL COMMENT '保存时间（时间戳，现在未使用）',
  `ps_total` decimal(8,0) DEFAULT NULL COMMENT '打分总分',
  `avgvalue` float(8,3) DEFAULT NULL COMMENT '平均得分',
  `ishuibi` varchar(255) DEFAULT '0' COMMENT '是否回避',
  `vote1` varchar(8) DEFAULT NULL COMMENT '第一轮投票结果',
  `vote2` varchar(8) DEFAULT NULL COMMENT '第二轮投票结果',
  `vote3` varchar(8) DEFAULT NULL COMMENT '第三轮投票结果',
  `vote1status` varchar(255) DEFAULT '未开始' COMMENT '投票状态',
  `vote2status` varchar(255) DEFAULT '未开始' COMMENT '投票状态',
  `vote3status` varchar(255) DEFAULT '未开始' COMMENT '投票状态',
  `vote1rate` float(8,3) DEFAULT NULL COMMENT '得票率',
  `vote2rate` float(8,3) DEFAULT NULL COMMENT '得票率',
  `vote3rate` float(8,3) DEFAULT NULL COMMENT '得票率',
  PRIMARY KEY (`xr_user_id`,`xr_xm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xmps_xmrelation
-- ----------------------------
