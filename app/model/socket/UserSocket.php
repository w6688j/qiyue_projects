<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 15:49
 */

namespace app\model\socket;

use app\core\BaseSocketModel;
use atphp\Config;

class UserSocket extends BaseSocketModel
{
    public function __construct()
    {
        $config = Config::get("socket")["user_auth"];
        parent::__construct($config["host"], $config["port"], 5);

    }

    /**
     * 标识位变化通知
     */
    public function flagChange($uid, $flag, $winLoseVal)
    {

        $return_array = [
            "status" => false,
            "msg" => "操作失败",
        ];
        $content = pack('L', 4 + 30 + 8 + 4);  // 4 byte
        $content .= $this->packHead(431); // 30 byte
        $content .= pack('L', $uid);
        $content .= pack('l', $flag);
        $content .= pack('l', $winLoseVal);

        $result = fwrite($this->hander, $content);
        if (false === $result) {
            $return_array["msg"] = "文件读写错误";
            return $return_array;
        }

        $status = stream_get_meta_data($this->hander);

        if (!$status['timed_out']) {
            $result = $this->unPackHead();
            if ($result === 0) {
                $return_array["status"] = true;
                $return_array["msg"] = "操作成功";
                return $return_array;
            }
        }
        return $return_array;
    }


    /**
     * 请求时发送头部，固定 30 字节
     * 头部信息会原样返回
     * @param  integer $cmd 指令
     * @param  int $s1 UUID
     * @param  $s2
     * @param  $s3
     * @param  $s4
     * @return string $content
     */
    private function packHead($cmd, $s1 = 0, $s2 = 0, $s3 = 0, $s4 = 0)
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
     * 处理返回头信息  返回：status：0成功，1失败
     */
    private function unPackHead()
    {
        unpack('l', fread($this->hander, 4));       //接收数据流长度值存放大小
        //HEAD 30 byte
        unpack('l', fread($this->hander, 4));        //4字节CMD
        unpack('l', fread($this->hander, 4));
        unpack('l', fread($this->hander, 4));    //8字节CMDID
        fread($this->hander, 16);     //16字节UUID

        unpack('c', fread($this->hander, 1));   //1字节 need resp
        unpack('c', fread($this->hander, 1));   //1字节 byte align

        unpack('l', fread($this->hander, 4));  //4字节广播

        $arr = unpack('l', fread($this->hander, 4));  //4字节status状态，0成功，非0失败
        $ret = $arr[1];  //0：成功 28：1.读取数据包出错，2.数据库执行出错ORACLE报错

        return $ret;
    }


}