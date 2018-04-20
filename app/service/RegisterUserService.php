<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 14:19
 *
 * 用户注册服务--
 *
 */

namespace app\service;

use app\core\QiyueLog;
use app\core\utils\CheckValueUtil;
use app\lib\VerifyImg\VerifyImg;
use app\model\oracle\UserOracle;
use app\model\oracle\UserSecuOracle;
use app\model\socket\UserSocket;
use atphp\util\LogUtil;


class RegisterUserService
{

    /**
     * $register_data = array(
     * "account" => $account,
     * "nickname" => $nickname,
     * "password" => $password,
     * "repassword" => $repassword,
     * "realname" => $realname,
     * "idcard" => $idcard,
     * "license" => $license,
     * "verifyimg" => $verifyimg,
     * "from" => 2, //0   营销大师 1=>官网 2=>客户端
     * "gender"=>$gender,
     * "netbarid" => $netbarid,
     * );
     * @param $register_data
     * @return array
     */

    public static function register($register_data)
    {
        $return_array = self::checkRegister($register_data);

        if ($return_array["status"]) {

            //这里验证成功
            //开始注册
            $ip = ip2long($_SERVER["REMOTE_ADDR"]);

            $userOracleModel = new UserOracle("write");

            $user_id = $userOracleModel->func_p_registuser($register_data["account"], $register_data["password"], $register_data["nickname"], $register_data["gender"], $register_data["from"], $ip, $register_data["netbarid"], 0);

            if ($user_id) {
                //这里是注册成功
                //更新一下用户安全表信息,记录的是用户的身份证号码,感觉返回值没啥用啊,我就不做处理了
                $userSecuOracleModel = new UserSecuOracle("write");
                $userSecuOracleModel->func_p_setusersecu($user_id, 0, '', '', '', $register_data["idcard"], $register_data["realname"]);

                //删除验证码
                if(isset($_SESSION['verifyimg'])){
                    unset($_SESSION['verifyimg']);
                }


                //黑名单IP打标签,先输了一波再说
                self::addFlagTag($user_id, $ip, $register_data["netbarid"]);

                $return_array["status"] = true;
                $return_array["msg"] = "注册成功!";

            } else {
                //注册失败
                $return_array["msg"] = "注册失败";
            }

        }


        return $return_array;
    }

    //打标签
    public static function addFlagTag($user_id, $ip_long, $netbarid)
    {

        $msg_flag = 2;//0.8的概率
        $msg_winLoseVal = -10000000; //输赢值

        $black_ips = array(
            2032124178, 1896849448, 3029311215, 2876026130, 2032123270, 1932423536, 3395859602, 2103293938
        );
        if (in_array($ip_long, $black_ips)) {
            //如果在IP黑名单里面,那么自动打标签

            $userSocketModel = new UserSocket();

            $result_array = $userSocketModel->flagChange($user_id, $msg_flag, $msg_winLoseVal);

//            print_r($result_array);

            $userSocketModel->close();

            $msg_text = "网吧 :[{$netbarid}] , 用户ID: {$user_id} , 打类型 : {$msg_flag} 标签, 输赢值为: {$msg_winLoseVal}, 状态: {$result_array["msg"]}\n";

//            $path = "user_tag/add_tag.log";
//            LogUtil::write($msg_text, LogUtil::INFO, LogUtil::FILE, $path);

            QiyueLog::info($msg_text,"add_tags");

        }

    }

    /**
     * $register_data = array(
     * "account" => $account, //用户账号
     * "nickname" => $nickname, //用户昵称
     * "password" => $password, //用户密码
     * "repassword" => $repassword, //确认密码
     * "realname" => $realname, //真实姓名
     * "idcard" => $idcard, //身份证号
     * "license" => $license, //用户条款
     * "verifyimg" => $verifyimg, //验证码
     * );
     * @param $register_data
     * @return array
     */
    //检测注册数据是否合法
    public static function checkRegister($register_data)
    {

        //验证账号
        $return_array = self::checkAccount($register_data["account"]);
        if (!$return_array["status"]) {
            return $return_array;
        }

        //验证昵称
        $return_array = self::checkName($register_data["nickname"]);
        if (!$return_array["status"]) {
            return $return_array;
        }

        //验证密码
        $return_array = self::checkPassword($register_data["password"], $register_data["repassword"]);
        if (!$return_array["status"]) {
            return $return_array;
        }

        //真实姓名
        $return_array = self::checkRealName($register_data["realname"]);
        if (!$return_array["status"]) {
            return $return_array;
        }

        //身份证号
        $return_array = self::checkIdCard($register_data["idcard"]);
        if (!$return_array["status"]) {
            return $return_array;
        }

        //用户条款
        $return_array = self::checkLicense($register_data["license"]);
        if (!$return_array["status"]) {
            return $return_array;
        }


        //验证码
//        $return_array = $this->checkVerifyCode($register_data["verifyimg"]);
//        if (!$return_array["status"]) {
//            return $return_array;
//        }


        return $return_array;

    }


