<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/14
 * Time: 13:18
 * 汇付宝通知回调
 */

namespace app\module\pay\controller;


use app\core\QiyueLog;
use app\model\oracle\LogPayOracle;
use app\model\socket\PaySocket;
use atphp\Config;
use atphp\Controller;
use atphp\exception\ExceptionExt;
use atphp\util\DateUtil;
use atphp\util\LogUtil;



class HeePayNoticeController extends Controller
{

    /*
 * 汇付宝
 * 异步通知页面

 * result	必填	支付结果，1=成功 其它为未知
 * agent_id	必填	商户编号 如1234567
 * jnet_bill_no	必填	汇付宝交易号(订单号)
 * agent_bill_id	必填	商户系统内部的定单号
 * pay_type	必填	20 网银类型
 * pay_amt	必填	订单实际支付金额(注意：此金额是用户的实付金额)
 * remark	必填	商家数据包，原样返回
 * pay_message	选填	支付结果信息，支付成功时为空
 * sign	必填	签名结果,与支付接口签名方式一致
 * $pay_status=> c++服务端返回的状态-1链接服务失败,-2传输数据失败,-3 php验证不通过,0成功1未找到订单2金额无效3更新订单失败4更新金额记录失败,5更新VIP失败,6金额不匹配,10加入奖券失败,20加入喇叭失败
 * heepay_notify_url.php?result=1&agent_id=2070349&jnet_bill_no=H1708305159750AO&agent_bill_id=35875e66503945b7bc95810fe9d896af&pay_type=20&pay_amt=20.00&remark=-149906978_859927483_194853273_-438556803&pay_message=&sign=149fd8361e42114c00030aa79f95ee92
     *
     *
     * {"result":"1","pay_message":"","agent_id":"2070349","jnet_bill_no":"H1708305159750AO","agent_bill_id":"35875e66503945b7bc95810fe9d896af","pay_type":"20","pay_amt":"20.00","remark":"-149906978_859927483_194853273_-438556803","sign":"149fd8361e42114c00030aa79f95ee92"}
 */
    public function notify_url()
    {

        $trade_status_code = TRADE_FAIL;

        $return_heepay_msg = "error";

        $pay_status = -3;

        $return_array = $this->getCheckParam(); //返回的信息
        $return_data = $return_array["data"]; //返回信息里面的data


        if ($return_array["status"]) {

            //秘钥验证成功
            if ($return_data["result"] === 1) {
                $paySocket = new PaySocket();
                $pay_status = $paySocket->postPayMsg($return_data["agent_bill_id"], $return_data["jnet_bill_no"], $return_data["pay_amt"] * 100, $return_data["remark"]);
                $paySocket->close();
                $trade_status_code = TRADE_SUCCESS;//成功

                if ($pay_status === 0) {
                    $return_heepay_msg = "ok";
                    $result_msg = "成功";
                } else {
                    $return_heepay_msg = "error";
                    $result_msg = "c++服务端通信验证失败";
                }
            } else {
                $result_msg = "支付结果--未知";
            }

        } else {
            $result_msg = '秘钥验证失败';
        }

        QiyueLog::info("heepay支付 {$result_msg},pay_status = {$pay_status} , 回调信息是:" . var_export($return_array, true),"heepay_callback");
        if ($trade_status_code == TRADE_SUCCESS) {
            $this->writeLog($return_data, $trade_status_code, $pay_status);
        }
        echo $return_heepay_msg;
    }

    private function writeLog($data, $trade_status_code, $pay_status)
    {
        $pay_log_result = false;
        try {
            $logPayOracleModel = new LogPayOracle("write");
            $pay_log_result = $logPayOracleModel->f_add_pay_log($data['jnet_bill_no'], time(), $data['agent_bill_id'], $trade_status_code, $data['pay_amt'] * 100, $pay_status, PAY_TYPE_HEEPAY, 0);

        } catch (ExceptionExt $e) {
            //不处理异常了,会直接写入到系统的db_error日志中,
        }
        if (!$pay_log_result) {
            $pay_log_msg = "写入日志表失败:支付回调信息是:" . var_export($data, true);
            QiyueLog::error($pay_log_msg,"heepay_callback_error");

        }
    }


    //同步跳转通知
    public function return_url()
    {
        $return_array = $this->getCheckParam(); //返回的信息
        $return_data = $return_array["data"]; //返回信息里面的data

        if ($return_array["status"]) {
            $return_array["status"] = false;
            if ($return_data["result"] === 1) {
                //这里是支付成功
                $return_array["status"] = true;
                $return_array["data"]["pay_amt"] = sprintf("%.2f", $return_array["data"]["pay_amt"]);
            }
        }else{
            $return_array["status"] = false;
        }

        $this->assign("return_array", $return_array);
        $this->display();
    }

    private function getCheckParam()
    {
        $request_data = $_GET;
        //不去掉这个,验证不成功
        unset($request_data["s"]);

        $result = isset($request_data['result']) ? intval($request_data['result']) : 0;
        $pay_message = isset($request_data['pay_message']) ? $request_data['pay_message'] : '';
        $agent_id = isset($request_data['agent_id']) ? $request_data['agent_id'] : '';
        $jnet_bill_no = isset($request_data['jnet_bill_no']) ? $request_data['jnet_bill_no'] : '';
        $agent_bill_id = isset($request_data['agent_bill_id']) ? $request_data['agent_bill_id'] : '';
        $pay_type = isset($request_data['pay_type']) ? $request_data['pay_type'] : 0;
        $pay_amt = isset($request_data['pay_amt']) ? $request_data['pay_amt'] : 0; //这里面接受的是元,我要把它转化为分
        $remark = isset($request_data['remark']) ? $request_data['remark'] : '';
        $returnSign = isset($request_data['sign']) ? $request_data['sign'] : '';


        //商户的KEY
        $key = Config::get("heepay")['sign_key'];
        $remark = urldecode($remark);

//        $signStr = '';
//        $signStr = $signStr . 'result=' . $result;
//        $signStr = $signStr . '&agent_id=' . $agent_id;
//        $signStr = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
//        $signStr = $signStr . '&agent_bill_id=' . $agent_bill_id;
//        $signStr = $signStr . '&pay_type=' . $pay_type;
//        $signStr = $signStr . '&pay_amt=' . $pay_amt;
//        $signStr = $signStr . '&remark=' . $remark;
//        $signStr = $signStr . '&key=' . $key;
//
//        echo $signStr;

        $data_array = [
            "result" => $result,
            "agent_id" => $agent_id,
            "jnet_bill_no" => $jnet_bill_no,
            "agent_bill_id" => $agent_bill_id,
            "pay_type" => $pay_type,
            "pay_amt" => $pay_amt,
            "remark" => $remark,
            "key" => $key,
        ];

        $param = http_build_query($data_array);

        $sign = md5($param);
        $return_array = [
            "status" => false,
            "msg" => "秘钥错误",
            "data" => [],
        ];

        $data_array["sign"] = $returnSign;
        $data_array["pay_message"] = $pay_message;
        $return_array["data"] = $data_array;

        if ($returnSign === $sign) {
            $return_array["msg"] = "秘钥正确";
            $return_array["status"] = true;
        }

        return $return_array;

    }

}