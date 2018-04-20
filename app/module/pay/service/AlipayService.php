<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 13:53
 */

namespace app\module\pay\service;


use atphp\util\JsonUtil;

class AlipayService
{

    //支付宝二维码支付
    public static function alipay_qrcode(array $request)
    {
        $total_fee = $request["total_fee"] / 100;//转换成元
        $request = [
            'body' => $request["s"],
            'subject' => "pp游戏账单支付",
            'out_trade_no' => $request["trade_no"],
            'timeout_express' => '30m',
            'total_amount' => $total_fee,
        ];

        $alipayService = new \app\service\AlipayService();
        $data = $alipayService->qrcodePay($request);

        if ($data['status']) {
            require_once APP_PATH . "lib/QRcode/phpqrcode.php";
            \QRcode::png($data['qr_code']);
        } else {
            //var_export($data);
            $return_arr["status"] = 0;
            $return_arr["msg"] = "请求二维码失败";
            echo JsonUtil::encode($return_arr);
            exit;
        }

    }


}