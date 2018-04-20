<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/13
 * Time: 11:56
 */

namespace app\core;

use atphp\exception\ExceptionExt;


class BaseOracleModel
{
    protected $hander = null;

    public function __construct($oracleConfigKey = null)
    {
        $this->hander = BaseOracle::connect($oracleConfigKey);
    }

    protected function checkError($stat, $cur = null)
    {
        if ($stat && $e = oci_error($stat)) {
            print_r($e);
            $e["message"] = icovGbkToUtf8($e['message']);
            //一遍数据库都要try_cache,所以我直接来记录下日志,不然捕获了,我就没法获得报错信息了
            QiyueLog::error("数据库错误信息: " . stripslashes(var_export($e, true)), "db");
            throw  new ExceptionExt("oracle连接失败");
        }
        if ($cur && $e = oci_error($cur)) {
            $e["message"] = icovGbkToUtf8($e['message']);
            QiyueLog::error("数据库错误信息: " . stripslashes(var_export($e, true)), "db");
            throw  new ExceptionExt("数据库错误信息: " . var_export($e, true));
        }
    }
}