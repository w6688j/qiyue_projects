<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/14
 * Time: 10:03
 */

namespace app\model\socket;

use app\core\BaseSocketModel;
use atphp\Config;
use atphp\exception\ExceptionExt;


class PaySocket extends BaseSocketModel
{
    function __construct()
    {
        $config = Config::get("socket")["user_auth"];

        parent::__construct($config["host"], $config["port"], 5);

    }
    /**
     * 支付成功,调用该函数,由C++判断订单是否合法,及更新订单
     * @param $no
     * @param $tradeno
     * @param int $s1
     * @param int $s2
     * @param int $s3
     * @param int $s4
     * @return int  c++服务端返回的状态-1链接服务失败,-2传输数据失败,-3 php验证不通过,0成功1未找到订单2金额无效3更新订单失败4更新金额记录失败,5更新VIP失败,6金额不匹配,10加入奖券失败,20加入喇叭失败
     */
    function postPayMsg($no, $tradeno, $money, $s)
    {
        $length = 4 + 30 + 32 + 32 + 4;
        $content = pack('L', $length);  // 4 byte
        $content .= $this->packHead(443, $s); // 30byte

        //only ascii char
        //$no = iconv('utf-8','gb2312',$no);
        $content .= pack('a32', $no);
        $content .= pack('a32', $tradeno);
        $content .= pack('L', $money);

        $result = fwrite($this->hander, $content);//fputs, $length

        if (!$result) {
            return -2;
        }

        $result = 0;
        $status = stream_get_meta_data($this->hander);
        if (!$status['timed_out']) {
            $result = $this->unPackHead();
        }
        return $result;
    }


    /**
     * https://pecl.php.net/package/uuid uuid扩展
     * 这里需要定义一个头部文件---c++后台服务端需要这个
     * @param $cmd
     * @param int $s 这个是回调过来的UUID,这个需要打包传到服务端,然后解析
     * @return string
     */
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

    /**
     * 这里解包头部文件 ---c++后台服务端
     * @param $conn
     * @return int
     */
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