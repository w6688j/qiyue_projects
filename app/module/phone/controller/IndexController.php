<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 15:18
 */
namespace app\module\phone\controller;

use atphp\Controller;
use atphp\Request;

class IndexController extends Controller
{

    public function index()
    {
        $url = Request::domain();
        $this->assign([
            "url" => $url,
        ]);
        $this->display();
    }

    public function downLoad()
    {
        //获取USER AGENT
        $type = isset($_GET['type']) ? intval($_GET['type']) : 3;

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        //分析数据
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;

        $home_url = Request::domain();
        if($type==1){
            //测试版
            $iphone_url = 'itms-services://?action=download-manifest&url=https://phone.pp158.com/plist/pplua_in4.plist';
            $android_url = $home_url.'/pplua_in4/setup/PPLua_in4.apk';

        }else if($type==2){
            //程序员下载
            $iphone_url = 'itms-services://?action=download-manifest&url=https://phone.pp158.com/plist/pplua_in5.plist';
            $android_url = $home_url.'/pplua_in5/setup/PPLua_in5.apk';
        }else{
            //正式版
            $iphone_url = 'itms-services://?action=download-manifest&url=https://phone.pp158.com/plist/pplua_test.plist';
            $android_url = 'http://phone.pp158.com/platform/pplua_test/setup/PPLua_test.apk';
        }

        if (stripos($agent, 'mobile mqqbrowser') || stripos($agent, 'MicroMessenger')) {
            //微信
            header("location:{$home_url}");

        } else {
            if ($is_iphone) {
                header("location:{$iphone_url}");
            } elseif ($is_android) {
                header("location:{$android_url}");
            } else {
                header("location:{$home_url}");
            }


        }
    }

}