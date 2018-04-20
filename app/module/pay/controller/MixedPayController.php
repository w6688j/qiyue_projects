<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/18
 * Time: 11:08
 *
 * 混合扫码控制器
 */

namespace app\module\pay\controller;

use app\module\pay\service\MixedPayService;
use atphp\Controller;
use atphp\Request;
use atphp\util\JsonUtil;

class MixedPayController extends Controller
{

    //混合扫码控制跳转
    public function payCtrl()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        // LogUtil::write("手机头信息:" . $user_agent, LogUtil::INFO, LogUtil::FILE, 'agent/' . DateUtil::format(time(), "Ymd") . "/agent.log");

        //-- 安卓
        //QQ : vivo X6D Build/LMY47I; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.49 Mobile MQQBrowser/6.2 TBS/043507 Safari/537.36 V1_AND_SQ_7.1.8_718_YYB_D PA QQ/7.1.8.3240 NetType/4G WebP/0.3.0 Pixel/1080

        //浏览器: Mozilla/5.0 (Linux; Android 5.1; vivo X6D Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/38.0.2125.102 Mobile Safari/537.36 VivoBrowser/5.2.3

        //微信 : Mozilla/5.0 (Linux; Android 5.1; vivo X6D Build/LMY47I; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.49 Mobile MQQBrowser/6.2 TBS/043507 Safari/537.36 MicroMessenger/6.5.13.1100 NetType/4G Language/zh_CN

        //支付宝:Mozilla/5.0 (Linux; U; Android 5.1; zh-CN; vivo X6D Build/LMY47I) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 UCBrowser/11.6.4.950 UWS/2.11.0.16 Mobile Safari/537.36 UCBS/2.11.0.16 Nebula AlipayDefined(nt:4G,ws:360|0|3.0) AliApp(AP/10.1.0.090418) AlipayClient/10.1.0.090418 Language/zh-Hans useStatusBar/true


        //iphone--
        //QQ :Mozilla/5.0 (iPhone; CPU iPhone OS 10_2_1 like Mac OS X) AppleWebKit/602.4.6 (KHTML, like Gecko) Mobile/14D27 QQ/6.6.9.412 V1_IPH_SQ_6.6.9_1_APP_A Pixel/1080 Core/UIWebView NetType/WIFI QBWebViewType/1

        //weixin : Mozilla/5.0 (iPhone; CPU iPhone OS 10_2_1 like Mac OS X) AppleWebKit/602.4.6 (KHTML, like Gecko) Mobile/14D27 MicroMessenger/6.5.5 NetType/WIFI Language/zh_CN


        //验证sign是否正确
        $type = Request::getInteger("type");
        $trade_no = Request::getString("trade_no");
        $total_fee = Request::getInteger("total_fee");
        $s = Request::getString("s");
        $request_sign = Request::getString("sign");
        $data = [
            "type" => $type,
            "trade_no" => $trade_no,
            "total_fee" => $total_fee,
            "s" => $s,

        ];

        $mixedPayService = new MixedPayService();
        $sign = $mixedPayService->getSign($data);

        if ($request_sign !== $sign) {
            $return_arr = [
                "status" => 0,
                "msg" => '签名不正确',
            ];
            echo JsonUtil::encode($return_arr);
            exit;
        }

        $data["sign"] = $request_sign;
        $param = http_build_query($data);


        $weixin_inside_url = "/WeiXinPay/wx_inside_pay?" . $param;
        $weixin_outside_url = "/WeiXinPay/wx_outside_pay?" . $param;
        $alipay_url = "/Alipay/alipay_web?" . $param;


        if (stripos($user_agent, "MicroMessenger")) {
            Header("location:" . $weixin_inside_url);

        } else if (stripos($user_agent, "AlipayClient")) {
            //echo "支付宝";
            Header("location:" . $alipay_url);

        } else {
            //其他--调用的是微信h5外部唤起微信支付接口,和 支付宝网页web支付接口
            $this->assign([
                "trade_no" => $data["trade_no"],
                "total_fee" => sprintf("%.2f", $data["total_fee"]),
                "weixin_url" => $weixin_outside_url,
                "alipay_url" => $alipay_url,
            ]);
            $this->display();
        }
    }

}