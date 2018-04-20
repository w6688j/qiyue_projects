<?php
/**
 * For: 用户授权Socket
 * User: caostian
 * Date: 2017/10/19
 * Time: 9:38
 */

namespace app\model\socket;

use app\core\BaseSocketModel;
use atphp\Config;
use atphp\exception\ExceptionExt;

class UserAuthSocket extends BaseSocketModel
{
    public function __construct()
    {
        $config = Config::get("socket")["user_info"];
        parent::__construct($config["host"], $config["port"], 5);
    }

    public function check($uid, $s)
    {

        $return_array = [
            "status" => false,
            "info" => "查询用户信息失败",
            "data" => "",
        ];
        $content = pack('L', 4 + 30 + 4);  // 4 byte
        $content .= $this->packHead(205, $s); // 30 byte
        $content .= pack('l', $uid); // 4 byte

        $result = fwrite($this->hander, $content);
        if (false === $result) {
            $return_array["info"] = "网络写入信息失败";
            return $return_array;
        }
        $status = stream_get_meta_data($this->hander);

        if (!$status['timed_out']) {
            $status = $this->unPackHead();
            $this->hander = $result['hander'];
            if (0 === $status) {
                //int32_t uid
                //char 32 name
                //short roomid
                //roomid你可以忽略
                //当ret=0时是成功，非0失败
                //非0说明没有登录
                //success
//                $arr = unpack('c32', fread($this->hander, 32));//ascii编码
//                $userid = self::ascii2str($arr);
//                $arr1 = unpack('c32', fread($this->hander, 32));//ascii编码
//                $name = self::ascii2str($arr1);
//                $data['user_email'] = $userid;            //此值是用户的邮箱等账号，相当于t_user.uid
//                $data['user_name'] = addslashes($name);  //此值是用户昵称，相当于t_user.name
                $return_array["info"] = "success";
                $return_array["data"] = ["user_id" => $uid];
                $return_array["status"] = true;
            }
        }

        return $return_array;


    }

    private static function ascii2str($arr)
    {
        $count = count($arr);
        $str = '';
        for ($i = 0; $i < $count; $i++) {
            if ($arr[$i] != 0) {
                if ($arr[$i] < 0 || $arr[$i] > 127) {
                    $str .= chr($arr[$i]) . chr($arr[++$i]);
                } else {

                    $str .= chr($arr[$i]);
                }
            }
        }
        $str = iconv("gb2312", "UTF-8//IGNORE", $str);

        return $str;
    }


    private function packHead($cmd, $s)
    {

        $content = pack('L', $cmd); // uint_32_t cmd

        $content .= pack('l', 0);        //  int64_t cmdid
        $content .= pack('l', 0);

        //这里我要用uuid 转化成16个字节,然后 a16,打包发给服务器端
        //"%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x"
        if (!function_exists("uuid_parse")) {
            throw new ExceptionExt("uuid_parse 不存在!");
        }

        $sid = uuid_parse($s); // char[16]
        $content .= pack('a16', $sid);


        $content .= pack('c', 1); //need resp
        $content .= pack('c', 0); //byte align

        return $content;
    }


    protected function unPackHead()
    {
        fread($this->hander, 4);       //接收数据流长度值存放大小

        //HEAD begin 30 byte
        fread($this->hander, 4);        //4字节CMD
        fread($this->hander, 4);
        fread($this->hander, 4);    //8字节CMDID
        fread($this->hander, 16);     //16字节UUID
        fread($this->hander, 1);
        fread($this->hander, 1);
        //HEAD end

        fread($this->hander, 4);  //4字节广播
        $arr = unpack('l', fread($this->hander, 4));  //4字节status状态，0成功，非0失败
        $ret = $arr[1];  //0：成功
        return $ret;
    }

}