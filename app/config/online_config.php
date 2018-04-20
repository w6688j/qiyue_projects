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
    "pay_web" => "pay.pp158.com",//支付子站点
    "gamefish_web" => "gamefish.pp158.com",//游戏中间站
    "activity_web" => "activity.pp158.com",//活动中间站


//socket连接配置信息
    "socket" => [
        "user_auth" => [
            "host" => '10.46.66.83',
            "port" => "8400",
        ],
        "user_info" => [
            "host" => '10.46.66.83',
            "port" => "8200",
        ],
    ],

    "mysql" => [
        'db_name' => 'fishtool',
        'db_host' => '127.0.0.1',
        'db_user' => 'fishtool',
        'db_pwd' => 'SXm13T8CO7oO64DH',
        'db_charset' => 'utf8',
        // 可选参数
        'db_port' => 3306,
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
            'db_link' => '//10.168.35.225/coobardg',
            'db_user' => 'coobar_analyse',
            'db_pwd' => 'coobar123324',
            'db_charset' => 'ZHS16GBK'
        ],
        //这个是写数据库--由于我操作写比较少
        "write" => [
            'db_link' => '//10.46.75.67:1521/coobar',
            'db_user' => 'coobar',
            'db_pwd' => 'pwdquery',
            'db_charset' => 'ZHS16GBK'
        ],
    ],

//    'qiyue_log' => [
//        "default" => APP_PATH . 'runtime/logs/yiyue/',
//        "exception" => APP_PATH . 'runtime/logs/exception/',
//        "error" => APP_PATH . 'runtime/logs/error/',
//        "db" => APP_PATH . 'runtime/logs/db/',
//        "add_tags" => APP_PATH . 'runtime/logs/add_tags/',//打标签
//    ],

    'qiyue_log' => [
        "default" => '/data/logs/qiyue_project/qiyue/',//默认
        "exception" => 'data/logs/qiyue_project/exception/',//数据异常
        "error" => 'data/logs/qiyue_project/logs/error/',//数据报错
        "db" => 'data/logs/qiyue_project/db/',//数据库
        "add_tags" => 'data/logs/qiyue_project/add_tags/',//打标签
        "heepay_callback" => '/data/logs/qiyue_project/pay/heepay/callback/',
        "heepay_callback_error" => '/data/logs/qiyue_project/heepay/callback_error/',
        "wxpay_app_callback" => '/data/logs/qiyue_project/pay/wxpay/app/callback/',
        "wxpay_web_callback" => '/data/logs/qiyue_project/pay/wxpay/web/callback/',
        "wxpay_app_callback_error" => 'runtime/logs/pay/wxpay/app/callback_error/',
        "wxpay_web_callback_error" => '/data/logs/qiyue_project/pay/wxpay/web/callback_error/',
        "alipay_callback" => '/data/logs/qiyue_project/pay/alipay/alipay_callback/',
        "alipay_callback_error" => '/data/logs/qiyue_project/pay/alipay/alipay_callback_error/',
    ],

    "exception_hander" => [
        "error" => ['\\app\\core\\ExceptionHandle', "errorHandle"],
        "exception" => ['\\app\\core\\ExceptionHandle', "exceptionHandle"],
    ],

);