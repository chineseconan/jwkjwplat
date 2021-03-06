<?php
return array(
    "REMARK_OPTION"=>[
		'默认分类'=>[
            'title'=>'国家重点研发计划项目答辩评审表格', // marking打分页面标题
            '评价要点'=>[
                '一、研究内容',
                '二、目标设置及技术路线',
                '三、任务分解和进度安排',
                '四、研发团队及工作基础',
                '五、预期成果与风险分析',
            ],
            'exportTitle'=>'基础加强计划重点基础研究项目专家评审汇总表',//明细查询导出表标题
            '评价内容'=>[
                // field为数据库存储字段；
                // mainpoints为评价要点；
                // content为评价内容；
                // type为打分方式：
                //      1.input     输入框，需设定保存小数点位数 decimalpoint（默认为0，最大为3，如需更大改数据库字段）
                //      2.select    下拉框，需设定下拉列表值 source（格式为数组）
                ['field'=>'ps_item1','mainpoints'=>'评审指标1',
                    'content'=>'1、研究内容是否涵盖指南方向要求的全部考核指标<br/>2、对国内外现状及趋势分析是否准确、全面<br/>3、研究内容的创新性、前瞻性及其实效性',
                    'minVal'=>0,'maxVal'=>30,'type'=>'input','decimalpoint'=>0],
                ['field'=>'ps_item2','mainpoints'=>'评审指标2',
                    'content'=>'1、项目目标是否明确清晰、突出重点<br/>2、主要技术路线、研究方法是否合理可行，具有创新性<br/>3、考核指标是否合理、量化、可考核',
                    'minVal'=>0,'maxVal'=>20,'type'=>'input','decimalpoint'=>0],
                ['field'=>'ps_item3','mainpoints'=>'评审指标3',
                    'content'=>'1、任务（课题）设置的科学性和系统性<br/>2、研发进度计划安排的合理性和可行性',
                    'minVal'=>0,'maxVal'=>20,'type'=>'input','decimalpoint'=>0],
                ['field'=>'ps_item4','mainpoints'=>'评审指标4',
                    'content'=>'1、研发团队整体科研水平及人员分工合理性<br/>2、项目负责人的科研水平及创新能力<br/>3、项目申报单位的组织管理能力和项目组织实施机制<br/>4、现有研究工作基础及条件',
                    'minVal'=>0,'maxVal'=>20,'type'=>'input','decimalpoint'=>0],
                ['field'=>'ps_item5','mainpoints'=>'评审指标5',
                    'content'=>'1、项目预期成果是否明确，社会、经济效益如何<br/>2、项目的技术和实施风险分析是否清晰，对策是否有效',
                    'minVal'=>0,'maxVal'=>10,'type'=>'input','decimalpoint'=>0],
            ],
            '注意事项'=>[
                '1.各专项根据实施方案和年度申报指南明确的有关要求，可对相关评价指标的内涵予以具体阐述。',
                '2.评审专家对于其建议立项的项目，评分应不低于 75 分且高于其不建议立项的项目。'
            ],
            '评审意见'=>'（对建议立项的项目请提出意见和建议，对建议不立项的项目请说明具体原因）',
            '定性评价'=>'1', // 定性评价是否必填
            '拟支持最大项目数'=>2 // 项目数量小于5时，每位专家所投建议立项最大票数
        ]
    ]

);