<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 15:36
 */

//设置默认市区
header("Content-type: text/html; charset=utf-8");

//加载定义文件
require_once __DIR__ . "/common/define.php";
//加载类库
include_once COMMON_PATH . 'function.php';

//注册自动加载
spl_autoload_register('auto_load');

if(!defined("MODULE_NAME")){
    $subDomain = \atphp\Request::subDomain();
    define('MODULE_NAME', $subDomain);
}
if(!defined("MODULE_PATH")){
    define('MODULE_PATH', APP_PATH . "module/".MODULE_NAME . "/");
}



//加载公共的function.php文件
if(is_file(APP_PATH."common/function.php")){
    include_once APP_PATH."common/function.php";
}
//加载当前的模块的function文件
if(is_file(MODULE_PATH."common/function.php")){
    include_once MODULE_PATH."common/function.php";
}

//系统调试功能
require_once __DIR__ . "/common/debug.php";


date_default_timezone_set(\atphp\Config::get('timezone'));


//自动建立运行时目录
if (!is_dir(APP_PATH . "runtime")) \atphp\util\FileUtil::mkdir(APP_PATH . "runtime");


if (PHP_SAPI == 'cli') {
    //   \atphp\CLI_ATPHP::run();
} else {

    //开始跑框架
    \atphp\ATPHP::run();

}

