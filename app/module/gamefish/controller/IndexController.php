<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 15:18
 * gamefish 中间控制跳转页
 */
namespace app\module\gamefish\controller;

use app\module\activity\controller\GiftController;
use atphp\Config;
use atphp\Controller;
use atphp\exception\ExceptionExt;
use atphp\Request;

class IndexController extends Controller
{
    public function index()
    {

        $gid = Request::getInteger("gid");

        //兼容老版本的QQ回调
        $this->_LoginCallbackForCompatibleOld();

        //这是首页地址
        $protocol = Request::isSsl() ? "https://" : "http://";
        $base_url = $protocol . $_SERVER["HTTP_HOST"]; //当年页面地址
        $home_url = $protocol . Config::get("home_web");//pp158地址
        $activity_url = $protocol . Config::get("activity_web");//pp158地址

//        print_r($_GET);
        switch ($gid) {
            case 0:
                //首页
                header("location:" . $home_url);
                break;
            case 1:
                //注册页
                $registerCtrl = new RegisterController();
                $registerCtrl->index();

                break;
            case 2:
                //找回密码跳转到PP官网
                $url = $home_url . "/Index/findPwd.html";
                header("location:" . $url);

                break;
            case 8:
                //QQ登录 ---正式上线要改回调..现在也没法调试
                $url = $base_url . "/QQLogin/index";
                header("location:" . $url);
                break;

            case 10:
                //绑定手机页面

                $uid = Request::getString("uid");
                $s1 = Request::getString("s1");
                $s2 = Request::getString("s2");
                $s3 = Request::getString("s3");
                $s4 = Request::getString("s4");

                $url = $home_url . "/Jump/index/uid/" . intval($uid) . "/s1/" . urlencode($s1) . "/s2/" . urlencode($s2) . "/s3/" . urlencode($s3) . "/s4/" . urlencode($s4);
                header("location:" . $url);

                break;
            case 11:
                //微信扫码登录
                $url = $base_url . "/WeixinLogin/index";
                header("location:" . $url);

                break;
            case 13:
                //游戏介绍
                $url = $home_url . '/Game/index.html';
                header("location:" . $url);

                break;
            case 14:
                //下载中心
                $url = $home_url . '/Downloads/index.html';
                header("location:" . $url);
                break;
            case 15:
                //最新活动
                $url = $home_url . '/Newslog/index.html';
                header("location:" . $url);
                break;
            case 16:
                //新老用户回馈活动
                $param =http_build_query(Request::get());
                $url =$activity_url.'/gift/index?'.$param;
                header("location:" . $url);
                break;

            case 50:
                //跳转到新闻页面
                $url = $home_url . "/News/index.html";
                header("location:" . $url);
                break;

            default:
                header("HTTP/1.1 404 Not Found");
                header("status: 404 Not Found");
        }
    }


    /**
     * 由于QQ.微信,特么所有的东西都不在我这里,,好尴尬,和老罗讨论,采用这种方式来兼容
     * 这里为了兼容老版本里面的QQ登录回调,http://gamefish.pp158.com/index.php?m=qq_login_callback
     * 现在 http://gamefish.pp158.com/QQLogin/callback
     *
     * 及微信回调 http://gamefish.pp158.com/index.php?m=wclgcbk
     * 现在地址: http://gamefish.pp158.com/WeixinLogin/callback
     *
     */
    private function _LoginCallbackForCompatibleOld()
    {
        $login_callback_type = Request::getString("m");


        if (!empty($login_callback_type)) {

            if ($login_callback_type == 'wclgcbk') {
                //微信回调
                $wxLogin = new WeixinLoginController();
                $wxLogin->callback();
                exit;

            } else if ($login_callback_type == 'qq_login_callback') {

                //QQ回调
                $qqLogin = new QQLoginController();

                $qqLogin->callback();
                //如果调用了回调直接终止
                exit;
            }
        }
    }
}