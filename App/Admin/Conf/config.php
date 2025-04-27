<?php
return array(
   // '配置项'=>'配置值'
    //'VIEW_PATH'=>'./Tm/',
    'TMPL_FILE_DEPR'=>'_',

    'DEFAULT_THEME'=>'Dt/Admin',

    //开启令牌
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__', // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令

);