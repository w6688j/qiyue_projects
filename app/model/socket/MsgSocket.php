<?php
/**
 * For: .....
 * User: caostian
 * Date: 2017/10/19
 * Time: 15:27
 */

namespace app\model\socket;

use app\core\BaseSocketModel;
use atphp\Config;
use atphp\exception\ExceptionExt;

class MsgSocket extends BaseSocketModel
{
    public function __construct()
    {
        $config = Config::get("socket")["user_auth"];
        parent::__construct($config["host"], $config["port"], 5);
    }


    //金币数量变化通知
    public function goldChange($uid, $gold)
    {
        $content = pack('L', 4 + 30 + 16);  // 4 byte
        $content .= $this->packhead(479); // 30 byte
        $content .= pack('L', $uid);
        $content .= pack('l', 0);
        $content .= pack('l', $gold);
        $content .= pack('l', 0);

        $result = fwrite($this->hander, $content);
        if (false === $result) {
            return false;
        }
        return true;
    }

    //背包数量变化通知
    public function bagChange($uid, $number, $pid,$sid)
    {
        $content = pack('L', 4 + 30 + 4 + 4 + 4);  // 4 byte
        $content .= $this->packHeadBySid(427,$sid); // 30 byte
        $content .= pack('L', $uid);
        $content .= pack('l', $pid);
        $content .= pack('l', $number);
//        $content .= pack('l', 0);

        $result = fwrite($this->hander, $content);
        if (false === $result) {
            return false;
        }

        return true;
    }





    /**
     * VIP变化通知
     * @param  $uid 玩家ID
     * @param  $vip VIP等级，整数0到5
     * @param  $validTime VIP有效时间再延长多少秒，若为0表示设置为过期，不可大于2145888000
     * @return bool
     */
    public function vipChange($uid, $vip, $validTime)
    {

        $content = pack('L', 4 + 30 + 12);  // 4 byte
        $content .= $this->packhead(429); // 30 byte
        $content .= pack('L', $uid);
        $content .= pack('l', $vip);
        $content .= pack('l', $validTime);

        $result = fwrite($this->hander, $content);
        if (false === $result) {
            return false;
        }
        $status = stream_get_meta_data($this->hander);
        if (!$status['timed_out']) {
            $result = $this->unPackHead();
            if ($result === 0) {
                return true;
            }
        }
        return false;
    }


    /**
     * 请求时发送头部，固定 30 字节
     * 头部信息会原样返回
     */
    public function packHead($cmd, $s1 = 0, $s2 = 0, $s3 = 0, $s4 = 0)
    {
        $content = pack('L', $cmd);     // uint_32_t cmd
        $content .= pack('l', 0);        //  int64_t cmdid
        $content .= pack('l', 0);
        $content .= pack('l', $s1);     //UUID
        $content .= pack('l', $s2);
        $content .= pack('l', $s3);
        $content .= pack('l', $s4);

        $content .= pack('c', 1);       //need resp  如果不需要返回值，值置为0，比如各种通知即使失败也不能影响对应的主要功能
        $content .= pack('c', 0);       //byte align

        return $content;
    }

    /**
     * https://pecl.php.net/package/uuid uuid扩展
     * 这里需要定义一个头部文件---c++后台服务端需要这个
     * @param $cmd
     * @param int $s 这个是回调过来的UUID,这个需要打包传到服务端,然后解析
     * @return string
     */
    private function packHeadBySid($cmd, $s)
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


    /**
     * 处理返回头信息
     */
    public function unPackHead()
    {
        unpack('l', fread($this->hander, 4));       //接收数据流长度值存放大小
        //HEAD 30 byte
        unpack('l', fread($this->hander, 4));        //4字节CMD
        unpack('l', fread($this->hander, 4));//$cmdid1
        unpack('l', fread($this->hander, 4));    //8字节$cmdid2
        fread($this->hander, 16);     //16字节UUID

        unpack('c', fread($this->hander, 1));   //1字节 need resp
        unpack('c', fread($this->hander, 1));   //1字节 byte align
        unpack('l', fread($this->hander, 4));  //4字节广播

        $arr = unpack('l', fread($this->hander, 4));  //4字节status状态，0成功，非0失败
        $ret = $arr[1];  //0：成功       28：1.读取数据包出错，2.数据库执行出错ORACLE报错

        return $ret;
    }

}