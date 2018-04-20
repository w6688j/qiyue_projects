<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/13
 * Time: 11:50
 * 用户表模型
 */

namespace app\model\oracle;


use app\core\BaseOracleModel;

class UserOracle extends BaseOracleModel
{

    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);

    }

    public function getUseInfo($v_id = '', $v_uid = '')
    {
        //PROCEDURE "p_t_user_info"(v_id in NUMBER,v_uid in VARCHAR2,cur out sys_refcursor)
        $sql = 'BEGIN game_user."p_t_user_info"(:v_id,:v_uid,:cur); END;';
        $stid = @oci_parse($this->hander, $sql);
        @oci_bind_by_name($stid, ":v_id", $v_id);
        @oci_bind_by_name($stid, ":v_uid", $v_uid);
        $cur = @oci_new_cursor($this->hander);
        @oci_bind_by_name($stid, ":cur", $cur, -1, OCI_B_CURSOR);
        @oci_execute($stid);
        @oci_execute($cur);
        $info = oci_fetch_assoc($cur);
        if ($info) {
            $info['uid'] = icovGbkToUtf8($info['uid']);
            $info['name'] = icovGbkToUtf8($info['name']);
        }
        //这里直接触发错误
        $this->checkError($stid, $cur);
        oci_free_statement($stid);
        oci_free_statement($cur);


        return $info;
    }


    /**
     * 检测用户账号是否存在
     * @param $uid
     * @return int 返回用户ID
     */
    public function func_p_exist_uid($account)
    {
        $user_id = false;//用户ID

        $sql = 'BEGIN :ret := GAME_USER."p_existuid" (:uid); END;';
        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":ret", $user_id, 20);
        oci_bind_by_name($stid, ":uid", $account);
        oci_execute($stid);

        //触发错误
        $this->checkError($stid);
        oci_free_statement($stid);

        return intval($user_id);
    }

    /**
     * 检测用户昵称是否存在
     * @param $nickName
     * @return int 返回用户ID
     */
    function func_p_exist_name($nickName)
    {
        $user_id = false;

        $nickName = icovUtf8ToGbk($nickName);

        $sql = 'BEGIN :ret := GAME_USER."p_existname" (:name); END;';
        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":ret", $user_id, 20);
        oci_bind_by_name($stid, ":name", $nickName);
        oci_execute($stid);

        //触发错误
        $this->checkError($stid);

        oci_free_statement($stid);

        return $user_id;
    }

    /**
     * 注册用户
     * @param string $uid
     * @param string $password
     * @param string $name
     * @param integer $gender
     * @param integer $from 0网吧营销大师注册过来的，1官网注册的，2游戏客户端注册的
     * @return int
     *
     * FUNCTION "p_registuser" (uid1 IN VARCHAR2, pwd1 IN VARCHAR2, name1 IN VARCHAR2, gender1 IN NUMBER, from1 in number,
     * ip1 in number, netbarid1 in number, mobile1 in number,id1 OUT NUMBER)
     */
    function func_p_registuser($uid, $password, $name, $gender, $from, $ip, $netbarid, $mobile1)
    {
        $ret = -1;
        $user_id = 0;

        $name = icovUtf8ToGbk($name);
        $password = md5($password);//密码加密

        $sql = 'BEGIN :ret := GAME_USER."p_registuser" (:uid1, :pwd1, :name1,:gender1,:from1,:ip1,:netbarid1,:mobile1,:id1); END;';

        $stid = oci_parse($this->hander, $sql);

        oci_bind_by_name($stid, ":ret", $ret, 20);
        oci_bind_by_name($stid, ":uid1", $uid);
        oci_bind_by_name($stid, ":pwd1", $password);
        oci_bind_by_name($stid, ":name1", $name);
        oci_bind_by_name($stid, ":gender1", $gender);
        oci_bind_by_name($stid, ":from1", $from);
        oci_bind_by_name($stid, ":ip1", $ip);
        oci_bind_by_name($stid, ":netbarid1", $netbarid);
        oci_bind_by_name($stid, ":mobile1", $mobile1);
        oci_bind_by_name($stid, ":id1", $user_id, 32);
        oci_execute($stid);

        //触发错误
        $this->checkError($stid);

        oci_free_statement($stid);

        if ($ret == 0) {
            return $user_id;
        }
        return false;

    }

}