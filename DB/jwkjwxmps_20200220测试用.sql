/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : jwkjwxmps

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2020-02-20 03:42:34
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
INSERT INTO `modelinfo` VALUES ('TBA684FA78040438C946D2B31', '项目评审', '1559294478', 'system', '1581528706', 'safe', '2', 'Admin/Xmps/markIndex', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TBBEE81C030EA4D529920AAE8', '进度查询', '1528418383', 'safe', null, null, '90', 'Admin/JdQuery/index', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TD2E907966B824F72ACE07E55', '字典管理页面', '1522371879', 'safe', null, null, '99', 'Admin/Dictionary/index', '是', '页面类型', '1', '否');
INSERT INTO `modelinfo` VALUES ('TD6B35D17E00D45A8805FEA79', '模块管理', '1522202360', 'safe', null, null, '73', 'Admin/ModelInfo/index', '是', null, null, '否');
INSERT INTO `modelinfo` VALUES ('TE460FF6627E7427DAA5B88F2', '投票设置', '1581951877', 'system', null, null, '91', 'Admin/VoteSetting/index', null, '功能类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TE7FC3A0E1FA54D4DAE429497', '项目审查', '1528355636', 'safe', null, null, '3', 'Admin/Xmps/index', null, '页面类型', '', '否');
INSERT INTO `modelinfo` VALUES ('TEB53C7085F854EDAAE66166C', '项目投票(重大)', '1581951148', 'T44383E50863249778EFDBC55', '1581952022', 'system', '4', 'Admin/Vote/index?type=da', null, '页面类型', '', '否');
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
INSERT INTO `roleauth` VALUES ('T0B47350128A04EF384107F89', 'TCAAB0FF33F1D45348204EB46', 'TEB53C7085F854EDAAE66166C', '1581951213', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T0D2CAFF72A4E4DA1AE2FFFA6', '', 'TF404DA085875493D92E30184', '1560158180', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T1037A86549524858AEFDF5B0', 'TCAAB0FF33F1D45348204EB46', 'TE7FC3A0E1FA54D4DAE429497', '1581951213', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T1B48D9EADA7C4C7FBA367FED', '3', 'TD6B35D17E00D45A8805FEA79', '1528354759', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T21701A718FDC442186ACC998', '1', 'TB1F58C240D2A4C47AD58E377', '1582039767', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T26FC2B2B77084B92B96F84F8', 'TCAAB0FF33F1D45348204EB46', 'TBA684FA78040438C946D2B31', '1581951213', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T44AC526ABB964CC3A2912A72', '1', 'TE460FF6627E7427DAA5B88F2', '1581952062', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T47C3F8EBFE1F4CB6B33D6C15', '3', 'T73ED91364FC84B81979F3FC0', '1528356339', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T7EAC2BE133084ED193EF50CB', '', 'T8A86398AC5FC4F39944FDBA1', '1528355293', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T93674EE4A47D4CD2BC5931CF', '1', 'TBBEE81C030EA4D529920AAE8', '1580984626', 'safe', null, null);
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
INSERT INTO `sysrole` VALUES ('TC0956A46AAEB448DB04EE0C0', '浏览专家', '1559294159', 'system', null, null, '否');

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
INSERT INTO `sysuser` VALUES ('system', '系统管理员', 'sysadmin', '1', '1', '绝密', null, '启用', '是', null, '0', '1559206875', null, null, '1528354890', 'safe', '1528348089', '0', null, null, null, null, 'cjn4ct9soe9dcdb92a2g9nstp5', '::1');
INSERT INTO `sysuser` VALUES ('audit', '审计管理员', 'auditadmin', 'Guanli2018', '2', '绝密', null, '启用', '是', '07b3c32099f3f28b0a9c92ae6dd035e3', '0', '1526548169', null, null, '1526548202', 'audit', '1525913729', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('safe', '安全管理员', 'secadmin', 'Guanli2018', '3', '绝密', null, '启用', '是', '61f88b780aa1682ed6d1545d69c9d0b9', '0', '1582039707', null, null, '1528110584', 'safe', null, '0', null, null, null, null, '', '::1');
INSERT INTO `sysuser` VALUES ('system1', '系统管理员', 'sysadmin1', '1', '1', null, null, '启用', '是', null, '0', '1559206875', null, null, '1528354890', 'safe', '0', '0', null, null, null, null, '', '::1');
INSERT INTO `sysuser` VALUES ('TE2B39AA2405148A2A0EB37F3', '一', '1', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位01', '启用', '否', null, null, null, '1582141344', 'system', null, null, '0', '0', '一组', '职务1', null, '11111111111', null, null);
INSERT INTO `sysuser` VALUES ('T4977323EE98E499D9B610AAA', '二', '2', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位02', '启用', '否', null, null, null, '1582141344', 'system', null, null, '0', '0', '一组', '职务2', null, '11111111112', null, null);
INSERT INTO `sysuser` VALUES ('T19E842AC9D3D46B0AEA5EE2F', '三', '3', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位03', '启用', '否', null, null, null, '1582141344', 'system', null, null, '0', '0', '一组', '职务3', null, '11111111113', null, null);
INSERT INTO `sysuser` VALUES ('TB451CC5BE3504E3DBDC18BFB', '四', '4', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位04', '启用', '否', null, null, null, '1582141344', 'system', null, null, '0', '0', '一组', '职务4', null, '11111111114', null, null);
INSERT INTO `sysuser` VALUES ('TB55085233528453B8B74C1A5', '五', '5', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位05', '启用', '否', null, null, null, '1582141344', 'system', null, null, '0', '0', '二组', '职务5', null, '11111111115', null, null);
INSERT INTO `sysuser` VALUES ('TB355D634A1344555A7E60C64', '六', '6', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位06', '启用', '否', null, null, null, '1582141344', 'system', null, null, '0', '0', '二组', '职务6', null, '11111111116', null, null);
INSERT INTO `sysuser` VALUES ('T11AB8079B67F4CC99E8AEE17', '七', '7', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位07', '启用', '否', null, null, null, '1582141344', 'system', null, null, '0', '0', '二组', '职务7', null, '11111111117', null, null);

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
INSERT INTO `userauth` VALUES ('T3E36C25F4D364F54994BD311', 'TCAAB0FF33F1D45348204EB46', 'TB55085233528453B8B74C1A5', '1582141344', null, null, null);
INSERT INTO `userauth` VALUES ('T57C48D92E66042F5BA64EB16', 'TCAAB0FF33F1D45348204EB46', 'T3749228EE2C84DC7AC96AC46', '1582138885', null, null, null);
INSERT INTO `userauth` VALUES ('T66F8028168EB4E0CAA92F5C4', 'TCAAB0FF33F1D45348204EB46', 'TE2B39AA2405148A2A0EB37F3', '1582141344', null, null, null);
INSERT INTO `userauth` VALUES ('T6F4B02019F5E48BDA8787F8F', 'TCAAB0FF33F1D45348204EB46', 'T17155F4824914C3B8B2BED60', '1582138885', null, null, null);
INSERT INTO `userauth` VALUES ('T763EC181B1654BB3AB4966E7', 'TCAAB0FF33F1D45348204EB46', 'T19E842AC9D3D46B0AEA5EE2F', '1582141344', null, null, null);
INSERT INTO `userauth` VALUES ('T95CD9F542C5845BF84DA54AB', 'TCAAB0FF33F1D45348204EB46', 'T11AB8079B67F4CC99E8AEE17', '1582141344', null, null, null);
INSERT INTO `userauth` VALUES ('T9BBE5BA172CD4B558EEC6587', 'TCAAB0FF33F1D45348204EB46', 'T4977323EE98E499D9B610AAA', '1582141344', null, null, null);
INSERT INTO `userauth` VALUES ('TA5EAA4FFE6C5437283A6ADFD', 'TCAAB0FF33F1D45348204EB46', 'TD447CCB0194B475288B9D86D', '1582138885', null, null, null);
INSERT INTO `userauth` VALUES ('TB74655AD1CB943C78A113DF3', 'TCAAB0FF33F1D45348204EB46', 'T324036A74EA0408980BFFCD9', '1582138885', null, null, null);
INSERT INTO `userauth` VALUES ('TC20572570391484389A92EDA', 'TCAAB0FF33F1D45348204EB46', 'TB451CC5BE3504E3DBDC18BFB', '1582141344', null, null, null);
INSERT INTO `userauth` VALUES ('TC60C1CA197934C8EA5650285', 'TCAAB0FF33F1D45348204EB46', 'TE9F2B7C043564E158674E330', '1582138885', null, null, null);
INSERT INTO `userauth` VALUES ('TC87765AFC44543ABA48003A3', 'TCAAB0FF33F1D45348204EB46', 'TB355D634A1344555A7E60C64', '1582141344', null, null, null);
INSERT INTO `userauth` VALUES ('TDE1A057CA38D4D75A3E8B133', 'TCAAB0FF33F1D45348204EB46', 'T31CF499684C84DECAAFEBE03', '1582138885', null, null, null);

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
INSERT INTO `xmps_xm` VALUES ('T01ECCE5691C54C6FA31A36BE', 'xm007', '项目007', '单位7', '申请人7', '二组', 'tjfs7', '7', '重大', '技术方向7', null, null, null, null, null, '1', '94.000', '7', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T02ACDE893A554F299947AD2D', 'xm005', '项目005', '单位5', '申请人5', '一组', 'tjfs5', '5', '重点', '技术方向5', null, null, null, null, null, '1', '96.000', '5', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T146316BC76994CB293F8CDFE', 'xm003', '项目003', '单位3', '申请人3', '一组', 'tjfs3', '3', '重大', '技术方向3', null, null, null, null, null, '1', '98.000', '3', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T2B1413C3BF474DDDA3E9501A', 'xm004', '项目004', '单位4', '申请人4', '一组', 'tjfs4', '4', '重点', '技术方向4', null, null, null, null, null, '1', '97.000', '4', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T67E039D99A1F4A08A31A3F50', 'xm006', '项目006', '单位6', '申请人6', '一组', 'tjfs6', '6', '重点', '技术方向6', null, null, null, null, null, '1', '95.000', '6', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T6F3B81BF149F4859819A0549', 'xm001', '项目001', '单位1', '申请人1', '一组', 'tjfs1', '1', '重大', '技术方向1', null, null, null, null, null, '1', '100.000', '1', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T77ADEAEA734141C5AAF41861', 'xm008', '项目008', '单位8', '申请人8', '二组', 'tjfs8', '8', '重大', '技术方向8', null, null, null, null, null, '1', '93.000', '8', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T7C9B1D160ED04E88AAB603EC', 'xm010', '项目010', '单位10', '申请人10', '二组', 'tjfs10', '10', '重点', '技术方向10', null, null, null, null, null, '1', '91.000', '10', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TDA8B44F29F9D4F79BF262E80', 'xm002', '项目002', '单位2', '申请人2', '一组', 'tjfs2', '2', '重大', '技术方向2', null, null, null, null, null, '1', '99.000', '2', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TF9818774A1F14C44BA9BF891', 'xm011', '项目011', '单位11', '申请人11', '二组', 'tjfs11', '11', '重点', '技术方向11', null, null, null, null, null, '1', '90.000', '11', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TFA20576FCC6146259D212A96', 'xm009', '项目009', '单位9', '申请人9', '二组', 'tjfs9', '9', '重大', '技术方向9', null, null, null, null, null, '1', '92.000', '9', null, '1', '0', '0');

-- ----------------------------
-- Table structure for xmps_xmrelation
-- ----------------------------
DROP TABLE IF EXISTS `xmps_xmrelation`;
CREATE TABLE `xmps_xmrelation` (
  `xr_id` varchar(255) DEFAULT NULL,
  `xr_user_id` varchar(50) NOT NULL,
  `xr_xm_id` varchar(50) NOT NULL,
  `xr_status` varchar(255) DEFAULT NULL,
  `ps_item1` decimal(8,3) DEFAULT NULL,
  `ps_item2` decimal(8,3) DEFAULT NULL,
  `ps_item3` decimal(8,3) DEFAULT NULL,
  `ps_item4` decimal(8,3) DEFAULT NULL,
  `ps_item5` decimal(8,3) DEFAULT NULL,
  `ps_item6` decimal(8,3) DEFAULT NULL,
  `ps_item7` decimal(8,3) DEFAULT NULL,
  `ps_item8` decimal(8,3) DEFAULT NULL,
  `ps_item9` decimal(8,3) DEFAULT NULL,
  `ps_item10` decimal(8,3) DEFAULT NULL,
  `ps_zz` varchar(255) DEFAULT NULL COMMENT '资助意见',
  `ps_detail` varchar(500) DEFAULT NULL COMMENT '与战斗力关联程度/专家意见',
  `ps_time` varchar(255) DEFAULT NULL COMMENT '保存时间（时间戳，现在未使用）',
  `ps_total` decimal(8,3) DEFAULT NULL COMMENT '打分总分',
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
INSERT INTO `xmps_xmrelation` VALUES ('TB2EBA958CF3E44BDA7153F96', 'T11AB8079B67F4CC99E8AEE17', 'T01ECCE5691C54C6FA31A36BE', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T8624061A685F450DAA50B8AC', 'T11AB8079B67F4CC99E8AEE17', 'T77ADEAEA734141C5AAF41861', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TCF01C711C4C24F35AE3DDBF8', 'T11AB8079B67F4CC99E8AEE17', 'T7C9B1D160ED04E88AAB603EC', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TD13DB1E9AA7047A2BF30F0DD', 'T11AB8079B67F4CC99E8AEE17', 'TF9818774A1F14C44BA9BF891', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T51BAE9C6517D4D3D97388DB2', 'T11AB8079B67F4CC99E8AEE17', 'TFA20576FCC6146259D212A96', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T6EF28438FBBD4A838650E378', 'T19E842AC9D3D46B0AEA5EE2F', 'T02ACDE893A554F299947AD2D', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T15225DCC31F84CB8A246D76A', 'T19E842AC9D3D46B0AEA5EE2F', 'T146316BC76994CB293F8CDFE', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T9B0522CC2B624453A618B257', 'T19E842AC9D3D46B0AEA5EE2F', 'T2B1413C3BF474DDDA3E9501A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T63B5EDC589B34C1FA8C5CFA4', 'T19E842AC9D3D46B0AEA5EE2F', 'T67E039D99A1F4A08A31A3F50', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T7AB3B15ACAF449129BF7EE1B', 'T19E842AC9D3D46B0AEA5EE2F', 'T6F3B81BF149F4859819A0549', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T5CEA3A09994A4B12A1307198', 'T19E842AC9D3D46B0AEA5EE2F', 'TDA8B44F29F9D4F79BF262E80', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TFFEB8DD7FCE94744A53A56CF', 'T4977323EE98E499D9B610AAA', 'T02ACDE893A554F299947AD2D', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T1A4B578C4BCC49CF8CEE9836', 'T4977323EE98E499D9B610AAA', 'T146316BC76994CB293F8CDFE', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T73D7B67C7F5B4C1EA57DADD0', 'T4977323EE98E499D9B610AAA', 'T2B1413C3BF474DDDA3E9501A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TCE378EED19F44C00A4A9463A', 'T4977323EE98E499D9B610AAA', 'T67E039D99A1F4A08A31A3F50', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T4EF3136AF3C141F891D371BA', 'T4977323EE98E499D9B610AAA', 'T6F3B81BF149F4859819A0549', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TED67010C8BF840019CE7FA3F', 'T4977323EE98E499D9B610AAA', 'TDA8B44F29F9D4F79BF262E80', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T3F6AF1DB035D4C7BB99AE0DF', 'TB355D634A1344555A7E60C64', 'T01ECCE5691C54C6FA31A36BE', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T941306EAB8084781AC065FB8', 'TB355D634A1344555A7E60C64', 'T77ADEAEA734141C5AAF41861', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T9107798B0FB04643924F61E5', 'TB355D634A1344555A7E60C64', 'T7C9B1D160ED04E88AAB603EC', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T48D3D8F5433F490A8773881F', 'TB355D634A1344555A7E60C64', 'TF9818774A1F14C44BA9BF891', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TD4EB14460ED94F4EA38EABD7', 'TB355D634A1344555A7E60C64', 'TFA20576FCC6146259D212A96', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T02039BA4F2834A679622BA0E', 'TB451CC5BE3504E3DBDC18BFB', 'T02ACDE893A554F299947AD2D', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TC1681ADAD94B49F8AFCC746A', 'TB451CC5BE3504E3DBDC18BFB', 'T146316BC76994CB293F8CDFE', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T5B0E80F9C5A94C559BE195EA', 'TB451CC5BE3504E3DBDC18BFB', 'T2B1413C3BF474DDDA3E9501A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T143B2935AC3A4729B351B7C8', 'TB451CC5BE3504E3DBDC18BFB', 'T67E039D99A1F4A08A31A3F50', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T2262BBCD5AF24C729D8F5112', 'TB451CC5BE3504E3DBDC18BFB', 'T6F3B81BF149F4859819A0549', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TB4A519C87C6C49B5B59380DF', 'TB451CC5BE3504E3DBDC18BFB', 'TDA8B44F29F9D4F79BF262E80', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T6B9D7EE234CF4ECEA5BAEDA7', 'TB55085233528453B8B74C1A5', 'T01ECCE5691C54C6FA31A36BE', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T118CEACF417144798043DC3C', 'TB55085233528453B8B74C1A5', 'T77ADEAEA734141C5AAF41861', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T6D56928E29D1426A8E77FC1C', 'TB55085233528453B8B74C1A5', 'T7C9B1D160ED04E88AAB603EC', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T4F8BE3FF685342BA8A6D636F', 'TB55085233528453B8B74C1A5', 'TF9818774A1F14C44BA9BF891', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TC3E2724FDA0D42FB9F8F43DF', 'TB55085233528453B8B74C1A5', 'TFA20576FCC6146259D212A96', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TBB5CA5AB54034F03A53F8C2D', 'TE2B39AA2405148A2A0EB37F3', 'T02ACDE893A554F299947AD2D', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TAB7E0C2C80E44109B72B7E1E', 'TE2B39AA2405148A2A0EB37F3', 'T146316BC76994CB293F8CDFE', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T7F99316843564F85869AF31D', 'TE2B39AA2405148A2A0EB37F3', 'T2B1413C3BF474DDDA3E9501A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T6A0D5D5DC0814638ABF3043C', 'TE2B39AA2405148A2A0EB37F3', 'T67E039D99A1F4A08A31A3F50', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T8B565727BA754F7187050BA7', 'TE2B39AA2405148A2A0EB37F3', 'T6F3B81BF149F4859819A0549', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TBF2EE348271B4CA3AF200DD0', 'TE2B39AA2405148A2A0EB37F3', 'TDA8B44F29F9D4F79BF262E80', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
