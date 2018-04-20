<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 19:23
 */

namespace app\model\oracle;

use app\core\BaseOracleModel;

class PayDetailOracle extends BaseOracleModel
{

    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);
    }


    //获取统计
    //--v_sel_type 1表示额外会统计网吧,2表示额外会统计总笔数,3,全部统计 总人数,总金额,网吧,总笔数 ---已弃用

    //--v_sel_type 1统计网吧,2统计下单笔数,4总金额,8总人数 16 总金币
    //--如果需要统计综合的,直接把各种类型相加就可以了,如统计网吧,和下单笔数,那么传1+2 =3 即可
    public function p_t_paydetail_stat($v_uid, $v_sid = '', $v_status = -1, $v_netbarid = -1, $v_start_time = 0, $v_end_time = 0, $v_pid_str = '', $v_sel_type = 15, $v_pay_type = -1)
    {

        //"p_t_paydetail_stat"(v_uid IN NUMBER,v_sid IN VARCHAR2,v_status IN NUMBER,v_netbarid IN NUMBER,v_start_time IN NUMBER,v_end_time in NUMBER,v_pid_str in VARCHAR2,v_sel_type IN NUMBER,v_pay_type in number,v_people_count OUT NUMBER,v_money_total OUT NUMBER,v_netbar_count OUT NUMBER,v_order_count OUT NUMBER,sum_money out NUMBER)
        $sql = 'BEGIN  game_user."p_t_paydetail_stat"(:v_uid,:v_sid,:v_status,:v_netbarid,:v_start_time,:v_end_time,:v_pid_str,:v_sel_type,:v_pay_type,:v_people_count,:v_money_total,:v_netbar_count,:v_order_count,:sum_money); END;';

        $v_people_count = 0;
        $v_money_total = 0;
        $v_netbar_count = 0;
        $v_order_count = 0;
        $sum_money = 0;


        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":v_uid", $v_uid);
        oci_bind_by_name($stid, ":v_sid", $v_sid);
        oci_bind_by_name($stid, ":v_status", $v_status);
        oci_bind_by_name($stid, ":v_netbarid", $v_netbarid);
        oci_bind_by_name($stid, ":v_start_time", $v_start_time);
        oci_bind_by_name($stid, ":v_end_time", $v_end_time);
        oci_bind_by_name($stid, ":v_pid_str", $v_pid_str, 32);
        oci_bind_by_name($stid, ":v_sel_type", $v_sel_type);
        oci_bind_by_name($stid, ":v_pay_type", $v_pay_type);
        oci_bind_by_name($stid, ":v_people_count", $v_people_count, 32);
        oci_bind_by_name($stid, ":v_money_total", $v_money_total, 32);
        oci_bind_by_name($stid, ":v_netbar_count", $v_netbar_count, 32);
        oci_bind_by_name($stid, ":v_order_count", $v_order_count, 32);
        oci_bind_by_name($stid, ":sum_money", $sum_money, 32);
        oci_execute($stid);

        //检测错误信息 --省的写好多代码
        $this->checkError($stid);

        oci_free_statement($stid);

        return array(
            "v_people_count" => $v_people_count,
            "v_money_total" => $v_money_total / 100,
            "v_netbar_count" => $v_netbar_count,
            "v_order_count" => $v_order_count,
            "v_sum_money" => $sum_money,
        );
    }


    public function p_t_paydetail1($v_uid, $v_sid = '', $v_status = -1, $v_start_time = 0, $v_end_time = 0, $v_netbarid = -1, $v_fields = '', $v_order = '', $v_group = '', $pagesize = 20, $curpage = 1)
    {

        $sql = 'BEGIN  game_user."p_t_paydetail1"(:v_uid,:v_sid,:v_status,:v_start_time,:v_end_time,:v_netbarid,:v_fields,:v_order,:v_group,:pagesize,:curpage,:cur); END;';

        $stid = oci_parse($this->hander, $sql);

        oci_bind_by_name($stid, ":v_uid", $v_uid);
        oci_bind_by_name($stid, ":v_sid", $v_sid);
        oci_bind_by_name($stid, ":v_status", $v_status);
        oci_bind_by_name($stid, ":v_start_time", $v_start_time);
        oci_bind_by_name($stid, ":v_end_time", $v_end_time);
        oci_bind_by_name($stid, ":v_netbarid", $v_netbarid);
        oci_bind_by_name($stid, ":v_fields", $v_fields);
        oci_bind_by_name($stid, ":v_order", $v_order);
        oci_bind_by_name($stid, ":v_group", $v_group);
        oci_bind_by_name($stid, ":pagesize", $pagesize);
        oci_bind_by_name($stid, ":curpage", $curpage);

        $cur = oci_new_cursor($this->hander);
        oci_bind_by_name($stid, ":cur", $cur, -1, OCI_B_CURSOR);

        oci_execute($stid);
        oci_execute($cur);

        //检测错误信息 --省的写好多代码
        $this->checkError($stid);

        $list = array();
        while (false != ($row = oci_fetch_assoc($cur))) {
            $list [] = $row;
        }
        oci_free_statement($stid);
        return $list;

    }


    public function p_t_paydetail2($v_uid, $v_sid, $v_status, $v_start_time, $v_end_time, $v_netbarid, $v_keyfield, $v_group, $pagesize)
    {
        // function "p_t_paydetail2"(v_uid IN NUMBER,v_sid in VARCHAR2,v_status in NUMBER,v_start_time in NUMBER,v_end_time in NUMBER,v_netbarid in number,v_keyfield in varchar2,v_order in VARCHAR2,v_group in VARCHAR2,pagesize in NUMBER,pg OUT NUMBER)
        $sql = 'BEGIN  :ret:=game_user."p_t_paydetail2" (:v_uid,:v_sid,:v_status,:v_start_time,:v_end_time,:v_netbarid,:v_keyfield,:v_group,:pagesize,:pg); END;';
        $stid = oci_parse($this->hander, $sql);
        $pg = 0;
        $ret = 0;
        oci_bind_by_name($stid, ":ret", $ret, 32);
        oci_bind_by_name($stid, ":v_uid", $v_uid);
        oci_bind_by_name($stid, ":v_sid", $v_sid);
        oci_bind_by_name($stid, ":v_status", $v_status);
        oci_bind_by_name($stid, ":v_start_time", $v_start_time);
        oci_bind_by_name($stid, ":v_end_time", $v_end_time);
        oci_bind_by_name($stid, ":v_netbarid", $v_netbarid);
        oci_bind_by_name($stid, ":v_keyfield", $v_keyfield);
        oci_bind_by_name($stid, ":v_group", $v_group);
        oci_bind_by_name($stid, ":pagesize", $pagesize);
        oci_bind_by_name($stid, ":pg", $pg, 32);
        oci_execute($stid);
        oci_free_statement($stid);

        return array($ret, $pg);
    }

}