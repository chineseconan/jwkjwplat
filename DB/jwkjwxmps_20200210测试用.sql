/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100119
Source Host           : localhost:3306
Source Database       : jwkjwxmps

Target Server Type    : MYSQL
Target Server Version : 100119
File Encoding         : 65001

Date: 2020-02-10 18:57:55
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
INSERT INTO `oplog` VALUES ('T7830DCBFF43D49CBAC0D131E', '系统管理员(sysadmin)', '0.0.0.0', '1579240239', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T3859D44393DC4FA8BC078C11', '系统管理员(sysadmin)', '0.0.0.0', '1579247230', null, 'sysuser', null, '成功', '新增用户66', '三员操作日志', 'f31c4622d220cb52c8a8f4154c1dece3');
INSERT INTO `oplog` VALUES ('T19E5074451C04CF89567073D', '系统管理员(sysadmin)', '0.0.0.0', '1579412251', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T4FDBAB41B4D649BD9056C250', '1(1)', '0.0.0.0', '1579573333', null, '', null, '成功', '账号(1)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TC52D8AC025B94C1B955A9CE1', '1(1)', '0.0.0.0', '1579573333', null, '', null, '成功', '访问项目评审', '用户访问日志', '56ae71409d060aaef6cff02165da5864');
INSERT INTO `oplog` VALUES ('T498958ED8E8C4BE68D69CE4C', '1(1)', '0.0.0.0', '1579573391', null, '', null, '成功', '访问项目评审', '用户访问日志', '3de5d4e3e1a2c57907b7b6fa960d2474');
INSERT INTO `oplog` VALUES ('TA0CD01FCE7914457B3DC2D6F', '1(1)', '0.0.0.0', '1579573442', null, '', null, '成功', '访问项目评审', '用户访问日志', 'a3453954a2e37924924be9e3abd71132');
INSERT INTO `oplog` VALUES ('TBD04659F6F76494E8975029E', '1(1)', '0.0.0.0', '1579573526', null, '', null, '成功', '访问项目评审', '用户访问日志', '0806dde3dc465adf4e4dd5471a0af19c');
INSERT INTO `oplog` VALUES ('T6BE4AC3C16C7472DA7E89414', '1(1)', '0.0.0.0', '1579573545', null, '', null, '成功', '访问项目评审', '用户访问日志', '60aa53806fe979e336481fbd1900ae1b');
INSERT INTO `oplog` VALUES ('TD218FA97E0E54090AE562B98', '1(1)', '0.0.0.0', '1579573549', null, '', null, '成功', '访问项目评审', '用户访问日志', 'bac78e8de978949ea0eb4b30af292b13');
INSERT INTO `oplog` VALUES ('T746D1D0D80504518AE455E60', '1(1)', '0.0.0.0', '1579573572', null, '', null, '成功', '访问项目评审', '用户访问日志', '3aba76165e906b0739e5109774b6fc31');
INSERT INTO `oplog` VALUES ('TA85A1ACC87154B2CADB367ED', '系统管理员(sysadmin)', '0.0.0.0', '1579573576', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T3B6C46EB470A468C89C09BA6', '2(2)', '0.0.0.0', '1579573586', null, '', null, '成功', '账号(2)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TF53E0415625C4FD1A49D0213', '2(2)', '0.0.0.0', '1579573586', null, '', null, '成功', '访问项目评审', '用户访问日志', '62cb94c624b4498e1976fca60df329a7');
INSERT INTO `oplog` VALUES ('TA88CB889C1064E93BEE1A42C', '66(6)', '0.0.0.0', '1579573771', null, '', null, '成功', '账号(6)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TD77058EF2AAD4229B11B0C66', '66(6)', '0.0.0.0', '1579573772', null, '', null, '成功', '访问项目评审', '用户访问日志', 'cfd560c53e2a3aac4282ad58f3af75e3');
INSERT INTO `oplog` VALUES ('TAE3E12B3F26546E4959F5CD7', '66(6)', '0.0.0.0', '1579589848', null, '', null, '成功', '访问项目评审', '用户访问日志', '60e84a663633ad26e672d08f65d3be54');
INSERT INTO `oplog` VALUES ('T1C219BC8E4E24D639811C830', '系统管理员(sysadmin)', '0.0.0.0', '1579589854', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T735A7D6F540A4612AC1B8D22', '系统管理员(sysadmin)', '0.0.0.0', '1580700854', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TDECBB1CFA6504C4DA866466A', '系统管理员(sysadmin)', '0.0.0.0', '1580806135', null, 'sysuser', null, '成功', '新增用户七', '三员操作日志', '91c452a9c63efa1cdd6bf41b83cf61cf');
INSERT INTO `oplog` VALUES ('T5297E6EFC6084ABA8856CB46', '系统管理员(sysadmin)', '0.0.0.0', '1580807148', null, 'sysuser', null, '成功', '修改用户七', '三员操作日志', '02df0e030888601eee52cefba9bc9f40');
INSERT INTO `oplog` VALUES ('T62C11EAEFCDB4DD88543512D', '系统管理员(sysadmin)', '0.0.0.0', '1580807181', null, 'sysuser', null, '成功', '修改用户七', '三员操作日志', '0a25fd41e0227bd89e5e4d94f0a5ebae');
INSERT INTO `oplog` VALUES ('T7048978A874542B0B73F3075', '系统管理员(sysadmin)', '0.0.0.0', '1580807777', null, 'sysuser', null, '成功', '修改用户七', '三员操作日志', 'c1f03a8a6e16c78a920642b81fa3db98');
INSERT INTO `oplog` VALUES ('TBD7AAB112A4B4A0DA7201828', '系统管理员(sysadmin)', '0.0.0.0', '1580807806', null, 'sysuser', null, '成功', '修改用户七', '三员操作日志', 'd53ed2d34b8994f4e932a5d3885e8a1b');
INSERT INTO `oplog` VALUES ('T4278E5760D1D484C886DD479', '系统管理员(sysadmin)', '0.0.0.0', '1580807862', null, 'sysuser', null, '成功', '新增用户88', '三员操作日志', 'e99b1cb0c4bc76ad9eb92eb2f3577255');
INSERT INTO `oplog` VALUES ('TB2A53733199B449BB0C48167', '系统管理员(sysadmin)', '0.0.0.0', '1580808012', null, '', null, '成功', '导出用户列表', '对象修改日志', '33a621aa17ae529ebb955a3abd5e5858');
INSERT INTO `oplog` VALUES ('TBC7767AD82C14B16987EF8B0', '系统管理员(sysadmin)', '0.0.0.0', '1580808323', null, '', null, '成功', '导出用户列表', '对象修改日志', 'ebfc6de651e2f3712f257cb3bf718540');
INSERT INTO `oplog` VALUES ('T266316F259C946D1ADEB7AD0', '系统管理员(sysadmin)', '0.0.0.0', '1580810357', null, 'sysuser', null, '成功', '删除用户9', '三员操作日志', 'b6b0bb66f0212f86afc34030bedc93ee');
INSERT INTO `oplog` VALUES ('TFAB2C81EF5A24D658728419C', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '5cb3f03c726fb78bf6593465167c5ac1');
INSERT INTO `oplog` VALUES ('TDCC1112226484DD4A9940C19', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'faded45a71975fa001e3fd93ea45b8e7');
INSERT INTO `oplog` VALUES ('T233C4E16145948B6B4A91EBC', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '8fe4612168dcd8802a55bd6c2a7f5af1');
INSERT INTO `oplog` VALUES ('T794D01E76E4E47248417A5E1', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '170743913df1cdfcd80529905efbd427');
INSERT INTO `oplog` VALUES ('T6C8E3D0464B04F159B4A995E', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '41ef70bec821f326c829af5b50d763fb');
INSERT INTO `oplog` VALUES ('T4F97A8906E204A478298F4EF', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'ca65933e609fb6f71151c72d9b589a3a');
INSERT INTO `oplog` VALUES ('T384F4AAC3CDD4C1C93284E8E', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '3f13bcaf1a098ac139e37780f24ca6ba');
INSERT INTO `oplog` VALUES ('T49D9F9944AAA4F068F022628', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '747e2ef4f7bfde22f06b90183e2a8972');
INSERT INTO `oplog` VALUES ('T6C92EE2E18A94A68A52D5777', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'fb3c4cb293ddde5812b27bc172408fea');
INSERT INTO `oplog` VALUES ('TFB3F73574F554A3D8B016A07', '系统管理员(sysadmin)', '0.0.0.0', '1580834969', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '888a158c840323312582e1c67c7dd841');
INSERT INTO `oplog` VALUES ('TCD5DD29650B441E2B0423BAF', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'cf884ee7f7a1c7fa1108b4b4c2923af0');
INSERT INTO `oplog` VALUES ('TD87CD002F3EB41F2A502CD55', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'a1ea0f28603c1aaefe4cc983bce72224');
INSERT INTO `oplog` VALUES ('TC7A40AE6B3024EE7A65C36D5', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'fd33b1f246518a5f631f2481a9423e94');
INSERT INTO `oplog` VALUES ('T1A08D0654A6B45E99624A9B7', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'cbf26f11907c76458966550b04fad98f');
INSERT INTO `oplog` VALUES ('T3B3023D1A2F44BC98909A3F4', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '55ad788ead347203d0497a26bd2b8d0d');
INSERT INTO `oplog` VALUES ('T2BCFAFE589D24D5F82B5D539', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', '27c49cf87013dba7e8cd6c6d57dde816');
INSERT INTO `oplog` VALUES ('T714D9DD3E9FE43578E181ED8', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'a123b3c56497997a837186d0467a6a45');
INSERT INTO `oplog` VALUES ('T24790590732C4A38B6B27E22', '系统管理员(sysadmin)', '0.0.0.0', '1580835071', null, 'sysuser', null, '失败', '新增用户', '三员操作日志', 'fc57cf304c93bc9e92c61339bf8a7d20');
INSERT INTO `oplog` VALUES ('TA5A5B55174DE409ABDDE7F4B', '系统管理员(sysadmin)', '0.0.0.0', '1580835107', null, 'sysuser', null, '失败', '新增用户浏览专家1', '三员操作日志', '75749491b5dd3693de10cd9fb624b2a3');
INSERT INTO `oplog` VALUES ('T91F8775CDF9D476D89A1F569', '系统管理员(sysadmin)', '0.0.0.0', '1580835107', null, 'sysuser', null, '失败', '新增用户浏览专家2', '三员操作日志', '711ca4c5279b131318abefe3fccbf16d');
INSERT INTO `oplog` VALUES ('T6B06BA7D7ABE413590155364', '系统管理员(sysadmin)', '0.0.0.0', '1580835107', null, 'sysuser', null, '失败', '新增用户浏览专家3', '三员操作日志', '39351bb843b626f9b7abb54ae5804590');
INSERT INTO `oplog` VALUES ('TBBB2AF83BB484E23AA7E7B1E', '系统管理员(sysadmin)', '0.0.0.0', '1580835107', null, 'sysuser', null, '失败', '新增用户浏览专家4', '三员操作日志', 'edff57badda5299782c2aa2c0a5dc260');
INSERT INTO `oplog` VALUES ('T4B501BB1E9F04BC480661F68', '系统管理员(sysadmin)', '0.0.0.0', '1580835107', null, 'sysuser', null, '失败', '新增用户浏览专家5', '三员操作日志', '7b72b8176b203a8e8fbe8f93eb701ee4');
INSERT INTO `oplog` VALUES ('TAB44B4D49F914A29836B2998', '浏览专家1(L1)', '0.0.0.0', '1580835127', null, '', null, '成功', '账号(L1)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T6F812A0790384B34ABF16E89', '浏览专家1(L1)', '0.0.0.0', '1580835131', null, '', null, '成功', '账号(l1)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T8897714DF1A5493FB15A8613', '系统管理员(sysadmin)', '0.0.0.0', '1580835136', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TE6BF615160E34B6C9F999199', '系统管理员(sysadmin)', '0.0.0.0', '1580839165', null, 'sysuser', null, '成功', '修改用户5', '三员操作日志', '9d4db79f16f761450d4739143f0424c7');
INSERT INTO `oplog` VALUES ('T9ED802964C994CA486534F93', '系统管理员(sysadmin)', '0.0.0.0', '1580915970', null, '', null, '成功', '访问明细查询', '用户访问日志', '99a1843a678fe666c0a5578354e80eae');
INSERT INTO `oplog` VALUES ('T88EAF781767340CE8AC4F4EC', '系统管理员(sysadmin)', '0.0.0.0', '1580915972', null, '', null, '成功', '访问明细查询', '用户访问日志', 'd5c405a7d196aef09248e0119520466e');
INSERT INTO `oplog` VALUES ('T3070BF3650224009A1F21DC7', '系统管理员(sysadmin)', '0.0.0.0', '1580915975', null, '', null, '成功', '访问明细查询', '用户访问日志', 'c4e65a134397631f1965317d9c42acb4');
INSERT INTO `oplog` VALUES ('T863244B807194E048E4E7665', '系统管理员(sysadmin)', '0.0.0.0', '1580916004', null, '', null, '成功', '访问明细查询', '用户访问日志', '9a4f36391217e910ac9fcd173ac92b4f');
INSERT INTO `oplog` VALUES ('T506F04D6245F490B885F4018', '系统管理员(sysadmin)', '0.0.0.0', '1580916071', null, '', null, '成功', '访问明细查询', '用户访问日志', '5aa3a4568f1f9f9e74056763d00b822d');
INSERT INTO `oplog` VALUES ('TDC6CD41BC7C54912A483C48D', '系统管理员(sysadmin)', '0.0.0.0', '1580916073', null, '', null, '成功', '访问明细查询', '用户访问日志', '0acf8be2262b3faa53a9ef3df66b6cc9');
INSERT INTO `oplog` VALUES ('T44680300FB334DE8A6E4AE32', '系统管理员(sysadmin)', '0.0.0.0', '1580916219', null, '', null, '成功', '访问明细查询', '用户访问日志', 'ec7bcdc00986aecfd28671d65bc4fdba');
INSERT INTO `oplog` VALUES ('T0850276A081E4908B4C56252', '系统管理员(sysadmin)', '0.0.0.0', '1580916912', null, '', null, '成功', '访问明细查询', '用户访问日志', '06d21c1fc62d67078b75a9d94927fc7b');
INSERT INTO `oplog` VALUES ('T623D7ABFA25E45EF874CF668', '系统管理员(sysadmin)', '0.0.0.0', '1580916972', null, '', null, '成功', '访问明细查询', '用户访问日志', 'ab55dca69da274ad12df8a96a7f8fafc');
INSERT INTO `oplog` VALUES ('TA5FC1B9E0A9F4EC28280A430', '系统管理员(sysadmin)', '0.0.0.0', '1580979064', null, '', null, '成功', '访问明细查询', '用户访问日志', 'b861f750ef945616b4fb08380fb0bfb8');
INSERT INTO `oplog` VALUES ('T68E7AE74F0794A9581F533DE', '系统管理员(sysadmin)', '0.0.0.0', '1580979082', null, '', null, '成功', '访问明细查询', '用户访问日志', 'fd26f7dc8d457297670d69debef1ae36');
INSERT INTO `oplog` VALUES ('TAAAA4AC239E4453EA0FB1401', '系统管理员(sysadmin)', '0.0.0.0', '1580979114', null, '', null, '成功', '访问明细查询', '用户访问日志', '8fe5d81f004da68b35b89426c214f536');
INSERT INTO `oplog` VALUES ('TF1F09F9FFB2F4A49B1CC501F', '系统管理员(sysadmin)', '0.0.0.0', '1580979116', null, '', null, '成功', '访问明细查询', '用户访问日志', '2a3014fd67abb5034cd7828bd6d533d7');
INSERT INTO `oplog` VALUES ('T12302607FBD9445AA489489D', '系统管理员(sysadmin)', '0.0.0.0', '1580979126', null, '', null, '成功', '访问明细查询', '用户访问日志', '06cd4eb77a3f3b66018ef194d7e5ea6f');
INSERT INTO `oplog` VALUES ('TD0CD74BC804340CE873382C1', '系统管理员(sysadmin)', '0.0.0.0', '1580979147', null, '', null, '成功', '访问明细查询', '用户访问日志', '32d68be4935e5c271c598e7e89cb78ca');
INSERT INTO `oplog` VALUES ('T41DA054A48BC49AE8BE70578', '系统管理员(sysadmin)', '0.0.0.0', '1580979148', null, '', null, '成功', '访问明细查询', '用户访问日志', '6c08ecd92082d2afb077e86ec143dc27');
INSERT INTO `oplog` VALUES ('T17F42963CEA5442795EB4502', '系统管理员(sysadmin)', '0.0.0.0', '1580979154', null, '', null, '成功', '访问明细查询', '用户访问日志', '7a33b0efdb1bd989447d86535a753929');
INSERT INTO `oplog` VALUES ('T4E283B3AA8C14BE485BAD2B9', '系统管理员(sysadmin)', '0.0.0.0', '1580979183', null, '', null, '成功', '访问明细查询', '用户访问日志', 'cd0c011980a0ef35ae59bc4ab170e06d');
INSERT INTO `oplog` VALUES ('TBCC65FD1059145C9A69A88B3', '()', '0.0.0.0', '1580984566', null, '', null, '失败', '账号(secadmin)登录,密码错误', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T13326AC6E28847EDBC55418F', '()', '0.0.0.0', '1580984569', null, '', null, '失败', '账号(secadmin)登录,密码错误', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T233FB8F729E14246AB3C83D2', '安全管理员(secadmin)', '0.0.0.0', '1580984573', null, '', null, '成功', '账号(secadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T184EB0C5BDBE4B96852BCD45', '安全管理员(secadmin)', '0.0.0.0', '1580984573', null, '', null, '成功', '访问字典类型管理页面', '用户访问日志', '0e52a68110188658f41badbdd92abfd8');
INSERT INTO `oplog` VALUES ('T833D5164290644388BA7E986', '安全管理员(secadmin)', '0.0.0.0', '1580984575', null, '', null, '成功', '访问模块授权管理', '用户访问日志', '9438524d2ecc8416cd56c7157285e466');
INSERT INTO `oplog` VALUES ('T5A72087EF4434F4CA9B8EBE5', '安全管理员(secadmin)', '0.0.0.0', '1580984583', null, '', null, '成功', '访问用户授权管理', '用户访问日志', '31877c3877f1f2efa28a427716e3a740');
INSERT INTO `oplog` VALUES ('TD5CA24CEC2E14D069D5AD91F', '安全管理员(secadmin)', '0.0.0.0', '1580984592', null, '', null, '成功', '访问安全管理', '用户访问日志', 'd6f1b0855890540d3f34c2889be5a87d');
INSERT INTO `oplog` VALUES ('TC5EC006170A14718B3D3A6F9', '安全管理员(secadmin)', '0.0.0.0', '1580984605', null, '', null, '成功', '访问字典类型管理页面', '用户访问日志', 'f7b84da152c2b0d6f5979da5eb6b2ca8');
INSERT INTO `oplog` VALUES ('T5CA68E18C5E14067A6775CB5', '安全管理员(secadmin)', '0.0.0.0', '1580984606', null, '', null, '成功', '访问模块授权管理', '用户访问日志', '74f4151834cc256858f65577547299a5');
INSERT INTO `oplog` VALUES ('T578E14224C644D8D90C859A5', '安全管理员(secadmin)', '0.0.0.0', '1580984626', null, 'roleauth', null, '成功', '删除角色()的进度查询模块权限', '三员操作日志', '2e317e45fe7d154ee47e7bd08845f80f');
INSERT INTO `oplog` VALUES ('TE4DAEE4B77864EB88BB9E625', '安全管理员(secadmin)', '0.0.0.0', '1580984626', null, 'roleauth', null, '成功', '给角色(系统管理员)赋予进度查询的模块权限', '三员操作日志', '48bdb0a579756a1313631b441a071f33');
INSERT INTO `oplog` VALUES ('T65D340FD2E524AE0B7F9CD86', '系统管理员(sysadmin)', '0.0.0.0', '1580984633', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T8B68011ACB614FA397C261AE', '系统管理员(sysadmin)', '0.0.0.0', '1581063696', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TD43288138F53493389254CFD', '系统管理员(sysadmin)', '0.0.0.0', '1581063884', null, '', null, '成功', '访问评分统计', '用户访问日志', '1d66ab379d4963fdb630f1c051ce56e7');
INSERT INTO `oplog` VALUES ('T73D47AA8A1994950916FF1BA', '系统管理员(sysadmin)', '0.0.0.0', '1581063907', null, '', null, '成功', '访问评分统计', '用户访问日志', 'caaa8be9049a86f9f96198ed5ecb95b0');
INSERT INTO `oplog` VALUES ('T2D35FBB04E374A189C05F1EA', '系统管理员(sysadmin)', '0.0.0.0', '1581063942', null, '', null, '成功', '访问评分统计', '用户访问日志', 'b6cee5bcc9a82058ca122c5be89e8ade');
INSERT INTO `oplog` VALUES ('T5E3C6A2054D34548952EBE30', '系统管理员(sysadmin)', '0.0.0.0', '1581064468', null, '', null, '成功', '访问评分统计', '用户访问日志', 'f37ee47e6d0ff666cfde9e0de86fd734');
INSERT INTO `oplog` VALUES ('T571493D117024AEC8B670936', '系统管理员(sysadmin)', '0.0.0.0', '1581064480', null, '', null, '成功', '访问评分统计', '用户访问日志', '2bd02e1cb3a06885f763fbfe6d7dc7bf');
INSERT INTO `oplog` VALUES ('T7105DDBBE0D54C448A1EB22B', '系统管理员(sysadmin)', '0.0.0.0', '1581064742', null, '', null, '成功', '访问评分统计', '用户访问日志', 'd0137d2296a0a129cf600881ab93b0e6');
INSERT INTO `oplog` VALUES ('TA2712185383445F8B4814DA3', '系统管理员(sysadmin)', '0.0.0.0', '1581064840', null, '', null, '成功', '访问评分统计', '用户访问日志', '1a62fd824b3dd525b69e23f57c401cd9');
INSERT INTO `oplog` VALUES ('T292E81BB93DE422185030F37', '系统管理员(sysadmin)', '0.0.0.0', '1581064999', null, '', null, '成功', '访问评分统计', '用户访问日志', 'a583544f8674ddda4dc46f9aee0edb77');
INSERT INTO `oplog` VALUES ('T8D5DD18E0A104DD3B89FF12C', '系统管理员(sysadmin)', '0.0.0.0', '1581065064', null, '', null, '成功', '访问评分统计', '用户访问日志', '59a872e0771388400a0ff5d92ebd7888');
INSERT INTO `oplog` VALUES ('TFFF6CAAD624E4F019ED6395E', '系统管理员(sysadmin)', '0.0.0.0', '1581065071', null, '', null, '成功', '访问评分统计', '用户访问日志', '76f697b3e6566e51885e7feee939110a');
INSERT INTO `oplog` VALUES ('TFB1AF583816947DA9F604461', '系统管理员(sysadmin)', '0.0.0.0', '1581065082', null, '', null, '成功', '访问评分统计', '用户访问日志', '0343b0a1c83066d65c741ffb96b94119');
INSERT INTO `oplog` VALUES ('T70ADBC75CDF444D6B01F41BB', '系统管理员(sysadmin)', '0.0.0.0', '1581065099', null, '', null, '成功', '访问评分统计', '用户访问日志', '57f69d666fdd700446e21820e2dffe55');
INSERT INTO `oplog` VALUES ('T7547E27F6C9245D3ABDCFFCD', '系统管理员(sysadmin)', '0.0.0.0', '1581065116', null, '', null, '成功', '访问评分统计', '用户访问日志', '5f78c8202c03433d06e94dec30bd216b');
INSERT INTO `oplog` VALUES ('TE8AFBEC96A97435D892E34C5', '1(1)', '0.0.0.0', '1581147369', null, '', null, '成功', '账号(1)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TFD4AF75F35564C59BF9F8D47', '1(1)', '0.0.0.0', '1581147369', null, '', null, '成功', '访问项目评审', '用户访问日志', '23ed30a12df6916d4d85a3bfa87ec7d1');
INSERT INTO `oplog` VALUES ('TDD6DBC81B02B435583ADE01C', '1(1)', '0.0.0.0', '1581147407', null, '', null, '成功', '访问项目评审', '用户访问日志', '5550fb96c68154d254791c65e50d68d4');
INSERT INTO `oplog` VALUES ('TF138FC1DDC4E4B2597F8BED7', '1(1)', '0.0.0.0', '1581147514', null, '', null, '成功', '访问项目评审', '用户访问日志', 'bd163f3963d27f4e535132e89d9ef0f4');
INSERT INTO `oplog` VALUES ('T689ACE39B0E641979D594CD7', '1(1)', '0.0.0.0', '1581147520', null, '', null, '成功', '访问项目评审', '用户访问日志', 'b221bad68938cba8c5597acc2707ae16');
INSERT INTO `oplog` VALUES ('T7FCB7085E2DC4995880266F8', '1(1)', '0.0.0.0', '1581147526', null, '', null, '成功', '访问项目评审', '用户访问日志', 'acc48769448459082283388da9aeb2e9');
INSERT INTO `oplog` VALUES ('T74FD1D65DA5D4B82960754D8', '1(1)', '0.0.0.0', '1581147553', null, '', null, '成功', '访问项目评审', '用户访问日志', '848c32624ab620d1c4c0aac76d1ae4cc');
INSERT INTO `oplog` VALUES ('T2F581C7C24A14EC18BD3AB3A', '1(1)', '0.0.0.0', '1581147580', null, '', null, '成功', '访问项目评审', '用户访问日志', 'e834f442848e13ae9ea9f765c9edf624');
INSERT INTO `oplog` VALUES ('T6EA97E431B79454DA01A34AF', '1(1)', '0.0.0.0', '1581151701', null, '', null, '成功', '访问项目评审', '用户访问日志', '8b01092e7966c8a19acf43b127857147');
INSERT INTO `oplog` VALUES ('TE3EE7BFF4C8749F39DC189EB', '1(1)', '0.0.0.0', '1581151715', null, '', null, '成功', '访问项目评审', '用户访问日志', '08da840f374d67e3796a5b65b1f55f78');
INSERT INTO `oplog` VALUES ('T166A1F815541496F9B035B11', '1(1)', '0.0.0.0', '1581154323', null, '', null, '成功', '访问项目评审', '用户访问日志', '6538145c38e15e12bca9409d9e5d9ddf');
INSERT INTO `oplog` VALUES ('T1EBA5E89F43A472E993CC9C6', '1(1)', '0.0.0.0', '1581154333', null, '', null, '成功', '访问项目评审', '用户访问日志', '7206e6fb946d848d7269f82b2655c31f');
INSERT INTO `oplog` VALUES ('TBC51D64A7F264C4694808DD5', '1(1)', '0.0.0.0', '1581154336', null, '', null, '成功', '访问项目评审', '用户访问日志', '2e308fbdffbf47e703d5e966664d6ce8');
INSERT INTO `oplog` VALUES ('TC64CDDEE9EAC47B7ABD5F6EE', '66(6)', '0.0.0.0', '1581154535', null, '', null, '成功', '账号(6)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TA80AD3C9FF0147DD8A0FBF34', '66(6)', '0.0.0.0', '1581154535', null, '', null, '成功', '访问项目评审', '用户访问日志', '4099abce815ab013da90413694c2c705');
INSERT INTO `oplog` VALUES ('T66153436CD2D4AF0BD166D42', '66(6)', '0.0.0.0', '1581154598', null, '', null, '成功', '访问项目评审', '用户访问日志', 'ddd85cc035aa4a6e47661358fef1a9d6');
INSERT INTO `oplog` VALUES ('TF6EE389C535A45BBA1069CB6', '88(8)', '0.0.0.0', '1581154617', null, '', null, '成功', '账号(8)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T5E71C1E1DEBA4D279AC95040', '88(8)', '0.0.0.0', '1581154617', null, '', null, '成功', '访问项目评审', '用户访问日志', '4c1bc365342f41e340e7e18ad853727d');
INSERT INTO `oplog` VALUES ('T23CCBCA606CC47B5AD851FBF', '88(8)', '0.0.0.0', '1581154717', null, '', null, '成功', '访问项目评审', '用户访问日志', '97b20e3965c5be1fcf972db9ae91a5b1');
INSERT INTO `oplog` VALUES ('T963B8280E8144408B75B1D5C', '88(8)', '0.0.0.0', '1581156459', null, '', null, '成功', '访问项目评审', '用户访问日志', '7c261493f56b14b557246f90fffcc470');
INSERT INTO `oplog` VALUES ('T874EA6C2F91C47C9A37893A2', '88(8)', '0.0.0.0', '1581156460', null, '', null, '成功', '访问项目评审', '用户访问日志', 'daefa755232cd258c39c22f4dc62b234');
INSERT INTO `oplog` VALUES ('TAA0CCD2C0AEF4B1C9B47C06A', '系统管理员(sysadmin)', '0.0.0.0', '1581159908', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('T44FBA34B25F948E0900108F9', '1(1)', '0.0.0.0', '1581331562', null, '', null, '成功', '账号(1)登录', '用户登录日志', null);
INSERT INTO `oplog` VALUES ('TFB4B150EFBB14045AE7A77C9', '1(1)', '0.0.0.0', '1581331562', null, '', null, '成功', '访问项目评审', '用户访问日志', '709611eb0b80a54714b0522156a91d57');
INSERT INTO `oplog` VALUES ('T8B0A15F0EF9D4C80AD71BC53', '1(1)', '0.0.0.0', '1581331564', null, '', null, '成功', '访问项目评审', '用户访问日志', '19dd8b19904a6ea15fc2d365b912612e');
INSERT INTO `oplog` VALUES ('T29B9099F65D449C8B150F260', '1(1)', '0.0.0.0', '1581331925', null, '', null, '成功', '访问项目评审', '用户访问日志', '60144aaceab983468a9b3cb86bcd007b');
INSERT INTO `oplog` VALUES ('T56125B588CC54ECD9CF07E32', '1(1)', '0.0.0.0', '1581332018', null, '', null, '成功', '导出', '明细查询', 'ae46ac2ac5860c3426c2d8889bfad68d');
INSERT INTO `oplog` VALUES ('T7CA89865EE4F4254A301D964', '系统管理员(sysadmin)', '0.0.0.0', '1581332232', null, '', null, '成功', '账号(sysadmin)登录', '用户登录日志', null);

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
INSERT INTO `roleauth` VALUES ('T34FA869FC9C942BCB1DEAEE1', 'TCAAB0FF33F1D45348204EB46', 'TE7FC3A0E1FA54D4DAE429497', '1528355698', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T47C3F8EBFE1F4CB6B33D6C15', '3', 'T73ED91364FC84B81979F3FC0', '1528356339', 'safe', null, null);
INSERT INTO `roleauth` VALUES ('T749A2BB61F5F4552A0161B72', '', 'TB1F58C240D2A4C47AD58E377', '1573375907', 'safe', null, null);
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
INSERT INTO `sysuser` VALUES ('system', '系统管理员', 'sysadmin', '1', '1', '绝密', null, '启用', '是', null, '0', '1559206875', null, null, '1528354890', 'safe', '1528348089', '0', null, null, null, null, '67bdah77jcdeoac07jcn76s370', '::1');
INSERT INTO `sysuser` VALUES ('audit', '审计管理员', 'auditadmin', 'Guanli2018', '2', '绝密', null, '启用', '是', '07b3c32099f3f28b0a9c92ae6dd035e3', '0', '1526548169', null, null, '1526548202', 'audit', '1525913729', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('safe', '安全管理员', 'secadmin', 'Guanli2018', '3', '绝密', null, '启用', '是', '61f88b780aa1682ed6d1545d69c9d0b9', '0', '1580984569', null, null, '1528110584', 'safe', null, '0', null, null, null, null, '', '::1');
INSERT INTO `sysuser` VALUES ('system1', '系统管理员', 'sysadmin1', '1', '1', null, null, '启用', '是', null, '0', '1559206875', null, null, '1528354890', 'safe', '0', '0', null, null, null, null, '', '::1');
INSERT INTO `sysuser` VALUES ('T44383E50863249778EFDBC55', '1', '1', '1', 'TCAAB0FF33F1D45348204EB46', null, '总体部', '启用', '否', null, '0', null, '1579247170', 'system', null, null, '0', '0', '航天', '院士', null, '88888888', '', '::1');
INSERT INTO `sysuser` VALUES ('TB2D5BF0DB9B4478E98BC725C', '2', '2', '1', 'TCAAB0FF33F1D45348204EB46', null, '总体部', '启用', '否', null, '0', null, '1579247170', 'system', null, null, '0', '0', '航天', '院士', null, '88888888', '', '::1');
INSERT INTO `sysuser` VALUES ('T84543E0F14E7448A9DAA47FF', '3', '3', '1', 'TCAAB0FF33F1D45348204EB46', null, '总体部', '启用', '否', null, null, null, '1579247170', 'system', null, null, '0', '0', '航天', '院士', null, '88888888', null, null);
INSERT INTO `sysuser` VALUES ('TA46454FD84F74D80A44E3137', '4', '4', '1', 'TCAAB0FF33F1D45348204EB46', null, '总体部', '启用', '否', null, null, null, '1579247170', 'system', null, null, '0', '0', '航天', '院士', null, '88888888', null, null);
INSERT INTO `sysuser` VALUES ('TCD226B867F9948E5B072BA51', '5', '5', '1', 'TCAAB0FF33F1D45348204EB46', null, '总体部', '启用', '否', 'a9a115b569af86833fc9fecd690bd6c5', null, null, '1579247170', 'system', '1580839165', 'system', '0', '0', '航天', '院士', null, '88888888', null, null);
INSERT INTO `sysuser` VALUES ('T7AA4B14B29F849B7BBDC69AB', '66', '6', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位7', '启用', '否', null, '0', null, '1579247230', 'system', null, null, '0', '0', '航天1', '6666', '', '66666', '', '::1');
INSERT INTO `sysuser` VALUES ('TD99D44BFF94B47D5A222A5E7', '七', '7', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位7', '启用', '否', 'dcdfcf2ece74b4133a9fe5a9ee9ab9b3', null, null, '1580806135', 'system', '1580807806', 'system', '0', '0', '航天', '777', '', '   11', null, null);
INSERT INTO `sysuser` VALUES ('TF572C79A4B4B4A0AA26CA61A', '88', '8', '1', 'TCAAB0FF33F1D45348204EB46', null, '单位8', '启用', '否', null, '0', null, '1580807862', 'system', null, null, '0', '0', '航天1', '888', null, '888', '', '::1');
INSERT INTO `sysuser` VALUES ('T5AE05A792F254FF8B040E2D3', '浏览专家1', 'L1', '1', 'TC0956A46AAEB448DB04EE0C0', null, null, '启用', '否', null, null, null, '1580835557', 'system', null, null, '0', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('T27C5B51642D34A118C2FEB73', '浏览专家2', 'L2', '1', 'TC0956A46AAEB448DB04EE0C0', null, null, '启用', '否', null, null, null, '1580835557', 'system', null, null, '0', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('T0882AAD882F4465C8BE02A9F', '浏览专家3', 'L3', '1', 'TC0956A46AAEB448DB04EE0C0', null, null, '启用', '否', null, null, null, '1580835557', 'system', null, null, '0', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('T46C306F37E7742FFA721DDC9', '浏览专家4', 'L4', '1', 'TC0956A46AAEB448DB04EE0C0', null, null, '启用', '否', null, null, null, '1580835557', 'system', null, null, '0', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('TEC652BF9B15641E59F3450C5', '浏览专家5', 'L5', '1', 'TC0956A46AAEB448DB04EE0C0', null, null, '启用', '否', null, null, null, '1580835557', 'system', null, null, '0', '0', null, null, null, null, null, null);
INSERT INTO `sysuser` VALUES ('TDC726F51021543A5AC84BFD8', '浏览专家6', 'L6', '1', 'TC0956A46AAEB448DB04EE0C0', null, null, '启用', '否', null, null, null, '1580835557', 'system', null, null, '0', '0', null, null, null, null, null, null);

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
INSERT INTO `userauth` VALUES ('T0C3397815A054630B6E11689', 'TCAAB0FF33F1D45348204EB46', 'TCD226B867F9948E5B072BA51', '1579247170', null, null, null);
INSERT INTO `userauth` VALUES ('T206741C9AE5549C5AA399B22', 'TC0956A46AAEB448DB04EE0C0', 'T46C306F37E7742FFA721DDC9', '1580835557', null, null, null);
INSERT INTO `userauth` VALUES ('T24B64662644B4958B737B40A', 'TC0956A46AAEB448DB04EE0C0', 'T5AE05A792F254FF8B040E2D3', '1580835557', null, null, null);
INSERT INTO `userauth` VALUES ('T2E4297E51AA24A22930784ED', 'TCAAB0FF33F1D45348204EB46', 'T7AA4B14B29F849B7BBDC69AB', '1579247230', null, null, null);
INSERT INTO `userauth` VALUES ('T31C90DC4DC7D4F31AF3C542D', 'TC0956A46AAEB448DB04EE0C0', 'TDC726F51021543A5AC84BFD8', '1580835557', null, null, null);
INSERT INTO `userauth` VALUES ('T597682EB3E7247FF9DEE35A4', 'TCAAB0FF33F1D45348204EB46', 'T44383E50863249778EFDBC55', '1579247170', null, null, null);
INSERT INTO `userauth` VALUES ('T61A03EF040EF49D8AFA0FDF8', 'TCAAB0FF33F1D45348204EB46', 'T84543E0F14E7448A9DAA47FF', '1579247170', null, null, null);
INSERT INTO `userauth` VALUES ('T665CD510A3454E9B8018887D', 'TCAAB0FF33F1D45348204EB46', 'TF572C79A4B4B4A0AA26CA61A', '1580807862', null, null, null);
INSERT INTO `userauth` VALUES ('T94AA772478DC4FF1A370F2D9', 'TC0956A46AAEB448DB04EE0C0', 'TEC652BF9B15641E59F3450C5', '1580835557', null, null, null);
INSERT INTO `userauth` VALUES ('T9B8B7E832B0349A29A08C5F4', 'TCAAB0FF33F1D45348204EB46', 'TD99D44BFF94B47D5A222A5E7', '1580806135', null, null, null);
INSERT INTO `userauth` VALUES ('T9DC77EC7672F4C68AADA878B', 'TC0956A46AAEB448DB04EE0C0', 'T27C5B51642D34A118C2FEB73', '1580835557', null, null, null);
INSERT INTO `userauth` VALUES ('TAB7506FFABB44F119AD2CB58', 'TC0956A46AAEB448DB04EE0C0', 'T0882AAD882F4465C8BE02A9F', '1580835557', null, null, null);
INSERT INTO `userauth` VALUES ('TECD62341A75A4883B01E0D8E', 'TCAAB0FF33F1D45348204EB46', 'TB2D5BF0DB9B4478E98BC725C', '1579247170', null, null, null);
INSERT INTO `userauth` VALUES ('TF39C8B9ADC354CFEB0AD807F', 'TCAAB0FF33F1D45348204EB46', 'TA46454FD84F74D80A44E3137', '1579247170', null, null, null);

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
INSERT INTO `xmps_xm` VALUES ('T0DE64AEF9E964FEAAA9BF4E2', 'XM001', '项目001', '单位001', '申请人001', '航天', '专家推荐', '1', '重大', 'tttt1', '', '', '', '', 'yyy551', '', '0.000', '0', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T1E1E02A517934142B7D37597', 'QR0041', '项目0041', '单位004', '申请人004', '航天1', '专家推荐', '4', '重点', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T2AE8AEC9C367442F9A730FFA', 'QR004', '项目004', '单位004', '申请人004', '航天', '专家推荐', '4', '重点', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T4A8DFDCB09EA45DC90C8FABD', 'QR0021', '项目0021', '单位002', '申请人002', '航天1', '专家推荐', '2', '重大', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('T5A4C426434D840FF83075FE0', 'QR003', '项目003', '单位003', '申请人003', '航天', '专家推荐', '3', '重大', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TA1B6A04C640A4E189A707B45', 'QR002', '项目002', '单位002', '申请人002', '航天', '专家推荐', '2', '重大', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TB71E8EF33311458781568F35', 'QR0031', '项目0031', '单位003', '申请人003', '航天1', '专家推荐', '3', '重大', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TC8586E11D7864B7688E7F1F5', 'QR0051', '项目0051', '单位005', '申请人005', '航天1', '专家推荐', '5', '重点', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TD3C21ECD36064B45AD01B18A', 'QR005', '项目005', '单位005', '申请人005', '航天', '专家推荐', '5', '重点', '55', 'd\'d', 'f\'f\'f\'f', 'd\'d', 'f\'f', 's\'s', '1', '0.000', '1', null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TD3EA7B2F99D04A369A5D2F07', 'QR0011', '项目0011', '单位001', '申请人001', '航天1', '专家推荐', '1', '重大', null, null, null, null, null, null, null, null, null, null, '1', '0', '0');
INSERT INTO `xmps_xm` VALUES ('TDA7EE32B48B9488CB5E357F9', 'xmcode005', '项目00555', '青年', '申请人00155', '航天', '专家推荐333', '6', '重大', '1', 'qq', 'yy', 'ww', 'r', 'ee', '1', '100.000', '10', null, '1', '0', '0');

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
INSERT INTO `xmps_xmrelation` VALUES ('T8CF33A6F96054EC8BF9ABC7A', 'T44383E50863249778EFDBC55', 'T2AE8AEC9C367442F9A730FFA', '完成', '5', '15.00', '4.00', '14.00', '5.00', null, null, null, null, null, '', null, null, '43', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TCEA3CA48FD044354A7244BB8', 'T44383E50863249778EFDBC55', 'T4A8DFDCB09EA45DC90C8FABD', '完成', '8', '9.00', '11.00', '11.00', '12.00', null, null, null, null, null, '', null, null, '51', '55.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TE3A3A591C10A4D89B7F60138', 'T44383E50863249778EFDBC55', 'T5A4C426434D840FF83075FE0', '完成', '14', '13.00', '15.00', '16.00', '6.00', null, null, null, null, null, '', null, null, '64', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TE9976074A9BB4C1D9D714F63', 'T44383E50863249778EFDBC55', 'TA1B6A04C640A4E189A707B45', '完成', '7', '6.00', '5.00', '3.00', '4.00', null, null, null, null, null, '', null, null, '25', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T91F007BF6562459A9C797570', 'T44383E50863249778EFDBC55', 'TB71E8EF33311458781568F35', '完成', '12', '12.00', '13.00', '13.00', '13.00', null, null, null, null, null, '', null, null, '63', '63.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T70CB862DFD9A49B1A818A54C', 'T44383E50863249778EFDBC55', 'TD3C21ECD36064B45AD01B18A', '完成', '5', '15.00', '14.00', '14.00', '15.00', null, null, null, null, null, '', null, null, '63', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TF8E414930B434995B8F01687', 'T44383E50863249778EFDBC55', 'TD3EA7B2F99D04A369A5D2F07', '完成', '17', '18.00', '12.00', '12.00', '1.00', null, null, null, null, null, '', null, null, '60', '60.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T31B05E0558BF4B6E8427B053', 'T44383E50863249778EFDBC55', 'TDA7EE32B48B9488CB5E357F9', '完成', '16', '16.00', '16.00', '16.00', '16.00', null, null, null, null, null, '', null, null, '80', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T89D7063E08B64E2188AF05C6', 'T7AA4B14B29F849B7BBDC69AB', 'T1E1E02A517934142B7D37597', '完成', '9', '9.00', '9.00', '9.00', '9.00', null, null, null, null, null, '', null, null, '45', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T7D05E4E2773C4653AC74D1F2', 'T7AA4B14B29F849B7BBDC69AB', 'T4A8DFDCB09EA45DC90C8FABD', '完成', '11', '11.00', '11.00', '11.00', '11.00', null, null, null, null, null, '', null, null, '55', '55.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T649B0818F35145F2ABF394FC', 'T7AA4B14B29F849B7BBDC69AB', 'TB71E8EF33311458781568F35', '完成', '13', '13.00', '13.00', '3.00', '13.00', null, null, null, null, null, '', null, null, '55', '63.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T68BA04F4005B4E4BB4AFB29E', 'T7AA4B14B29F849B7BBDC69AB', 'TC8586E11D7864B7688E7F1F5', '完成', '8', '8.00', '8.00', '19.00', '8.00', null, null, null, null, null, '', null, null, '51', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TAB683A2644814530B95B3D4D', 'T7AA4B14B29F849B7BBDC69AB', 'TD3EA7B2F99D04A369A5D2F07', '完成', '12', '12.00', '12.00', '12.00', '12.00', null, null, null, null, null, '', null, null, '60', '60.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TD259132985EC49D5B48C61A7', 'T84543E0F14E7448A9DAA47FF', 'T2AE8AEC9C367442F9A730FFA', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T12CB468CA28D4500B85CE54E', 'T84543E0F14E7448A9DAA47FF', 'T5A4C426434D840FF83075FE0', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TBFF4A8B6A44F4A89AB2CA63D', 'T84543E0F14E7448A9DAA47FF', 'TA1B6A04C640A4E189A707B45', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T000AD06956EA48C986A9CA13', 'T84543E0F14E7448A9DAA47FF', 'TD3C21ECD36064B45AD01B18A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TFBB831D5DDCB49AB8DAB9843', 'T84543E0F14E7448A9DAA47FF', 'TDA7EE32B48B9488CB5E357F9', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T94B984B9E14945649F6990B3', 'TA46454FD84F74D80A44E3137', 'T0DE64AEF9E964FEAAA9BF4E2', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T45A8AA17775947AFBD9B3B3F', 'TA46454FD84F74D80A44E3137', 'T2AE8AEC9C367442F9A730FFA', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TE0BA284ED1034E3F9549432A', 'TA46454FD84F74D80A44E3137', 'T5A4C426434D840FF83075FE0', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T3C6E3893CAC94D058B719F1C', 'TA46454FD84F74D80A44E3137', 'TA1B6A04C640A4E189A707B45', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T264EC961F0774C709957EFF8', 'TA46454FD84F74D80A44E3137', 'TD3C21ECD36064B45AD01B18A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TF3471B93B71249808F4D4C6C', 'TA46454FD84F74D80A44E3137', 'TDA7EE32B48B9488CB5E357F9', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T0B9F358DEA7647EDBCE1C182', 'TB2D5BF0DB9B4478E98BC725C', 'T0DE64AEF9E964FEAAA9BF4E2', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T0637C22BAF8D449780483449', 'TB2D5BF0DB9B4478E98BC725C', 'T2AE8AEC9C367442F9A730FFA', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TA33328D2048E417CAD73FA02', 'TB2D5BF0DB9B4478E98BC725C', 'T4A8DFDCB09EA45DC90C8FABD', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, '55.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TB2FE7C1E65F3427FB6CBBB78', 'TB2D5BF0DB9B4478E98BC725C', 'T5A4C426434D840FF83075FE0', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TD2F200B62D2342619F176D06', 'TB2D5BF0DB9B4478E98BC725C', 'TA1B6A04C640A4E189A707B45', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T836603C63F2C46DBA79190E2', 'TB2D5BF0DB9B4478E98BC725C', 'TD3C21ECD36064B45AD01B18A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T30ABD432E91B432CAA4493B1', 'TCD226B867F9948E5B072BA51', 'T2AE8AEC9C367442F9A730FFA', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T21D9731E81234B7987704DD4', 'TCD226B867F9948E5B072BA51', 'T5A4C426434D840FF83075FE0', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T842A934CE95B4535BB895480', 'TCD226B867F9948E5B072BA51', 'TA1B6A04C640A4E189A707B45', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TD6D27242213E4B8DAFF10E56', 'TCD226B867F9948E5B072BA51', 'TD3C21ECD36064B45AD01B18A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T477FC2F0A33D4F12BCED15B4', 'TCD226B867F9948E5B072BA51', 'TDA7EE32B48B9488CB5E357F9', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TCB7EF6F94EE045CF8A621060', 'TD99D44BFF94B47D5A222A5E7', 'T0DE64AEF9E964FEAAA9BF4E2', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TBF986E925DBE4A8B8D205B41', 'TD99D44BFF94B47D5A222A5E7', 'T2AE8AEC9C367442F9A730FFA', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T9986D47AFD724F8D85BEA1BA', 'TD99D44BFF94B47D5A222A5E7', 'T5A4C426434D840FF83075FE0', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T3846A2D7161244DBBF3EE3A5', 'TD99D44BFF94B47D5A222A5E7', 'TA1B6A04C640A4E189A707B45', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TB51AB836844A48E9A59906D6', 'TD99D44BFF94B47D5A222A5E7', 'TD3C21ECD36064B45AD01B18A', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TF5539B54A1F94427B08A28DD', 'TD99D44BFF94B47D5A222A5E7', 'TDA7EE32B48B9488CB5E357F9', '进行中', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('T6D3F612C76E1447FB7AE5509', 'TF572C79A4B4B4A0AA26CA61A', 'T1E1E02A517934142B7D37597', '完成', '13', '13.00', '13.00', '13.00', '13.00', null, null, null, null, null, '', null, null, '65', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TA6FAB72D2B2D4503BA291116', 'TF572C79A4B4B4A0AA26CA61A', 'T4A8DFDCB09EA45DC90C8FABD', '完成', '15', '15.00', '15.00', '15.00', '15.00', null, null, null, null, null, '', null, null, '75', '55.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TCB2180AFEDC94E6882ED38B8', 'TF572C79A4B4B4A0AA26CA61A', 'TB71E8EF33311458781568F35', '完成', '14', '14.00', '14.00', '14.00', '14.00', null, null, null, null, null, '', null, null, '70', '63.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TE2EF175FB7494DABAC305E7E', 'TF572C79A4B4B4A0AA26CA61A', 'TC8586E11D7864B7688E7F1F5', '完成', '16', '16.00', '16.00', '0.00', '16.00', null, null, null, null, null, '', null, null, '64', null, '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
INSERT INTO `xmps_xmrelation` VALUES ('TC43B32F5A8E9425C9279677A', 'TF572C79A4B4B4A0AA26CA61A', 'TD3EA7B2F99D04A369A5D2F07', '完成', '11', '14.00', '14.00', '14.00', '14.00', null, null, null, null, null, '', null, null, '67', '60.000', '0', null, null, null, '未开始', '未开始', '未开始', null, null, null);
