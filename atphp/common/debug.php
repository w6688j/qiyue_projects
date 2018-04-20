<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/29
 * Time: 15:19
 * 定义调试信息
 */

//打开PHP的错误显示
ini_set('display_errors', true);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//这里开启错误提示

/**
 * if (DEBUG && PHP_SAPI != 'cli') {
 * //内网服务器,,不知道为什么偶尔出现,mbstring模块应用重复,诡异,找不到问题,是不是没有装好???,有时间看看
 * if(!strpos($_SERVER["REMOTE_ADDR"],"192.168") || strpos($_SERVER["REMOTE_ADDR"],"60.191.111.6")){
 * //载入友好的错误显示类
 * $whoops = new \Whoops\Run;
 * $errorPage = new \Whoops\Handler\PrettyPageHandler;
 * $errorPage->setPageTitle("PHP报错了,要注意了哇");
 * $whoops->pushHandler($errorPage);
 * $whoops->register();
 * }
 *
 *
 * } else {
 * //  set_error_handler(array('\atphp\exception\ExceptionHandle','errorHandle'));
 * set_exception_handler(array('\atphp\exception\ExceptionHandle', 'exceptionHandle'));
 * }
 **/

$exception_config = \atphp\Config::get("exception_hander");

$exception_error = isset($exception_config) ? $exception_config["error"] : array('\atphp\exception\ExceptionHandle', 'errorHandle');
$exception_exception  = isset($exception_config) ? $exception_config["exception"] : array('\atphp\exception\ExceptionHandle', 'exceptionHandle');

set_error_handler($exception_error);
set_exception_handler($exception_exception);
