<?php
return array(
    "REMARK_OPTION"=>[
		'青托'=>[
            'title'=>'青年人才托举工程项目', // marking打分页面标题
            '评价要点'=>[
                '1.候选对象发展潜力（40分）',
                '2.项目科学价值和军事价值（40分）',
                '3.单位托举实力（20分）'
            ],
            'exportTitle'=>'基础加强计划重点基础研究项目专家评审汇总表',//明细查询导出表标题
            '评价内容'=>[
                // field为数据库存储字段；
                // mainpoints为评价要点；
                // content为评价内容；
                // type为打分方式：
                //      1.input     输入框，需设定保存小数点位数 decimalpoint（默认为0，最大为3，如需更大改数据库字段）
                //      2.select    下拉框，需设定下拉列表值 source（格式为数组）
                ['field'=>'ps_item1','mainpoints'=>'评审指标1','content'=>'主要关注候选对象科研经历、已取得成就及主要贡献、个人三年发展规划等。打分标准：优（34-40分）；良（25-33分）；一般（24分）','minVal'=>24,'maxVal'=>40,'type'=>'input','decimalpoint'=>0],
                ['field'=>'ps_item2','mainpoints'=>'评审指标2','content'=>'主要关注是否具有创新性、科学性、合理性，是否符合国防科技重点发展方向和聚焦军事智能主轴主线，是否具有军事应用前景和与战斗力紧密关联，不过分强调技术路线可行性和技术方案完备性。打分标准：优（34-40分）；良（25-33分）；一般（24分）','minVal'=>24,'maxVal'=>40,'type'=>'input','decimalpoint'=>0],
                ['field'=>'ps_item3','mainpoints'=>'评审指标3','content'=>'主要关注托举团队、支撑平台、培养方案、配套经费和保障条件等。打分标准：优（17-20分）；良（13-16分）；一般（12分）','minVal'=>12,'maxVal'=>20,'type'=>'input','decimalpoint'=>0]
            ],
            '注意事项'=>[
                '1.评审前请签署专家承诺书。',
                '<b>2.请严格把关科研诚信问题。如项目申报人和推荐专家有科研诚信问题，项目申报材料或答辩材料有包装拔高、侵占他人成果等学术不端行为，请及时联系工作人员。</b>',
                '3.如您对项目研究内容不熟悉，或因为其他原因不便进行评审，请及时联系工作人员。',
            ],
            '评审意见'=>'',
            '定性评价'=>'0' // 定性评价是否必填
        ],
		'重点'=>[
            'title'=>'基础加强计划重点基础研究项目评审系统', // 好像没用到
            'exportTitle'=>'基础加强计划重点基础研究项目专家评审汇总表',//明细查询导出表标题
            '评价内容'=>[
                // field为数据库存储字段；
                // mainpoints为评价要点；
                // content为评价内容；
                // type为打分方式：
                //      1.input     输入框，需设定保存小数点位数 decimalpoint（默认为0，最大为3，如需更大改数据库字段）
                //      2.select    下拉框，需设定下拉列表值 source（格式为数组）
                ['field'=>'ps_item1','mainpoints'=>'研究定位相符性','content'=>'主要考察项目是否符合基础加强计划重点项目定位、是否符合GF科技重点发展方向、对基础科学问题总结凝练的准确性等','maxVal'=>3,'type'=>'select','source'=>[0,1,2,3]],
                ['field'=>'ps_item2','mainpoints'=>'项目策划合理性','content'=>'主要考察项目目标设定的科学性，项目课题设置、研究内容安排、研究阶段划分的合理性，经费预算安排的合规、合理性等','maxVal'=>3,'type'=>'input','decimalpoint'=>3],
                ['field'=>'ps_item3','mainpoints'=>'综合效益显著性','content'=>'主要考察项目技术指标的先进性、预期成果JS价值及可检验性等','maxVal'=>2,'type'=>'input','decimalpoint'=>2],
                ['field'=>'ps_item4','mainpoints'=>'团队实力匹配性','content'=>'主要考察研究团队基础、支撑平台、配套保障条件等','maxVal'=>2,'type'=>'input','decimalpoint'=>2]
            ],
            '注意事项'=>[
                '1.评审前请签署专家承诺书；',
                '2.如果您对项目研究内容不熟悉，或因为其他原因不便进行评审，请及时联系工作人员。'
            ],
            '评审意见'=>'（请从已取得的成绩和贡献、拟开展的研究工作、申请人的创新能力及发展潜力三个方面撰写评审意见）',
            '定性评价'=>'0' // 定性评价是否必填
        ],
		'重大'=>[
            'title'=>'基础加强计划重点基础研究项目评审系统',
            'exportTitle'=>'基础加强计划重点基础研究项目专家评审汇总表',//明细查询导出表标题
            '评价要点'=>[
                '1.研究定位相符性（0-3分）',
                '2.项目策划合理性（0-3分）',
                '3.综合效益显著性（0-2分）',
                '4.团队实力匹配性（0-2分）'
            ],
            '评价内容'=>[
                ['field'=>'ps_item1','mainpoints'=>'研究定位相符性','content'=>'主要考察项目是否符合基础加强计划重点项目定位、是否符合GF科技重点发展方向、对基础科学问题总结凝练的准确性等','maxVal'=>3,'type'=>'select','source'=>[0,1,2,3]],
                ['field'=>'ps_item2','mainpoints'=>'项目策划合理性','content'=>'主要考察项目目标设定的科学性，项目课题设置、研究内容安排、研究阶段划分的合理性，经费预算安排的合规、合理性等','maxVal'=>3,'type'=>'select','source'=>[0,1,2,3]],
                ['field'=>'ps_item3','mainpoints'=>'综合效益显著性','content'=>'主要考察项目技术指标的先进性、预期成果JS价值及可检验性等','maxVal'=>2,'type'=>'select','source'=>[0,1,2]],
                ['field'=>'ps_item4','mainpoints'=>'团队实力匹配性','content'=>'主要考察研究团队基础、支撑平台、配套保障条件等','maxVal'=>2,'type'=>'select','source'=>[0,1,2]]
            ],
            '注意事项'=>[
                '1.评审前请签署专家承诺书；',
                '2.如果您对项目研究内容不熟悉，或因为其他原因不便进行评审，请及时联系工作人员。'
            ],
            '评审意见'=>'（请从已取得的成绩和贡献、拟开展的研究工作、申请人的创新能力及发展潜力三个方面撰写评审意见）',
            '定性评价'=>'1' //目前是定性评价
        ]
    ]

);