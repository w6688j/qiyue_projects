<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/25
 * Time: 17:36
 * 调用支付接口
 */
namespace app\module\pay\controller;

use app\module\pay\service\MixedPayService;
use atphp\Controller;
use atphp\Request;
use atphp\util\JsonUtil;



class  AlipayController extends Controller
{
    //支付宝网页支付-- 混合扫码用的是这个---网页支付
    public function alipay_web()
    {
        $return_array = $this->getParam(true);
        if (!$return_array["status"]) {
            echo JsonUtil::encode($return_array);
            exit;
        }
        $param_data = $return_array["data"];


        $total_fee = $param_data["total_fee"] / 100;//转换成元


        //支付宝支付请求
        $request = [
            'productCode' => 'QUICK_WAP_WAY',
            'body' => $param_data["s"],
            'subject' => "pp游戏账单支付",
            'out_trade_no' => $param_data["trade_no"],
            'timeout_express' => '30m',
            'total_amount' => $total_fee,
        ];

        $alipayService = new \app\service\AlipayService();
        $alipayService->webPay($request);
    }

    /**
     *
     * @param bool $is_sign 默认不需要验证签名,
     * @return array
     */
    private function getParam($is_sign = false)
    {
        $type = Request::getInteger("type");
        $trade_no = Request::getString("trade_no");
        $total_fee = Request::getInteger("total_fee");
        $s = Request::getString("s");

        $return_arr = [
            "status" => false,
            "msg" => '缺少必要参数',
            "data" => [],
        ];
        //这里再验证一遍
        if (!$trade_no || !$type || !$total_fee || !$s) {
            return $return_arr;
        }

        $data = [
            "type" => $type,
            "trade_no" => $trade_no,
            "total_fee" => $total_fee,
            "s" => $s,
        ];

        //扫码不需要验证签名
        if ($is_sign) {
            $request_sign = Request::getString("sign");

            $mixedPayService = new MixedPayService();
            $sign = $mixedPayService->getSign($data);

            if ($request_sign !== $sign) {
                $return_arr = [
                    "status" => 0,
                    "msg" => '签名不正确',
                ];
                return $return_arr;
            }
        }


        $return_arr["status"] = true;
        $return_arr["msg"] = "ok";
        $return_arr["data"] = $data;

        return $return_arr;


    }


}