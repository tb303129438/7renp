<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * ThinkPHP惯例配置文件
 * 该文件请不要修改，如果要覆盖惯例配置的值，可在应用配置文件中设定和惯例不符的配置项
 * 配置名称大小写任意，系统会统一转换成小写
 * 所有配置参数都可以在生效前动态改变
 */
defined('THINK_PATH') or exit();
return  array(
    /* 应用设定 */
    'APP_USE_NAMESPACE'     =>  true,    // 应用类库是否使用命名空间
    'APP_SUB_DOMAIN_DEPLOY' =>  false,   // 是否开启子域名部署
    'APP_SUB_DOMAIN_RULES'  =>  array(
//  	'www.mcpc.com/admin'  => 'Admin',  // admin.domain1.com域名指向Admin模块
//  	'www.mcpc.com'   => 'Pc',  // test.domain2.com域名指向Test模块
//  	
//  	'm.mcpc1.com'   => 'Home',  // test.domain2.com域名指向Test模块
    ), // 子域名部署规则
    

    
    'APP_DOMAIN_SUFFIX'     =>  '', // 域名后缀 如果是com.cn net.cn 之类的后缀必须设置    
    'ACTION_SUFFIX'         =>  '', // 操作方法后缀
    'MULTI_MODULE'          =>  true, // 是否允许多模块 如果为false 则必须设置 DEFAULT_MODULE
    'MODULE_DENY_LIST'      =>  array('Common','Runtime'),
    'CONTROLLER_LEVEL'      =>  1,
    'APP_AUTOLOAD_LAYER'    =>  'Controller,Model', // 自动加载的应用类库层 关闭APP_USE_NAMESPACE后有效
    'APP_AUTOLOAD_PATH'     =>  '', // 自动加载的路径 关闭APP_USE_NAMESPACE后有效

    /* Cookie设置 */
    'COOKIE_EXPIRE'         =>  0,    // Cookie有效期
    'COOKIE_DOMAIN'         =>  '',      // Cookie有效域名
    'COOKIE_PATH'           =>  '/',     // Cookie路径
    'COOKIE_PREFIX'         =>  '',      // Cookie前缀 避免冲突
    'COOKIE_HTTPONLY'       =>  '',      // Cookie httponly设置

    /* 默认设定 */
    'DEFAULT_M_LAYER'       =>  'Model', // 默认的模型层名称
    'DEFAULT_C_LAYER'       =>  'Controller', // 默认的控制器层名称
    'DEFAULT_V_LAYER'       =>  'View', // 默认的视图层名称



    'DEFAULT_LANG'          =>  'zh-cn', // 默认语言
    'DEFAULT_THEME'         =>  'Dt',	// 默认模板主题名称

    'VIEW_PATH'=>'./Tm/',//默认模板路径

    'DEFAULT_MODULE'        =>  'Pc',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
    'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE'      =>  'PRC',	// 默认时区
    'DEFAULT_AJAX_RETURN'   =>  'JSON',  // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_JSONP_HANDLER' =>  'jsonpReturn', // 默认JSONP格式返回的处理方法
    'DEFAULT_FILTER'        =>  'htmlspecialchars', // 默认参数过滤方法 用于I函数...

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    // 'DB_NAME'               =>  'moche',          // 数据库名
    // 'DB_USER'               =>  'moche',      // 用户名
    // 'DB_PWD'                =>  '5tkL72KWjSGcnspw',          // 密码
    'DB_NAME'               =>  'h5_mingdeshandon',          // 数据库名
    'DB_USER'               =>  'h5_mingdeshandon',      // 用户名
    'DB_PWD'                =>  'bBxMTpJTK7JsT4ZH',          // 密码

    
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'tm_',    // 数据库表前缀
    'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    'DB_SQL_BUILD_CACHE'    =>  false, // 数据库查询的SQL创建缓存
    'DB_SQL_BUILD_QUEUE'    =>  'file',   // SQL缓存队列的缓存方式 支持 file xcache和apc
    'DB_SQL_BUILD_LENGTH'   =>  5000, // SQL缓存的队列长度
    'DB_SQL_LOG'            =>  false, // SQL执行日志记录
    'DB_BIND_PARAM'         =>  false, // 数据库写入数据自动参数绑定

    /* 数据缓存设置 */
    'DATA_CACHE_TIME'       =>  0,      // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'   =>  false,   // 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK'      =>  false,   // 数据缓存是否校验缓存
    'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
    'DATA_CACHE_TYPE'       =>  'File',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'       =>  TEMP_PATH,// 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR'     =>  false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       =>  1,        // 子目录缓存级别

    /* 错误设置 */
    'ERROR_MESSAGE'         =>  '页面错误！请稍后再试～',//错误显示信息,非调试模式有效
    'ERROR_PAGE'            =>  '',	// 错误定向页面
    'SHOW_ERROR_MSG'        =>  false,    // 显示错误信息
    'TRACE_MAX_RECORD'      =>  100,    // 每个级别的错误信息 最大记录数

    /* 日志设置 */
    'LOG_RECORD'            =>  false,   // 默认不记录日志
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_FILE_SIZE'         =>  2097152,	// 日志文件大小限制
    'LOG_EXCEPTION_RECORD'  =>  false,    // 是否记录异常信息日志

    /* SESSION设置 */
    'SESSION_AUTO_START'    =>  true,    // 是否自动开启Session
    'SESSION_OPTIONS'       =>  array(), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE'          =>  '', // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX'        =>  '', // session 前缀
    //'VAR_SESSION_ID'      =>  'session_id',     //sessionID的提交变量

    /* 模板引擎设置 */
    'TMPL_CONTENT_TYPE'     =>  'text/html', // 默认模板输出类型
    'TMPL_ACTION_ERROR'     =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   =>  THINK_PATH.'Tpl/think_exception.tpl',// 异常页面的模板文件
    'TMPL_DETECT_THEME'     =>  false,       // 自动侦测模板主题
    'TMPL_TEMPLATE_SUFFIX'  =>  '.html',     // 默认模板文件后缀
    'TMPL_FILE_DEPR'        =>  '/', //模板文件CONTROLLER_NAME与ACTION_NAME之间的分割符
    // 布局设置
    'TMPL_ENGINE_TYPE'      =>  'Think',     // 默认模板引擎 以下设置仅对使用Think模板引擎有效
    'TMPL_CACHFILE_SUFFIX'  =>  '.php',      // 默认模板缓存后缀
    'TMPL_DENY_FUNC_LIST'   =>  'echo,exit',    // 模板引擎禁用函数
    'TMPL_DENY_PHP'         =>  false, // 默认模板引擎是否禁用PHP原生代码
    'TMPL_L_DELIM'          =>  '{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}',            // 模板引擎普通标签结束标记
    'TMPL_VAR_IDENTIFY'     =>  'array',     // 模板变量识别。留空自动判断,参数为'obj'则表示对象
    'TMPL_STRIP_SPACE'      =>  true,       // 是否去除模板文件里面的html空格与换行
    'TMPL_CACHE_ON'         =>  true,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_CACHE_PREFIX'     =>  '',         // 模板缓存前缀标识，可以动态改变
    'TMPL_CACHE_TIME'       =>  0,         // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
    'TMPL_LAYOUT_ITEM'      =>  '{__CONTENT__}', // 布局模板的内容替换标识
    'LAYOUT_ON'             =>  false, // 是否启用布局
    'LAYOUT_NAME'           =>  'layout', // 当前布局名称 默认为layout

    // Think模板引擎标签库相关设定
    'TAGLIB_BEGIN'          =>  '<',  // 标签库标签开始标记
    'TAGLIB_END'            =>  '>',  // 标签库标签结束标记
    'TAGLIB_LOAD'           =>  true, // 是否使用内置标签库之外的其它标签库，默认自动检测
    'TAGLIB_BUILD_IN'       =>  'cx', // 内置标签库名称(标签使用不必指定标签库名称),以逗号分隔 注意解析顺序
    'TAGLIB_PRE_LOAD'       =>  '',   // 需要额外加载的标签库(须指定标签库名称)，多个以逗号分隔

    /* URL设置 */
    'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'             =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_PATHINFO_DEPR'     =>  '/',	// PATHINFO模式下，各参数之间的分割符号
    'URL_PATHINFO_FETCH'    =>  'ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL', // 用于兼容判断PATH_INFO 参数的SERVER替代变量列表
    'URL_REQUEST_URI'       =>  'REQUEST_URI', // 获取当前页面地址的系统变量 默认为REQUEST_URI
    'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置
    'URL_DENY_SUFFIX'       =>  'ico|png|gif|jpg', // URL禁止访问的后缀设置
    'URL_PARAMS_BIND'       =>  true, // URL变量绑定到Action方法参数
    'URL_PARAMS_BIND_TYPE'  =>  0, // URL变量绑定的类型 0 按变量名绑定 1 按变量顺序绑定
    'URL_PARAMS_FILTER'     =>  false, // URL变量绑定过滤
    'URL_PARAMS_FILTER_TYPE'=>  '', // URL变量绑定过滤方法 如果为空 调用DEFAULT_FILTER
    'URL_ROUTER_ON'         =>  true,   // 是否开启URL路由
    'URL_ROUTE_RULES'       =>  array(
        'user/add' => 'Home/user/add',
        'user/doadd' => 'Home/user/doadd',
        'user/ajax' => 'Home/user/ajax',
        'user/member'=>'Home/user/member',
        'user/logout'=>'Home/user/logout',
        'user/profile'=>'Home/user/profile',
        'user/doprofile'=>'Home/user/doprofile',
        'user/password'=>'Home/user/password',
        'user/dopassword'=>'Home/user/dopassword',
        'user/address'=>'Home/user/address',
        'user/address_add'=>'Home/user/address_add',
        'user/doaddress_add'=>'Home/user/doaddress_add',
        'user/address_edit'=>'Home/user/address_edit',
        'user/doaddress_edit'=>'Home/user/doaddress_edit',
        'user/tree'=>'Home/user/tree',
        'user/product'=>'Home/user/product',
        'user/product_view'=>'Home/user/product_view',
        'user/product_order'=>'Home/user/product_order',
        'user/doproduct_order'=>'Home/user/doproduct_order',
        'user/order'=>'Home/user/order',
        'user/income'=>'Home/user/income',
        'user/message'=>'Home/user/message',
        'user/domessage'=>'Home/user/domessage',
        'user/news'=>'Home/user/news',
        'user/news_view'=>'Home/user/news_view',
        'user/integral'=>'Home/user/integral',
        'user/recharge'=>'Home/user/recharge',
        'user/dorecharge'=>'Home/user/dorecharge',
        'user/transfer'=>'Home/user/transfer',
        'user/dotransfer'=>'Home/user/dotransfer',
        'user/cash'=>'Home/user/cash',
        'user/docash'=>'Home/user/docash',
        'user/paypwd'=>'Home/user/paypwd',
        'user/dopaypwd'=>'Home/user/dopaypwd',
        'user/cash_history'=>'Home/user/cash_history',
        'user/backcash'=>'Home/user/backcash',
        'user/incomefrom'=>'Home/user/incomefrom',
        'user/touch'=>'Home/user/touch',
        'user/replay' => 'Home/user/replay',
        'user/doreplay' => 'Home/user/doreplay',
        'user/up' => 'Home/user/up',
        'user/doup' => 'Home/user/doup',
        'user/bank' => 'Home/user/bank',
        'user/dobank' => 'Home/user/dobank',
        'user/dopayreturn' => 'Home/user/dopayreturn',
        'user/cart' => 'Home/user/cart',
        'user/docart' => 'Home/user/docart',
        'user/docartorder'=>'Home/user/docartorder',
        'user/view'=>'Home/user/view',
        'user/paidan'=>'Home/user/paidan',
        'user/dopaidan'=>'Home/user/dopaidan',
        'user/tpaidan'=>'Home/user/tpaidan',
        'user/dotpaidan'=>'Home/user/dotpaidan',
        'user/lpaidan'=>'Home/user/lpaidan',
        'user/jihuoma'=>'Home/user/jihuoma',
        'user/dojihuoma'=>'Home/user/dojihuoma',
        'user/tjihuoma'=>'Home/user/tjihuoma',
        'user/dotjihuoma'=>'Home/user/dotjihuoma',
        'user/ljihuoma'=>'Home/user/ljihuoma',
        'user/team'=>'Home/user/team',
        'user/dohelp'=>'Home/user/dohelp',
        'user/dodohelp'=>'Home/user/dodohelp',
        'user/ldohelp'=>'Home/user/ldohelp',
        'user/gethelp'=>'Home/user/gethelp',
        'user/dogethelp'=>'Home/user/dogethelp',
        'user/lgethelp'=>'Home/user/lgethelp',
        'user/qiangdan'=>'Home/user/qiangdan',
        'user/deletecart'=>'Home/user/deletecart',
        'user/vdohelp'=>'Home/user/vdohelp',
        'user/vgethelp'=>'Home/user/vgethelp',
        'user/qrcode'=>'Home/user/qrcode',
        'user/freeze'=>'Home/user/freeze',
        'user/card'=>'Home/user/card',
        'user/docard'=>'Home/user/docard',
        'user/redofreeze'=>'Home/user/redofreeze',
        'user/history'=>'Home/user/history',

        'user' => 'Home/user/index',

        'reg'=>'Home/index/reg',
        '/^recharge$/'=>'Home/index/recharge',
        '/^login$/'=>'Home/index/login',
        '/^view$/'=>'Home/index/view',
        '/^order$/'=>'Home/index/order',
        '/^doorder$/'=>'Home/index/doorder',
          '/^join$/'=>'Home/index/join',
           '/^message$/'=>'Home/index/message',
           '/^domessage$/'=>'Home/index/domessage',
          '/^news$/'=>'Home/index/news',

           '/^news_view$/'=>'Home/index/news_view',
		  '/^help_view$/'=>'Home/index/help_view',
    ), // 默认路由规则 针对模块
    'URL_MAP_RULES'         =>  array(), // URL映射定义规则

    /* 系统变量名称设置 */
    'VAR_MODULE'            =>  'm',     // 默认模块获取变量
    'VAR_ADDON'             =>  'addon',     // 默认的插件控制器命名空间变量
    'VAR_CONTROLLER'        =>  'c',    // 默认控制器获取变量
    'VAR_ACTION'            =>  'a',    // 默认操作获取变量
    'VAR_AJAX_SUBMIT'       =>  'ajax',  // 默认的AJAX提交变量
    'VAR_JSONP_HANDLER'     =>  'callback',
    'VAR_PATHINFO'          =>  's',    // 兼容模式PATHINFO获取变量例如 ?s=/module/action/id/1 后面的参数取决于URL_PATHINFO_DEPR
    'VAR_TEMPLATE'          =>  't',    // 默认模板切换变量

    'HTTP_CACHE_CONTROL'    =>  'private',  // 网页缓存控制
    'CHECK_APP_DIR'         =>  true,       // 是否检查应用目录是否创建
    'FILE_UPLOAD_TYPE'      =>  'Local',    // 文件上传方式
    'DATA_CRYPT_TYPE'       =>  'Think',    // 数据加密方式

);
