<?php
return array(
      "Default"=>array(
            'DB_TYPE'=>'mysql',// 数据库类型
            'DB_HOST'=>'localhost',// 服务器地址
            'DB_NAME'=>'newatp',// 数据库名
            'DB_USER'=>'root',// 用户名
            'DB_PWD'=>'',// 密码
            'DB_PORT'=>3306,// 端口
            'DB_PREFIX'=>'newatp_',// 数据库表前缀
            'DB_CHARSET'=>'utf8',// 数据库字符集
            'URL_CASE_INSENSITIVE' => false
      ),
      "Oracle"=>array(
          'DB_TYPE'=>'Oracle',// 数据库类型
          'DB_HOST'=>'10.78.103.11',// 服务器地址
          'DB_NAME'=>'chenweizhao',// 数据库名
          'DB_USER'=>'nga',// 用户名
          'DB_PWD'=>'nga',// 密码
          'DB_PORT'=>1521,// 端口
          'DB_PREFIX'=>'newatp_',// 数据库表前缀
          'DB_CHARSET'=>'utf8',// 数据库字符集
    ),
    'SESSION_PREFIX' => 'risks_',
    'COOKIE_PREFIX' => 'risks_',
    'COOKIE_DOMAIN' => '',
    'LOAD_EXT_CONFIG'=>['mark'=>'mark_config'],
    'COOKIE_HTTPONLY' => ''
);