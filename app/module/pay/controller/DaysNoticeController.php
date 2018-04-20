<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/25
 * Time: 10:07
 * 支付宝通知
 */

namespace app\module\pay\controller;

use app\core\QiyueLog;
use app\model\oracle\LogPayOracle;
use app\model\socket\PaySocket;
use app\service\AlipayService;
use app\service\PayDetailService;
use atphp\Controller;
use atphp\exception\ExceptionExt;
use atphp\util\DateUtil;
use atphp\util\LogUtil;

class DaysNoticeController extends Controller
{
    /*****
     * 返回信息
     * array (
     * 'gmt_create' => '2017-08-25 10:52:20',
     * 'charset' => 'utf-8',
     * 'seller_email' => '',
     * 'open_id' => '',
     * 'subject' => 'woshishui',
     * 'sign' => '',
     * 'body' => 'test_cst',
     * 'buyer_id' => '',
     * 'invoice_amount' => '0.01',
     * 'notify_id' => '',
     * 'fund_bill_list' => '[{"amount":"0.01","fundChannel":"PCREDIT"}]',
     * 'notify_type' => 'trade_status_sync',
     * 'trade_status' => 'TRADE_SUCCESS',
     * 'receipt_amount' => '0.01',
     * 'app_id' => '',
     * 'buyer_pay_amount' => '0.01',
     * 'sign_type' => 'RSA2',
     * 'seller_id' => '',
     * 'gmt_payment' => '2017-08-25 10:52:23',
     * 'notify_time' => '2017-08-25 10:52:24',
     * 'version' => '1.0',
     * 'out_trade_no' => '1503629531',
     * 'total_amount' => '0.01',
     * 'trade_no' => '2017082521001004590206630559',
     * 'auth_app_id' => '',
     * 'buyer_LogUtilon_id' => '',
     * 'point_amount' => '0.00',
     * )
     * $pay_status=> c++服务端返回的状态-1链接服务失败,-2传输数据失败,-3 php验证不通过,0成功1未找到订单2金额无效3更新订单失败4更新金额记录失败,5更新VIP失败,6金额不匹配,10加入奖券失败,20加入喇叭失败
     */
    //支付宝通知
    public function notify_url()
    {
        $request_data = $_POST;

        if (isset($request_data['respCode']) && '0000' == $request_data['respCode']) {
            $paySocket = new PaySocket();
            $pay_status = $paySocket->postPayMsg($request_data["agent_bill_id"], $request_data["jnet_bill_no"], $request_data["tranAmt"] * 100, $request_data["remark"]);
            $paySocket->close();
            $trade_status_code = TRADE_SUCCESS;//成功
        } else {

        }


//         echo "success";
    }

    private function writeLog($data, $trade_status_code, $pay_status)
    {
        $pay_log_result = false;
        try {
            $logPayOracleModel = new LogPayOracle("write");
            $pay_log_result = $logPayOracleModel->f_add_pay_log($data['trade_no'], strtotime($data['notify_time']), $data['out_trade_no'], $trade_status_code, $data['receipt_amount'] * 100, $pay_status, PAY_TYPE_ALIPAY, 0);

        } catch (ExceptionExt $e) {
            //不处理异常了,会直接写入到系统的db_error日志中,
        }
        if (!$pay_log_result) {
            $pay_log_msg = "写入日志表失败:支付回调信息是:" . var_export($data, true);
            //LogUtil::write($pay_log_msg, LogUtil::INFO, LogUtil::FILE, $this->path_name);
            QiyueLog::error($pay_log_msg, "alipay_callback_error");
        }
    }



    /***
     * 返回信息
     * array (
     * 's' => 'AlipayNotice/return_url',
     * 'total_amount' => '0.01',
     * 'timestamp' => '2017-08-25 14:54:30',
     * 'sign' => '',
     * 'trade_no' => '2017082521001004590207128641',
     * 'sign_type' => 'RSA2',
     * 'auth_app_id' => '',
     * 'charset' => 'UTF-8',
     * 'seller_id' => '2088221931936835',
     * 'method' => 'alipay.trade.wap.pay.return',
     * 'app_id' => '',
     * 'out_trade_no' => '1503644047',
     * 'version' => '1.0',
     * )
     */

    //支付宝同步跳转通知
    public function return_url()
    {
        $request_data = $_GET;
        //不去掉这个,验证不成功
        $request_data["s"] = null;

        $alipay_service = new AlipayService();
        $result = $alipay_service->checkNoticeSign($request_data);

        $return_arr = [
            "status" => false,
            "msg" => "支付失败",
            "data" => [],
        ];
        if ($result) {
            //验证订单号是否支付成功
            $pay_info = PayDetailService::getOneByTradeNo($request_data["out_trade_no"]);

            if (isset($pay_info) && $pay_info["status"] != 0) {
                $return_arr["status"] = true;
                $return_arr["total_amount"] = $request_data["total_amount"];
                $return_arr["msg"]="success";
            }
        } else {
            //秘钥验证失败
            $result_arr["msg"] = '秘钥验证失败';
        }

        $this->assign("return_arr", $return_arr);
        $this->display();

    }
}