<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 14:00
 * 混合扫码
 *
 */

namespace app\module\pay\service;


use atphp\Config;
use atphp\Request;

class MixedPayService
{
    //支付宝微信混合扫码--生成二维码
    public static function mixed_qrcode(array $request)
    {

        $data = [
            "type" => $request["type"],
            "trade_no" => $request["trade_no"],
            "total_fee" => $request["total_fee"],
            "s" => $request["s"],

        ];
        //这里加一个秘钥,防止恶意用户,扫码之后,改参数,那么我就尴尬了
        //加密key
        $sign = self::getSign($data);
        $data["sign"] = $sign;

        $now_url = Request::isSsl() ? "https://" : "http://" . $_SERVER['HTTP_HOST'];
        //扫码支付的地址
        $url = $now_url . "/MixedPay/payCtrl/?" . http_build_query($data);

        require_once APP_PATH . "lib/QRcode/phpqrcode.php";
//        \QRcode::png($url);
        \QRcode::png($url,false,QR_ECLEVEL_L,2,3);

    }


    /**
     * 混合扫码加密参数
     * @param $data
     * @return string
     */
    public static function getSign($data)
    {
        $data["key"] = Config::get("mixed_pay")["key"];

        $sign = http_build_query($data);
        return md5($sign);
    }
}