<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 20:25
 */
namespace app\model\oracle;

use app\core\BaseOracleModel;

class UserMoneyOrale extends BaseOracleModel
{

    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);
    }


    //添加用户金币 [!]废弃--并没有给我权限
    //FUNCTION "_fix_bankmoney" (uid1 in number,money1 in number)
    private function _fix_bankmoney($uid1, $money1)
    {
        $sql = 'BEGIN :ret:=game_user."_fix_bankmoney"(:uid1,:money1);END;';
        $ret = "-1";
        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":uid1", $uid1);
        oci_bind_by_name($stid, ":money1", $money1);
        oci_bind_by_name($stid, ":ret", $ret, 32);

        oci_execute($stid);
        //检测错误信息 --省的写好多代码
        $this->checkError($stid);

        oci_free_statement($stid);
        return $ret == 0 ? true : false;
    }

    //同时添加金币和奖券--[!]废弃--并没有给我权限
    //FUNCTION "_fix_money_ticket" (uid1 in number,money1 in number,ticket1 in number)
    private function _fix_money_ticket($uid1, $money1, $ticket1)
    {
        $sql = 'BEGIN :ret:=game_user."_fix_money_ticket"(:uid1,:money1,:ticket1);END;';
        $ret = -1;
        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":uid1", $uid1);
        oci_bind_by_name($stid, ":money1", $money1);
        oci_bind_by_name($stid, ":ticket1", $ticket1);
        oci_bind_by_name($stid, ":ret", $ret, 32);
        oci_execute($stid);
        //检测错误信息 --省的写好多代码
        $this->checkError($stid);
        oci_free_statement($stid);

        return $ret == 0 ? true : false;
    }

    //添加金币/奖券----我不管反正我用的是task_id = 1000
    //FUNCTION "p_task_reward" (uid1 IN NUMBER, taskid1 in NUMBER,money1 IN NUMBER,ticket1 IN NUMBER,speaker1 IN NUMBER)
    public function p_task_reward($user_id, $money = 0, $ticket = 0, $speaker = 0, $task_id = 1000)
    {
        $sql = 'BEGIN :ret:=game_user."p_task_reward"(:uid1,:taskid1,:money1,:ticket1,:speaker1);END;';
        $ret = -1;
        try{
            $stid = oci_parse($this->hander, $sql);

            oci_bind_by_name($stid, ":uid1", $user_id);
            oci_bind_by_name($stid, ":taskid1", $task_id);
            oci_bind_by_name($stid, ":money1", $money);
            oci_bind_by_name($stid, ":ticket1", $ticket);
            oci_bind_by_name($stid, ":speaker1", $speaker);
            oci_bind_by_name($stid, ":ret", $ret, 32);
            oci_execute($stid);
        }catch (\Exception $e){
            print_r($e);
        }
        
        //检测错误信息 --省的写好多代码
        $this->checkError($stid);
        oci_free_statement($stid);
        return $ret == 0 ? true : false;
    }

    /**
     * 必须先 设置任务,,才能调用这个
     * FUNCTION "p_settask" (uid1 IN NUMBER, type0 IN NUMBER, type1 IN NUMBER,val1 IN NUMBER,typedep0 IN NUMBER, typedep1 IN NUMBER,maxval1 IN NUMBER,flag IN NUMBER,
     * id1 out number, val2 out number)
     * TASK_TYPE_ONCE_ACTIVE_PAY1 = 1000,
     * TASK_TYPE_ONCE_ACTIVE_PAY2,
     * TASK_TYPE_ONCE_ACTIVE_PAY3,
     * TASK_TYPE_ONCE_ACTIVE_PAY4,
     * TASK_TYPE_ONCE_ACTIVE_PAY5,
     * TASK_TYPE_ONCE_ACTIVE_NEW7,
     */
    public function p_settask($user_id, $task_type, $type0 = 0, $val1 = 1, $typedep0 = 0, $typedep1 = 0, $maxval1 = 1, $flag = 0)
    {

        $sql = 'BEGIN :ret:=game_user."p_settask"(:uid1,:type0,:type1,:val1,:typedep0,:typedep1,:maxval1,:flag,:id1,:val2);END;';
        $ret = -1;
        $task_id = 0;
        $val2 = 0; //这个值我也用不到
        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":uid1", $user_id);
        oci_bind_by_name($stid, ":type0", $type0);
        oci_bind_by_name($stid, ":type1", $task_type);
        oci_bind_by_name($stid, ":val1", $val1);
        oci_bind_by_name($stid, ":typedep0", $typedep0);
        oci_bind_by_name($stid, ":typedep1", $typedep1);
        oci_bind_by_name($stid, ":maxval1", $maxval1);
        oci_bind_by_name($stid, ":flag", $flag);
        oci_bind_by_name($stid, ":id1", $task_id,32);
        oci_bind_by_name($stid, ":val2", $val2,32);
        oci_bind_by_name($stid, ":ret", $ret, 32);
        oci_execute($stid);
        //检测错误信息 --省的写好多代码
        $this->checkError($stid);
        oci_free_statement($stid);
        return [
            "status" => $ret == 0 ? true : false,
            "task_id" => $task_id
        ];

    }


}