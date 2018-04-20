<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 18:31
 */

namespace app\module\gamefish\controller;

use app\core\utils\RandomUtil;
use app\service\RegisterUserService;
use atphp\Config;
use atphp\Controller;
use atphp\exception\ExceptionExt;
use atphp\Request;
use atphp\util\JsonUtil;
use atphp\util\SessionUtil;

class RegisterController extends Controller
{

    public function index()
    {
        //网吧ID
        $netbarid = Request::getInteger("netbarid");
        $token = RandomUtil::getRandom();

        SessionUtil::set("register_token", $token);

        $this->assign([
            "version" => Config::get("version"),
            "netbarid" => $netbarid,
            "token" => $token,
        ]);

        $this->display("Register/index.html");
    }


    //ajax 注册控制器
    public function register()
    {
        if (Request::isAjax()) {
            $account = Request::getString("account");
            $netbarid = Request::getInteger("netbarid");
            $nickname = Request::getString("nickname");
            $password = Request::getString("password");
            $repassword = Request::getString("repassword");
            $realname = Request::getString("realname");
            $idcard = Request::getString("idcard");
            $gender = Request::getInteger("gender");
            $license = Request::getString("license"); //同意条款
            $token = Request::getString("token"); //注册token 暂时还没有用到
            $verifyimg = Request::getString("verifyimg");

            $register_data = array(
                "account" => $account,
                "nickname" => $nickname,
                "password" => $password,
                "repassword" => $repassword,
                "realname" => $realname,
                "idcard" => $idcard,
                "license" => $license,
                "verifyimg" => $verifyimg,
                "from" => 2, //0   营销大师 1=>官网 2=>客户端
                "gender" => $gender,
                "netbarid" => $netbarid,
            );
            $return_array = RegisterUserService::register($register_data);
        } else {
            $return_array["msg"] = "非法访问";
            $return_array["status"] = false;
        }

        echo JsonUtil::encode($return_array);
    }

    //ajax ---检测账号
    public function checkAccount()
    {
        if (Request::isAjax()) {
            $account = Request::getString("param");

            $registerService = new RegisterUserService();

            $return_array = $registerService->checkAccount($account);

            $this->output($return_array["msg"], $return_array["status"]);
        } else {
            $this->output("非法访问", false);
        }
    }

    //ajax ---检测昵称
    public function checkName()
    {

        if (Request::isAjax()) {
            $nickName = Request::getString("param");

            $registerService = new RegisterUserService();

            $return_array = $registerService->checkName($nickName);

            $this->output($return_array["msg"], $return_array["status"]);

        } else {
            $this->output("非法访问", false);
        }
    }


    //ajax --检测身份证号码
    public function checkIdCard()
    {
        if (Request::isAjax()) {
            $idCard = Request::getString("param");

            $registerService = new RegisterUserService();

            $return_array = $registerService->checkIdCard($idCard);

            $this->output($return_array["msg"], $return_array["status"]);

        } else {
            $this->output("非法访问", false);
        }
    }


    //validform ajax输出
    private function output($msg, $status = true)
    {
        $array = array(
            "info" => $msg,
            "status" => $status ? 'y' : 'n',
        );
        echo JsonUtil::encode($array);
        exit;
    }

}