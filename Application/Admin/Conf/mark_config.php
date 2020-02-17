<?php
return array(
    "REMARK_OPTION"=>[
        '重大'=>[
            'title'=>'国家社科基金军事学重大项目', // date("Y", time()) . "年度".$titleForExport."评审结果统计表" 为进度查询导出表头
            'exportTitle'=>'国家社科基金军事学重大项目专家评审汇总表',//明细查询导出表标题
            '评价要点'=>[
                '1.研究团队取得成绩和对军事理论的贡献（满分30分）',
                '2.研究团队的实力（满分20分）',
                '3.研究项目的科学价值和军事价值（满分30分）',
                '4.研究思路和方案的创新性、可行性（满分20分）'
            ],
            '注意事项'=>[
                '<b>1.项目符合探索面向未来战争的前沿作战理论研究范畴（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】；研究重点与课题需求匹配，国内外现状描述充分准确（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】。</b>',
                '<b>2.研究内容符合相关领域技术发展趋势及要求（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】；研究内容有一定的现实技术支撑（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】。</b>',
                '<b>3.项目具有较强原创性、前沿性、以及引领未来战争演进的研究价值（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】；预期成果应用前景广阔，对于推进作战准备和牵引技术和武器装备发展具有重要意义（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】。</b>',
                '<b>4.人员知识和年龄结构合理，对科技和未来战争需求有一定认识，具备开展项目研究能力（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】；有良好科研平台作为支撑，具备开展作战理论研究和演示验证的基础条件（10分）【优（9-10）；良（7-8）；中（5-6）；差（0-4）】。</b>。',
                '<b>5.研究思路和方案创新性强，科学合理；不过分强调研究路径的可行性和研究方案的完备性（20分）【优（17-20）；良（14-17）；中（10-13）；差（0-9）】。</b>'
            ],
            '评价内容'=>[
                'ps_item1'=>['field'=>'ps_item1','title'=>'研究定位','brief'=>'研究定位','minVal'=>0,'maxVal'=>20,'type'=>'text'],
                'ps_item2'=>['field'=>'ps_item2','title'=>'研究内容<br/>技术支撑','brief'=>'研究内容技术支撑','minVal'=>0,'maxVal'=>20,'type'=>'text'],
                'ps_item3'=>['field'=>'ps_item3','title'=>'研究项目军事价<br/>值和科学价值','brief'=>'研究项目军事价值和科学价值','minVal'=>0,'maxVal'=>20,'type'=>'text'],
                'ps_item5'=>['field'=>'ps_item5','title'=>'研究单位及<br/>人员实力','brief'=>'研究单位及人员实力','minVal'=>0,'maxVal'=>20,'type'=>'text'],
                'ps_item4'=>['field'=>'ps_item4','title'=>'研究思路和方案<br/>创新性，可行性','brief'=>'研究思路和方案创新性，可行性','minVal'=>0,'maxVal'=>20,'type'=>'select','source'=>[0,5,15,20]]
            ]
        ],
        '重点'=>[
            'title'=>'国家社科基金军事学重点项目',
            'exportTitle'=>'国家社科基金军事学重大项目专家评审汇总表',//明细查询导出表标题
            '评价要点'=>[
                '1.申请人取得成绩和对军事理论的贡献（满分30分）',
                '2.申请人的发展潜力（满分30分）',
                '3.研究项目的科学价值和军事价值（满分20分）',
                '4.研究思路和方案的创新性、可行性（满分20分）'
            ],
            '注意事项'=>[    '<b>1.申请人取得成绩和对军事理论的贡献【优（26-30）；良（22-25）；中（18-21）；差（0-17）】。</b>已取得重要研究成果或者转化应用取得明显军事效益；担任过重点项目负责人；同行评价意见较好。',                    '<b>2.申请人的发展潜力【优（26-30）；良（22-25）；中（18-21）；差（0-17）】。</b>对本领域有较强研究积累，知识储备和科研经历丰富；勇于开拓创新，具有较好的洞察力、创造力；有良好团队背景和科研平台支撑；理技融合、战技结合型人才重点支持。',                    '<b>3.研究项目的科学价值和军事价值【优（17-20）；良（14-16）；中（11-13）；差（0-10）】。</b>基础理论类项目，着重评价其原创性、前沿性，以及潜在军事价值；应用理论类项目，着重评价其提升作战能力和军队建设质量效益的重要作用。',                    '<b>4.研究思路和方案的创新性、可行性【优（17-20）；良（14-16）；中（11-13）；差（0-10）】。</b>研究方案创新性强、科学合理。不过分强调研究路径的可行性和研究方案的完备性。'
            ],
            '评价内容'=>[
                'ps_item1'=>['field'=>'ps_item1','title'=>'成绩贡献','brief'=>'成绩贡献','minVal'=>0,'maxVal'=>30,'type'=>'text'],
                'ps_item2'=>['field'=>'ps_item2','title'=>'发展潜力','brief'=>'发展潜力','minVal'=>0,'maxVal'=>30,'type'=>'text'],
                'ps_item3'=>['field'=>'ps_item3','title'=>'科学、军事价值','brief'=>'科学军事价值','minVal'=>0,'maxVal'=>20,'type'=>'text'],
                'ps_item4'=>['field'=>'ps_item4','title'=>'创新性','brief'=>'创新性','minVal'=>0,'maxVal'=>20,'type'=>'text']
            ]
        ],
//        'ps_item1'=>['field'=>'ps_item1','title'=>'成绩贡献','brief'=>'成绩贡献','minVal'=>0,'maxVal'=>2],
//        'ps_item2'=>['field'=>'ps_item2','title'=>'发展潜力','brief'=>'发展潜力','minVal'=>0,'maxVal'=>3],
//        'ps_item3'=>['field'=>'ps_item3','title'=>'科学、军事价值','brief'=>'科学军事价值','minVal'=>0,'maxVal'=>4,nopadding=>1],
//        'ps_item4'=>['field'=>'ps_item4','title'=>'创新性','brief'=>'创新性','minVal'=>0,'maxVal'=>1]
    ]
);