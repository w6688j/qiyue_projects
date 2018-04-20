<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 13:57
 */

namespace app\module\pay\service;


use atphp\Config;
use atphp\util\JsonUtil;
use service\WeiXinPayWebService;

class WeiXinPayService
{
    //微信二维码支付
    public static function wx_qrcode(array $request)
    {
        $data = [
            'body' => 'pp游戏账单支付',
            'detail' => '',
            'attach' => $request["s"],
            'out_trade_no' => $request["trade_no"],//订单号
            'total_fee' => $request["total_fee"],//分
            'time_start' => date("YmdHis", time()),//开始时间
            'time_expire' => date("YmdHis", time() + 1800),//过期时间
            'trade_type' => 'NATIVE',
            'openid' => '',
            'notify_url' => Config::get('weixin_web')["notify_url"],
            'product_id' => $request["trade_no"],//trade_type=NATIVE时（即扫码支付），此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
        ];

        $wx_service = new WeiXinPayWebService();
        $result = $wx_service->pay($data);
//        print_r($request);exit;

        if ($result['status']) {
            require_once APP_PATH . "lib/QRcode/phpqrcode.php";
            \QRcode::png($result['code_url']);
        } else {
            // var_export($result);
            $return_arr["status"] = 0;
            $return_arr["msg"] = "请求二维码失败";
            echo JsonUtil::encode($return_arr);
            exit;
        }
    }
}