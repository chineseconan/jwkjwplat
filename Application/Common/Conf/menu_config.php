<?php
//系统配置项
return array(
    //主页全部菜单
    'menu' => array(
        0 => array(
            'title' => '基础', 'icon' => 'fa-clock-o',
            'spread' => 1, //是否展开
            'isControlTitle'=> true, //此属性控制该菜单下的子菜单打开时窗口标题是否一致
            'children' => array(
                0 => array(
                    'title' => '整体切换数据库', //标题
                    'icon' => 'fa-envelope-o',//图标
                    'href' => 'Base/CheckDB/basecheck',//链接
                ),
                1 => array(
                    'title' => 'M方法读其它库',
                    'icon' => '&#xe63c;',
                    'href' => 'Base/CheckDB/mfunccheck',
                ),
                2 => array(
                    'title' => 'BootstrapTable',
                    'icon' => 'fa-tasks',
                    'href' => 'Base/BootstrapTable/table',
                ) ,
                3 => array(
                    'title' => 'Handson表单(非IE)',
                    'icon' => 'fa-tasks',
                    'href' => 'Base/HandsonTable/table',
                ) ,
                4 => array(
                    'title' => 'ztee左侧拖到右侧',
                    'icon' => 'fa-tasks',
                    'href' => 'Base/ZTree/ztreeSearch',
                )
            )
        ),
        1 => array(
            'title' => '文件相关', 'icon' => 'fa-life-ring', 'spread' => 1,
            'children' => array(
                0 => array(
                    'title' => 'CSV操作',
                    'icon' => 'fa-flask',
                    'href' => 'FilePlugin/Csv/index'
                ),
                1 => array(
                    'title' => 'EXCEL操作',
                    'icon' => 'fa-jpy',
                    'href' => 'FilePlugin/Excel/index'
                ),
                2 => array(
                    'title' => 'WORD操作',
                    'icon' => 'fa-calendar',
                    'href' => 'FilePlugin/Word/index'
                ),
                3 => array(
                    'is_file' => true,
                    'title' => '多、大文件上传 ',
                    'icon' => 'fa-area-chart',
                    'href' => 'Public/vendor/webuploader/examples/image-upload'
                )
            )
        ),
    )
);

