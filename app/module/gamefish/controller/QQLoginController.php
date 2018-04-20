<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/21
 * Time: 9:21
 */

namespace app\module\gamefish\controller;


use app\lib\QQOauth\QQOauth;
use atphp\Controller;
use atphp\exception\ExceptionExt;
use atphp\Request;

class QQLoginController extends Controller
{

    //QQ登录请求
    public function index()
    {
        $qqOauth = new QQOauth();
        $qqOauth->sendLogin();
    }

    //回调的地址
    public function callback()
    {
        $return_array = [
            "status" => false,
            "msg" => "error",
            "data" => [],
        ];

        $return_array["status"] = true;
        $return_array["msg"] = "success";

        $return_array["data"] = $this->callback_deal();
        $this->assign("return_array", $return_array);

        $this->display("QQLogin/callback");
    }


    private function callback_deal()
    {
        $code = Request::getString("code");
        if (empty($code)) {
            throw new ExceptionExt("QQ登录出错了,错误代码: -101");
        }
        $qqOauth = new QQOauth();
        $access_token = $qqOauth->getAccesToken();

        if (!$access_token) {
            throw new ExceptionExt("QQ登录出错了,错误代码: -102");
        }

        $openid = $qqOauth->getOpenId($access_token);

        if (!$openid) {
            throw new ExceptionExt("QQ登录出错了,错误代码: -103");
        }

        $qqUser = $qqOauth->get_KJ_userInfo($access_token, $openid);

        // $qq_nickname = urlencode($qqUser["nickname"]); //[older version]
        if (isset($qqUser["nickname"])) {
            $qq_nickname = $qqUser["nickname"];
            $zh = '\x{4e00}-\x{9fa5}';
            //将英文大小写字母、数字、中文以外的所有字符替换掉
            $qq_nickname = preg_replace("/[^A-Za-z0-9'.$zh.']+/u", "", $qq_nickname);
        } else {
            $qq_nickname = md5(uniqid(time()));
        }


        return [
            "openid" => $openid,
            "nickname" => $qq_nickname,
        ];
    }
}