<?php
return array(
    'DB_TYPE'=>'mysql',// 数据库类型
    'DB_HOST'=>'localhost',// 服务器地址
    'DB_NAME'=>'jwkjwxmpsjmrh',// 数据库名
    'DB_USER'=>'root',// 用户名
    'DB_PWD'=>'',// 密码
    'DB_PORT'=>3306,// 端口
    'DB_PREFIX'=>'',// 数据库表前缀
    'DB_CHARSET'=>'utf8',// 数据库字符集
    'URL_CASE_INSENSITIVE' => false,

    'SESSION_PREFIX' => 'jwkjw_',
    'COOKIE_PREFIX' => 'jwkjw_',
    'COOKIE_DOMAIN' => '',
    'LOAD_EXT_CONFIG'=>[
        // 1.函评，['mark'=>'mark_config_hanping']
        // 2.会评，['mark'=>'mark_config']
        'mark'=>'mark_config_hanping',
        'vote'=>'vote_config'
    ],
    // 3.会评情况下，是否投票(1为投票，0为不需要投票)
    'TOUPIAO'=>'0',

    'COMMONUSERID'=> 'T30E9CC5E140D43A6B0E448B8', //‘普通用户’的角色id
    'PROFESSERID'=> 'TCAAB0FF33F1D45348204EB46', //‘专家’的角色id
    'VIEWUSERID'=> 'TC0956A46AAEB448DB04EE0C0', //‘浏览专家’的角色id

    'PWD_SALT' => 'jwkjw', // 系统登录密码加密的盐
    "xmfilepath"=>"ziyuan",// 项目资料存放的文件夹名称
    "defaultfilename"=>"项目申报书.pdf",// 项目资料置顶的文件名称
    // 系统名称
    'TITLE'=>'国家重点研发计划项目评审系统',
    // 评议要点及打分标准
    'MARKRULE'=>[
        '<b>1.申请人已取得的成绩和对国防科技的贡献（2分）。</b>申请人对国防科技领域核心关键技术突破做出实质性贡献，已取得重要研究成果或者转化应用取得明显军事效益。',
        '<b>2.申请人的发展潜力（3分）。</b>申请人对本领域有深厚研究积累，具有较强的洞察力、创造力和组织协调能力，能够主动发现问题并开展科研攻关，有良好的团队背景与科研平台支撑。',
        '<b>3.研究项目的科学价值和军事价值（4分）。</b>研究项目处于国际前沿且有潜在军事应用前景，或者聚焦国防领域重大现实问题，有望实现重大突破，预期成果对提高战斗力具有高贡献率。',
        '<b>4.研究方案的创新性（1分）。</b>研究方案创新性强、科学合理，具有一定的可行性和完备性。',
        '<b>注：初审成绩仅供参考</b>'
    ],
    // 会评--与战斗力关联程度设置（0，1）
    "isZD"=>"1",
    // 函评--与战斗力关联程度设置（0，1）
    "isZZ" => "1",
    // 函评--评审意见是否必填（0，1）
    "isDetail" => "1",
    // 函评--是否四舍五入（0，1）
    "isRound"  => 0
);
