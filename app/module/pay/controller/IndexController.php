<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/1
 * Time: 10:26
 */

namespace app\module\pay\controller;


use app\module\pay\service\HeePayService;
use app\module\pay\service\MixedPayService;
use app\module\pay\service\WeiXinPayService;
use atphp\Controller;
use atphp\Request;
use atphp\util\JsonUtil;


class IndexController extends Controller
{

    /**
     * type   注: 1微信扫码,2支付宝扫码,,3网银,4混合支付宝/微信扫码
     * trade_no : 订单号
     * subject : 标题
     * total_fee : 金额(单位分 int)
     * pay_code : 银行编码 001,002,010
     * s: 序列号
     * 测试连接: http://pay.pp158.cn:8082/Index/index/?type=1&trade_no=wx_order_1111111&total_fee=1&s=6D82324B-1CF1-4DC9-B457-9F8AD5B54B31
     */
    public function index()
    {
        $type = Request::getInteger("type");
        $trade_no = Request::getString("trade_no");
        $total_fee = Request::getInteger("total_fee");
        $pay_code = Request::getString("pay_code");
        $s = Request::getString("s");

        $return_arr = [
            "status" => false,
            "msg" => '缺少必要参数',
        ];

        if (!$trade_no || !$type || !$total_fee || !$s) {
            echo JsonUtil::encode($return_arr);
            exit;
        }
        if ($type == 3 && !$pay_code) {
            $return_arr["msg"] = "网银需要银行编码";
            echo JsonUtil::encode($return_arr);
            exit;
        }

        $request_data = $_GET;

        switch ($type) {
            case 1:
                //微信扫码
                $weixinPayService = new WeiXinPayService();
                $weixinPayService->wx_qrcode($request_data);
                break;
            case 2:
                //支付宝扫码
                $alipayService = new \app\module\pay\service\AlipayService();
                $alipayService->alipay_qrcode($request_data);

                break;
            case 3:
                //网银
                $heepayService = new HeePayService();
                $heepayService->underOrder($request_data);

                break;
            case 4:
                //支付宝/微信混合扫码

                $mixedPayService = new MixedPayService();
                $mixedPayService->mixed_qrcode($request_data);

                break;

            default:
                $return_arr["msg"] = "不存在此类型";
                echo JsonUtil::encode($return_arr);
                exit;
        }

    }
}