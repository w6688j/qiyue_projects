<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/27
 * Time: 15:17
 * 这是一个支付的单独项目,里面封装了一写支付/微信相关的支付,因为这个实在用的太多了,又不想用微信/支付宝的sdk,太难以维护了
 */
//项目根目录
define("WEB_PATH", __DIR__ . DIRECTORY_SEPARATOR);
define('ROOT_PATH', realpath(__DIR__ . '/../../') . "/");    // 根目录---用realpath(),这个方法很机智啊,这样可以直接看到绝对路径了,而且不会显示../../这种恶心的东西
//项目的目录
define('APP_PATH', ROOT_PATH . "app/");

define('DEBUG', false);//调试模式

//额外定义一些支付的常量数据
//v_type 1支付宝,2微信,3网银

define("PAY_TYPE_ALIPAY", 1);//支付宝
define("PAY_TYPE_WEIXIN", 2);//微信
define("PAY_TYPE_HEEPAY", 3);//网银

//定义支付返回状态
define("TRADE_SUCCESS", 1); //交易成功
define("TRADE_CLOSE", 2);//交易关闭
define("TRADE_WAIT_PAY", 3);//交易等待支付
define("TRADE_FINISHED", 4);//交易完成
define("TRADE_FAIL", 5);//交易失败


//这里指定加载配置文件---分为公共文件/和项目的文件,如果要分为线上和线下,,,公共配置和单独配置名称必须相同//默认为config

define("LOAD_CONFIG", "online_config");//线上配置文件
//define("LOAD_CONFIG", "local_config");//本地配置文件
//载入composer
include ROOT_PATH . 'vendor/autoload.php';



