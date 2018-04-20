<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 19:07
 * 点选验证码控制器
 */

namespace app\module\gamefish\controller;


use app\lib\VerifyImg\VerifyImg;
use app\service\RegisterUserService;
use atphp\Controller;
use atphp\Request;
use atphp\util\JsonUtil;

class VerifyCodeController extends Controller
{

    //加载验证码
    public function loadImg()
    {
        $verifyImg = new VerifyImg();
        $verifyImg->getImg();
    }

    //需要选择的验证码的字符
    public function selCodes()
    {

        $verify_img = new VerifyImg();

        $data = $verify_img->getData();

        $check_codes = $data["check_codes"];

        $return_arr = array(
            "status" => false,
            "msg" => '获取字符失败!'
        );
        if ($check_codes) {
            $return_arr["status"] = true;
            $return_arr["msg"] = "OK";
            $return_arr["data"] = $check_codes;
        }
        echo JsonUtil::encode($return_arr);
    }

    //验证验证码
    public function CheckImg()
    {
        //检测验证码
        $data = Request::getString("data");

        $registerUserService = new RegisterUserService();

        $return_arr = $registerUserService->checkVerifyCode($data);

        echo JsonUtil::encode($return_arr);
    }
}