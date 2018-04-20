<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 17:39
 */

//网站的协议
$protocol = \atphp\Request::isSsl() ? "https://" : "http://";

return array(
    'route' => [
        'url.php' => ['Index', 'index'],//这个只是为了兼容老版本gamefish.pp158.com/url.php?gid=1这样的链接
    ],

    "version" => "1.5",//前端数据控制缓存用这个,省的前端的js一直有缓存,好烦的

    //点选验证码配置
    "verifyImg" => [
        "verify_width" => 198,//图片的宽度
        "verify_height" => 90,//图片的高度
        "verify_fontsize" => 25,//字体大小
        "verify_font_space" => 8,//给每个字符一些间隔
    ],

    //QQ授权配置--登录
    "qqOauth" => [
        "app_id" => '100292182',
        "app_key" => "89a0540fa973e05a845e7d809bb0c277",
        "qq_scope" => "get_user_info,add_topic,add_one_blog,add_album,upload_pic,list_album,add_share,check_page_fans,add_t,add_pic_t,get_info",
        "redirect_uri" => $protocol . $_SERVER["HTTP_HOST"] . "/index.php?m=qq_login_callback",
    ],

    //微信授权扫码--登录
    "weixinLogin" => [
        "app_id" => "wx69f456debc0003ae",
        "app_secret" => "0aae42b1ca008eb05d170b916a913451",
        "redirect_uri" => $protocol . $_SERVER["HTTP_HOST"] . "/index.php?m=wclgcbk",
    ],

    //日志路径
    'log_path' => RUNTIME_PATH . "gamefish/",
);