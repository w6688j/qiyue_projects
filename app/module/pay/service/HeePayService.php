<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 13:46
 */
namespace app\module\pay\service;

use atphp\Config;
use atphp\util\DateUtil;

class HeePayService
{
    //heepay下单支付
    public static function underOrder(array $request)
    {
        //传送的数据
        $data_array = [
            "version" => Config::get("heepay")["version"],
            "agent_id" => Config::get("heepay")["agent_id"],
            "agent_bill_id" => strtolower($request["trade_no"]),
            "agent_bill_time" => DateUtil::format(time(), "YmdHis"),
            "pay_type" => Config::get("heepay")["pay_type"],
            "pay_amt" => ($request["total_fee"] / 100),
            "notify_url" => Config::get("heepay")["notify_url"],
            "return_url" => Config::get("heepay")["return_url"],
            "user_ip" => $_SERVER['REMOTE_ADDR'],
            "key" => Config::get("heepay")["sign_key"],
        ];


        //不加urldecode 会出现签名错误得问题,因为http_build_query 函数会把url自动encode
        $param = urldecode(http_build_query($data_array));
        $sign = md5($param); //计算签名值

        $data_array["pay_code"] = $request["pay_code"];
        $data_array["goods_name"] = urlencode("pp账单支付");
        $data_array["goods_num"] = urlencode(1);
        $data_array["goods_note"] = urlencode("");
        $data_array["remark"] = $request["s"];
        $data_array["sign"] = $sign;

        //表单自动提交
        self::form($data_array);

    }

    /**
     * 绘制form表单提交
     * @param $data_array
     */
    private static function form($data_array)
    {
        $url = Config::get("heepay")["pay_url"];

        $html = "<form id='frmSubmit' method='post' name='frmSubmit' action='{$url}'>";

        foreach ($data_array as $key => $value) {
            $html .= "<input type='hidden' name='{$key}' value='{$value}' />";
        }

        $html .= "<script language='javascript'>
                            function gatewayPaySubmit(){document.frmSubmit.submit();}
                            gatewayPaySubmit();
                        </script>";

        echo $html;
    }
}