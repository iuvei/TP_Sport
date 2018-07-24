<?php

$array = [
    //'配置项'=>'配置值'
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => DB_HOST_P, // 服务器地址
    'DB_NAME' => DB_NAME_P, // 数据库名
    'DB_USER' => DB_USER_P, // 用户名
    'DB_PWD' => DB_PASS_P, // 密码
    'DB_CHARSET'=>'latin1',
    'DB_PREFIX' => 'web_', // 数据库表前缀
    'URL_MODEL'=>1,
    'URL_HTML_SUFFIX'=>'html',
    'DEFAULT_TIMEZONE'=>'Etc/GMT+4',
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'Layout/layout',
    //开启表单验证
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
];

if($_SERVER['SERVER_ADMIN'] == '6668'){
    $array['TELEPHONE'] = '0063-9279668666';
    $array['COMPLAIN_TELEPHONE'] = '0063-9055166668';
    $array['EMAIL'] = 'hg6668.com@gmail.com';
}else if($_SERVER['SERVER_ADMIN'] == '70887'){
    $array['TELEPHONE'] = '0063-9155588678';
    $array['COMPLAIN_TELEPHONE'] = '0063-9153766558';
    $array['EMAIL'] = 'HG70887@gmail.com';
}


return $array;

?>
