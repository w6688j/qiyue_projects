<?php
/**
 * For: .....
 * User: caostian
 * Date: 2017/10/20
 * Time: 17:36
 */

namespace app\model\oracle;

use app\core\BaseOracleModel;

class UserDetailOracle extends BaseOracleModel
{
    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);

    }

    public function p_user_detail_list1($v_id, $v_start_time = 0, $v_end_time = 0, $v_from = -1, $v_tel = '', $v_netbarid = -1, $v_field = '', $v_order = '', $pagesize1 = 20, $curpage = 1)
    {

        $sql = 'BEGIN game_user."p_user_detail_list1"(:v_id,:v_start_time,:v_end_time,:v_from,:v_tel,:v_netbarid,:v_field,:v_order,:pagesize1,:curpage,:cur); END;';
        $stid = null;
        $cur = null;
        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":v_id", $v_id);
        oci_bind_by_name($stid, ":v_start_time", $v_start_time);
        oci_bind_by_name($stid, ":v_end_time", $v_end_time);
        oci_bind_by_name($stid, ":v_from", $v_from);
        oci_bind_by_name($stid, ":v_tel", $v_tel);
        oci_bind_by_name($stid, ":v_netbarid", $v_netbarid);

        oci_bind_by_name($stid, ":v_field", $v_field);
        oci_bind_by_name($stid, ":v_order", $v_order);
        oci_bind_by_name($stid, ":pagesize1", $pagesize1);
        oci_bind_by_name($stid, ":curpage", $curpage);
        $cur = oci_new_cursor($this->hander);
        oci_bind_by_name($stid,":cur",$cur,-1,OCI_B_CURSOR);
        oci_execute($stid);
        oci_execute($cur);
        //检测错误信息 --省的写好多代码
        $this->checkError($stid);

        isset($stid) or oci_free_statement($stid);
        isset($cur) or oci_free_statement($cur);

        $list = [];
        while (false != ($row = oci_fetch_assoc($cur))) {
            $list [] = $row;
        }

        return $list;
    }

}