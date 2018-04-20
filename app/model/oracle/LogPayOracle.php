<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/14
 * Time: 10:39
 */

namespace app\model\oracle;

use app\core\BaseOracleModel;

class LogPayOracle extends BaseOracleModel
{

    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);
    }

    /**
     * 支付 插入日志
     * @param $trade_no //接口返回的交易号
     * @param $notify_time
     * @param $out_trade_no //原支付请求的商户订单号(自己生成的订单号)
     * @param $trade_status
     * @param $total_fee
     * @param $pay_status
     * @param $v_type
     * @param $os
     * @return bool
     * :ret := game_user."f_add_pay_log" (:trade_no, :notify_time, :out_trade_no, :trade_status, :total_fee,:pay_status,:v_type,:os)
     */
    function f_add_pay_log($trade_no, $notify_time, $out_trade_no, $trade_status, $total_fee, $pay_status, $v_type, $os)
    {
        $sql = 'BEGIN :ret := game_user."f_add_pay_log" (:trade_no, :notify_time, :out_trade_no, :trade_status, :total_fee,:pay_status,:v_type,:os); END;';
        $ret = -1;

        $stid = oci_parse($this->hander, $sql);

        oci_bind_by_name($stid, ":ret", $ret, 20);//ret表示有没有这个数据，返回值为0表示执行成功
        oci_bind_by_name($stid, ":trade_no", $trade_no);
        oci_bind_by_name($stid, ":notify_time", $notify_time);
        oci_bind_by_name($stid, ":out_trade_no", $out_trade_no);
        oci_bind_by_name($stid, ":trade_status", $trade_status);
        oci_bind_by_name($stid, ":total_fee", $total_fee);
        oci_bind_by_name($stid, ":pay_status", $pay_status);
        oci_bind_by_name($stid, ":os", $os,20);
        oci_bind_by_name($stid, ":v_type", $v_type,20);
        oci_execute($stid);

        //检测错误信息 --省的写好多代码
        $this->checkError($stid);

        oci_free_statement($stid);

        if ($ret == 0) {
            return true;
        }
        return false;

    }

}