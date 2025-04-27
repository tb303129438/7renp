<?php
//header("Location:index.php?m=Admin&a=login");
//header("Location:Admin/index/login");
define('BIND_MODULE','Admin');
// 绑定访问Index控制器
define('BIND_CONTROLLER','Login');
// 定义应用目录

define('APP_PATH','./App/');

// 引入ThinkPHP入口文件
require './Tp/ThinkPHP.php';
?>