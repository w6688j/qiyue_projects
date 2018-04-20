<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 16:28
 */
//配置
return array(
    "web_name" => "pp游戏",

    //各种子域名站点
    "home_web" => "www.pp158.com", //官网网址
    "pay_web" => "pay.pp158.cn:8082",//支付子站点
    "gamefish_web" => "gamefish.pp158.cn:8082",//游戏中间站
    "activity_web" => "activity.pp158.cn:8082",//活动中间站


////    //路由方法
//    'route'=>[
//        'gift'=>['Gift','index'],
//    ],
//单数据库配置写法

    "mysql" => [
        'db_name' => 'fishtool',
        'db_host' => '192.168.66.160',
        'db_user' => 'root',
        'db_pwd' => '123123',
        'db_charset' => 'utf8',
        // 可选参数
        'db_port' => 3306,
    ],

//socket连接配置信息
    "socket" => [
        "user_auth" => [
            "host" => '192.168.66.160',
            "port" => "8400",
        ],
        "user_info" => [
            "host" => '192.168.66.160',
            "port" => "8200",
        ],
    ],


    //一维数据的配置方式
//        "oracle"=>[
//            'db_link' => '//192.168.66.160:1521/coobar',
//            'db_user' => 'game_user',
//            'db_pwd' => '123456',
//            'db_charset'=>'ZHS16GBK'
//        ],

    //二维数组的配置方式
    "oracle" => [
        //这个是读数据库 默认.
        "read" => [
            'db_link' => '//192.168.66.160:1521/coobar',
            'db_user' => 'game_user',
            'db_pwd' => '123456',
            'db_charset' => 'ZHS16GBK'
        ],
        //这个是写数据库--由于我操作写比较少
        "write" => [
            'db_link' => '//192.168.66.160:1521/coobar',
            'db_user' => 'game_user',
            'db_pwd' => '123456',
            'db_charset' => 'ZHS16GBK'
        ],
    ],
    'qiyue_log' => [
        "default" => APP_PATH . 'runtime/logs/yiyue/',
        "exception" => APP_PATH . 'runtime/logs/exception/',
        "error" => APP_PATH . 'runtime/logs/error/',
        "db" => APP_PATH . 'runtime/logs/db/',
        "heepay_callback"=>APP_PATH . 'runtime/logs/pay/heepay/callback/',
        "heepay_callback_error"=>APP_PATH . 'runtime/logs/pay/heepay/callback_error/',
        "wxpay_app_callback"=>APP_PATH . 'runtime/logs/pay/wxpay/app/callback/',
        "wxpay_web_callback"=>APP_PATH . 'runtime/logs/pay/wxpay/web/callback/',
        "wxpay_app_callback_error"=>APP_PATH . 'runtime/logs/pay/wxpay/app/callback_error/',
        "wxpay_web_callback_error"=>APP_PATH . 'runtime/logs/pay/wxpay/web/callback_error/',
        "alipay_callback"=>APP_PATH . 'runtime/logs/pay/alipay/alipay_callback/',
        "alipay_callback_error"=>APP_PATH . 'runtime/logs/pay/alipay/alipay_callback_error/',
    ],
    "exception_hander" => [
        "error" => ['\\app\\core\\ExceptionHandle', "errorHandle"],
        "exception" => ['\\app\\core\\ExceptionHandle', "exceptionHandle"],
    ],
);