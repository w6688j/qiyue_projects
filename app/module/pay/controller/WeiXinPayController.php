<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/28
 * Time: 15:10
 * 微信支付,下单控制器
 */

namespace app\module\pay\controller;

use app\module\pay\service\MixedPayService;
use app\service\WeiXinOpenIdService;
use atphp\Config;
use atphp\Controller;
use atphp\Request;
use atphp\util\JsonUtil;
use service\WeiXinPayWebService;


class WeiXinPayController extends Controller
{

    //微信内H5调起支付
    public function wx_inside_pay()
    {

        $return_array = $this->getParam(true);
        if (!$return_array["status"]) {
            echo JsonUtil::encode($return_array);
            exit;
        }
        //获取的参数数组
        $param_data = $return_array["data"];

        //openID --直接测试--正式线上,需要注释掉
        //$openid = 'oFqowwOHE0iVsPPH37SPfrU-sDWE';

        $weiXinOpenIDService = new WeiXinOpenIdService();
        $openid = $weiXinOpenIDService->getOpenId();

        $return_arr = [
            "msg" => "出错了",
            "status" => false,
        ];
        $js_request = [];

        if (!$openid) {
            $return_arr["msg"] = "获取用户信息失败";
        } else {
            //获取了openID
            $data = [
                'body' => 'pp游戏账单支付',
                'detail' => '',
                'attach' => $param_data["s"],
                'out_trade_no' => $param_data["trade_no"],//订单号
                'total_fee' => $param_data["total_fee"],//分
                'time_start' => date("YmdHis", time()),//开始时间
                'time_expire' => date("YmdHis", time() + 1800),//过期时间
                'trade_type' => 'JSAPI',
                'openid' => $openid,
                'notify_url' => Config::get('weixin_web')["notify_url"],
                'product_id' => $param_data["trade_no"],//trade_type=NATIVE时（即扫码支付），此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
            ];

            $wx_service = new WeiXinPayWebService();
            //下单
            $result = $wx_service->pay($data);

            if ($result["status"]) {
                $timeStamp = time();
                $js_request = [
                    "appId" => Config::get("weixin_web")["appid"],
                    "timeStamp" => "$timeStamp",
                    "nonceStr" => $wx_service->getNonceStr(),//随机串
                    "package" => "prepay_id={$result['prepay_id']}",
                    "signType" => "MD5",
                ];
                $js_request["paySign"] = $wx_service->makeSign($js_request);

                $return_arr["msg"] = "下单成功,唤起支付中!";
                $return_arr["status"] = true;

            } else {
                //失败
                $return_arr["msg"] = "下单失败";
            }


        }


        $this->assign([
            "js_request" => $js_request,
            "return_arr" => $return_arr,
        ]);


        $this->display();


    }

    //微信外h5唤起微信支付----还在审核中,,没办法了
    public function wx_outside_pay()
    {
        $data = [
            'body' => 'pp游戏账单支付',
            'detail' => '',
            'attach' => "阿萨啊",
            'out_trade_no' => time(),//订单号
            'total_fee' => 1,//分
            'time_start' => date("YmdHis", time()),//开始时间
            'time_expire' => date("YmdHis", time() + 1800),//过期时间
            'trade_type' => 'MWEB',
            'openid' => "",
            'notify_url' => Config::get('weixin_web')["notify_url"],
            'product_id' => time(),//trade_type=NATIVE时（即扫码支付），此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
        ];

        $wx_service = new WeiXinPayWebService();
        //下单
        $result = $wx_service->pay($data);

        dump($result);

    }

    /**
     * 获取参数
     * @return array
     */
    private function getParam($is_sign = false)
    {
        $type = Request::getInteger("type");
        $trade_no = Request::getString("trade_no");
        $total_fee = Request::getInteger("total_fee");
        $s = Request::getString("s");

        $return_arr = [
            "status" => 0,
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