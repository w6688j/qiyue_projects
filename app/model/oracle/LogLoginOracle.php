<?php
/**
 * For: 用户登录日志操作表
 * User: caostian
 * Date: 2017/10/19
 * Time: 11:36
 */

namespace app\model\oracle;

use app\core\BaseOracleModel;

class LogLoginOracle extends BaseOracleModel
{

    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);
    }

    //v_sel_type 1 统计时间段内的在线人数---不准确,, ,2 统计IP ,4 统计人数,8 登录时长满五分钟
    public function p_log_login_stat($v_uid, $v_ip = 0, $v_intime_start = 0, $v_intime_end = 0, $v_outtime_start = 0, $v_outtime_end = 0, $v_netbarid = -1, $v_sel_type = 4, $v_group_by = '', $v_order_by = '')
    {
        //PROCEDURE "p_log_login_stat"(v_uid in number,v_ip in number,v_intime_start in number,v_intime_end in number,v_outtime_start in number,v_outtime_end IN NUMBER,v_netbarid in number ,v_sel_type in number,v_group_by in varchar2,v_order_by in varchar2,login_count out NUMBER) AS
        $sql = 'BEGIN game_user."p_log_login_stat"(:v_uid,:v_ip,:v_intime_start,:v_intime_end,:v_outtime_start,:v_outtime_end,:v_netbarid,:v_sel_type,:v_group_by,:v_order_by,:login_count,:ip_count,:use_count,:login_5m_count); END;';

        $login_count = 0;
        $ip_count = 0;
        $use_count = 0;
        $login_5m_count = 0;//登录时长满五分钟

        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":v_uid", $v_uid);
        oci_bind_by_name($stid, ":v_ip", $v_ip);
        oci_bind_by_name($stid, ":v_intime_start", $v_intime_start);
        oci_bind_by_name($stid, ":v_intime_end", $v_intime_end);
        oci_bind_by_name($stid, ":v_outtime_start", $v_outtime_start);
        oci_bind_by_name($stid, ":v_outtime_end", $v_outtime_end);
        oci_bind_by_name($stid, ":v_netbarid", $v_netbarid);
        oci_bind_by_name($stid, ":v_sel_type", $v_sel_type);
        oci_bind_by_name($stid, ":login_count", $login_count, 32);
        oci_bind_by_name($stid, ":ip_count", $ip_count, 32);
        oci_bind_by_name($stid, ":v_group_by", $v_group_by);
        oci_bind_by_name($stid, ":v_order_by", $v_order_by);
        oci_bind_by_name($stid, ":use_count", $use_count, 32);
        oci_bind_by_name($stid, ":login_5m_count", $login_5m_count, 32);
        oci_execute($stid);
        //检测错误信息 --省的写好多代码
        $this->checkError($stid);

        oci_free_statement($stid);

        return array(
            "login_count" => intval($login_count),//统计时间段的在线人数
            "ip_count" => intval($ip_count),
            "use_count" => intval($use_count),
            "login_5m_count" => intval($login_5m_count),//登录时长满五分钟
        );


    }
}