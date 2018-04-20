<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 11:52
 * 用户安全的表模型
 */

namespace app\model\oracle;


use app\core\BaseOracleModel;

class UserSecuOracle extends BaseOracleModel
{

    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);
    }


    /**
     * 查询身份证号码存不存在
     * @param $idcard
     * @return bool | int 用户Id
     */
    public function func_p_exist_idcard($idcard)
    {
        $user_id = false;
        $sql = 'BEGIN :ret := GAME_USER."p_existidcard" (:identity); END;';
        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":ret", $user_id, 20);
        oci_bind_by_name($stid, ":identity", $idcard);
        oci_execute($stid);

        //这里直接触发错误
        $this->checkError($stid);

        oci_free_statement($stid);

        return $user_id;
    }

    /**
     *设置用户安全表信息
     * "p_setusersecu" (id1 IN NUMBER, bar1 in number,question1 IN NUMBER, answer1 IN VARCHAR2, email1 IN VARCHAR2, identity1 IN VARCHAR2, name1 IN VARCHAR2)
     */
    function func_p_setusersecu($id1, $bar1, $question1, $answer1, $email1, $identity1, $name1)
    {
        $name1 = icovUtf8ToGbk($name1);
        $question1 = icovUtf8ToGbk($question1);
        $answer1 = icovUtf8ToGbk($answer1);

        $ret = -1;
        $sql = 'BEGIN :ret := GAME_USER."p_setusersecu" (:id1, :bar1, :question1,:answer1,:email1,:identity1,:name1); END;';

        $stid = oci_parse($this->hander, $sql);
        oci_bind_by_name($stid, ":ret", $ret, 20);
        oci_bind_by_name($stid, ":id1", $id1);
        oci_bind_by_name($stid, ":bar1", $bar1);
        oci_bind_by_name($stid, ":question1", $question1);
        oci_bind_by_name($stid, ":answer1", $answer1);
        oci_bind_by_name($stid, ":email1", $email1);
        oci_bind_by_name($stid, ":identity1", $identity1);
        oci_bind_by_name($stid, ":name1", $name1);
        oci_execute($stid);

        //这里直接触发错误
        $this->checkError($stid);

        oci_free_statement($stid);
        return $ret;
    }

}