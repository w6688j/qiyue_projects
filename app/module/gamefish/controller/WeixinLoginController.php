<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/21
 * Time: 10:40
 *
 * 微信登录
 */

namespace app\module\gamefish\controller;


use app\lib\WeixinLogin\WeixinLogin;
use atphp\Controller;
use atphp\exception\ExceptionExt;

class WeixinLoginController extends Controller
{

    //微信登录页
    public function index()
    {
        $weixinLogin = new WeixinLogin();
        $weixinLogin->queryLogin();
    }

    //微信回调
    public function callback()
    {
        $return_array = [
            "status" => false,
            "msg" => "error",
            "data" => [],
        ];
//        try {
            $weixinLogin = new WeixinLogin();
            $weixinLogin->checkStatus();
            $data = $weixinLogin->getTokenOpenid();
            $nickName = $weixinLogin->getNickName($data['token'], $data['openid']);

            $return_array["status"] = true;
            $return_array["msg"] = "success";
            $return_array["data"] = [
                "openid" => $data['openid'],
                "nickname" => $nickName,

            ];
//        } catch (ExceptionExt $e) {
//            $return_array["msg"] = $e->getMessage();
//        }

        $this->assign([
            "return_array" => $return_array,
        ]);

        $this->display("WeixinLogin/callback");

    }
}