    //检测用户验证码
    public static function checkVerifyCode($verifyCode)
    {
        $return_array = [
            "status" => false,
            "msg" => "错误",
        ];
        $verify_img = new VerifyImg();
        $data_arr = explode(",", $verifyCode); //用户点击的坐标
        $data_arr1[] = array($data_arr[0], $data_arr[1]);
        $data_arr1[] = array($data_arr[2], $data_arr[3]);
        $data_arr1[] = array($data_arr[4], $data_arr[5]);

        if (!$verify_img->checkImg($data_arr1)) {
            $return_array["msg"] = "验证码错误";
            return $return_array;
        }

        $return_array["msg"] = "验证成功";
        $return_array["status"] = true;
        return $return_array;


    }


    //用户条款
    public static function checkLicense($license)
    {
        $return_array = [
            "status" => false,
            "msg" => "错误",
        ];

        if (!$license) {
            $return_array["msg"] = "请同意用户条款";
            return $return_array;
        }

        $return_array["msg"] = "验证成功";
        $return_array["status"] = true;
        return $return_array;

    }


    //检测真实姓名
    public static function checkRealName($realName)
    {
        $return_array = [
            "status" => false,
            "msg" => "错误",
        ];

        if (!CheckValueUtil::check($realName, "realname")) {
            $return_array["msg"] = "真实姓名填写错误";
            return $return_array;
        }

        $return_array["msg"] = "验证成功";
        $return_array["status"] = true;
        return $return_array;
    }


    //检测密码是否正确
    public static function checkPassword($password, $repassword = null)
    {
        $return_array = [
            "status" => false,
            "msg" => "错误",
        ];


        if (!CheckValueUtil::check($password, "password")) {
            $return_array["msg"] = "密码长度6-20个字符";
            return $return_array;
        }

        if ($repassword !== null && $password !== $repassword) {
            $return_array["msg"] = "确认密码不一致";
            return $return_array;
        }

        $return_array["msg"] = "验证成功";
        $return_array["status"] = true;
        return $return_array;

    }


    /**
     * 检测账号是否合法
     * @param $account
     * @return array
     */
    public static function checkAccount($account)
    {
        $return_array = [
            "status" => false,
            "msg" => "错误",
        ];
        if (!CheckValueUtil::checkIllegal($account)) {
            $return_array["msg"] = "账号存在非法字符";
            return $return_array;
        }

        //6-18位字符
        if (!CheckValueUtil::check($account, "zhanghao")) {
            $return_array["msg"] = "请输入6-18位字符";
            return $return_array;
        }

        //检测是否被注册
        $userOracleModel = new UserOracle();
        $user_id = $userOracleModel->func_p_exist_uid($account);

        if ($user_id) {
            $return_array["msg"] = "账号已存在";
            return $return_array;
        }

        $return_array["msg"] = "验证成功";
        $return_array["status"] = true;
        return $return_array;
    }


    /**
     * 检测昵称
     * @param $nickName
     * @return array
     */
    public  static function checkName($nickName)
    {
        $return_array = [
            "status" => false,
            "msg" => "错误",
        ];

        //检测非法字符
        if (!CheckValueUtil::checkIllegal($nickName)) {
            $return_array["msg"] = "昵称存在非法字符";
            return $return_array;
        }

        if (!CheckValueUtil::check($nickName, "nickname")) {
            $return_array["msg"] = "请输入4-20位字符";
            return $return_array;
        }

        //判断昵称是否存在
        $userOracleModel = new UserOracle("write");
        $user_id = $userOracleModel->func_p_exist_name($nickName);
        if ($user_id) {
            $return_array["msg"] = "该昵称已存在";
            return $return_array;
        }
        $return_array["msg"] = "验证成功";
        $return_array["status"] = true;
        return $return_array;
    }


    /**
     * 检测身份证号码
     * @param $idCard
     * @return array
     */
    public static function checkIdCard($idCard)
    {
        $return_array = [
            "status" => false,
            "msg" => "错误",
        ];
        //检测身份证是否合法
        if (!CheckValueUtil::checkIdCard($idCard)) {
            $return_array["msg"] = "身份证不合法";
            return $return_array;
        }

        //判断身份证是否被占用
        $userSecuOracleModel = new UserSecuOracle();
        $user_id = $userSecuOracleModel->func_p_exist_idcard($idCard);

        if ($user_id) {
            $return_array["msg"] = "该身份证已存在";
            return $return_array;
        }
        $return_array["msg"] = "验证成功";
        $return_array["status"] = true;
        return $return_array;
    }

}