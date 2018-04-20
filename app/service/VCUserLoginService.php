<?php
/**
 * For: VC客户端登录网页web界面
 * User: caostian
 * Date: 2017/10/19
 * Time: 9:22
 */

namespace app\service;

use app\core\QiyueLog;
use app\model\socket\UserAuthSocket;
use atphp\Request;
use atphp\util\SessionUtil;

class VCUserLoginService
{
    public static function checkUser()
    {
        $uid = Request::getInteger("uid"); //这个是用户ID,PK
        $s = Request::getString("s");

       // QiyueLog::info("活动参数: ".var_export(Request::get(),true));


        if (empty($uid) || empty($s)) {
            //我这里要检测你的sesstion存不存在,如果存在,也是说明你登录了
            if (!SessionUtil::get("user_id")) {
                $return_arr["status"] = false;
                $return_arr["info"] = "授权参数不存在";
                return $return_arr;
            }else{
                $return_arr["status"] = true;
            }
        }else{
            $user_auth_socket = new UserAuthSocket();
            $return_arr = $user_auth_socket->check($uid, $s);
            $user_auth_socket->close();
            if ($return_arr["status"]) {
                SessionUtil::set("sid",$s);
                SessionUtil::set("user_id", $uid);
            }

        }


        return $return_arr;
    }
}