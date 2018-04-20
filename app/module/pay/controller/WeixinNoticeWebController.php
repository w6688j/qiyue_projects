<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/25
 * Time: 17:31
 * 微信支付通知
 */

namespace app\module\pay\controller;


use app\core\QiyueLog;
use app\model\oracle\LogPayOracle;
use app\model\socket\PaySocket;
use atphp\Controller;
use atphp\exception\ExceptionExt;
use atphp\util\XmlUtil;
use service\WeiXinPayWebService;

class WeixinNoticeWebController extends Controller
{
    /****
     * 回调信息
     * {
     * "appid": "wx95a9edfdca7a948d",
     * "attach": "1",
     * "bank_type": "CFT",
     * "cash_fee": "1",
     * "fee_type": "CNY",
     * "is_subscribe": "N",
     * "mch_id": "1401776502",
     * "nonce_str": "y1tdyjpyw2had6o4mu9h1ss0i4nmd7tr",
     * "openid": "o8Mbyvm76ImMpPzqiNG_-4JWpCmg",
     * "out_trade_no": "1112322",
     * "result_code": "SUCCESS",
     * "return_code": "SUCCESS",
     * "sign": "4E1D65A500E75B7959E5231369A5C22E",
     * "time_end": "20170308163834",
     * "total_fee": "1",
     * "trade_type": "NATIVE",
     * "transaction_id": "4005412001201703082670107418"
     * }
     * $pay_status=> c++服务端返回的状态-1链接服务失败,-2传输数据失败,-3 php验证不通过,0成功1未找到订单2金额无效3更新订单失败4更新金额记录失败,5更新VIP失败,6金额不匹配,10加入奖券失败,20加入喇叭失败
     * //v_type 1支付宝,2微信,3网银
     */

    public function notify_url()
    {
        $xml = file_get_contents("php://input");


        $result = XmlUtil::decode($xml); //返回数组信息
        $wx_service = new WeiXinPayWebService();
        $pay_status = -3;//验证失败
        $trade_status_code = TRADE_FAIL;

        $result_msg = '';
        //返回的信息
        $return_array = array(
            "return_code" => "FAIL",
            "return_msg" => "FAIL"
        );
        //验证秘钥
        if ($wx_service->checkNoticeSign($result)) {
            if (isset($result['result_code']) && isset($result['return_code']) && $result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS') {
                //这里支付成功
                $s = $result['attach'];
                $trade_status_code = TRADE_SUCCESS;//成功
                $paySocket = new PaySocket();
                $pay_status = $paySocket->postPayMsg(strtolower($result["out_trade_no"]), $result["transaction_id"], $result['total_fee'], $s);
                $paySocket->close();

                if ($pay_status === 0) {
                    $result_msg = "成功";
                    $return_array = array(
                        "return_code" => "SUCCESS",
                        "return_msg" => "OK"
                    );
                } else {
                    $result_msg = "c++服务端通信验证失败 pay_status = {$pay_status}";
                }
            } else {
                //支付失败
                $result_msg = '支付失败';
            }
        } else {
            //秘钥验证失败
            $result_msg = '秘钥验证失败';
        }
        //不是成功状态写入日志
        //  if ($return_array["return_code"] != "SUCCESS") {
        //LogUtil::write("微信支付 {$result_msg}:回调信息是:" . var_export($result, true), LogUtil::INFO, LogUtil::FILE, $this->path_name);
        //}
        QiyueLog::info("微信支付 {$result_msg}:回调信息是:" . var_export($result, true),"wxpay_app_callback");

        //表日志
        if ($trade_status_code == TRADE_SUCCESS) {
            $this->writeLog($result, $trade_status_code, $pay_status);
        }

        ob_clean();
        echo  XmlUtil::encodeSimple($return_array);


    }

    private function writeLog($data, $trade_status_code, $pay_status)
    {
        $pay_log_result = false;
        try {
            $logPayOracleModel = new LogPayOracle("write");
            $pay_log_result = $logPayOracleModel->f_add_pay_log($data['transaction_id'], strtotime($data['time_end']), $data['out_trade_no'], $trade_status_code, $data['total_fee'], $pay_status, PAY_TYPE_WEIXIN, 0);

        } catch (ExceptionExt $e) {
            //不处理异常了,会直接写入到系统的db_error日志中,
        }
        if (!$pay_log_result) {
            $pay_log_msg = "写入日志表失败:支付回调信息是:" . var_export($data, true);
            QiyueLog::error($pay_log_msg,"wxpay_app_callback_error");
        }
    }
}