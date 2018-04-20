<?php
/**
 * For: .....
 * User: caostian
 * Date: 2017/10/22
 * Time: 16:15
 */

namespace app\service;


use app\model\oracle\PayDetailOracle;

class PayDetailService
{
    public static function getOneByTradeNo($trade_no)
    {
        $v_fields = '"uid","sid","status","price"';
        $info = self::_model()->p_t_paydetail1(-1, $trade_no, -1, 0, 0, -1, $v_fields, '', '', 1, 1);
        return count($info)>0?$info[0]:null;
    }

    private static function _model()
    {
        return new PayDetailOracle();
    }


}