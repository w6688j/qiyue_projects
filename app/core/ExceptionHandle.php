<?php
/**
 * For: .....
 * User: caostian
 * Date: 2017/10/22
 * Time: 11:29
 */

namespace app\core;


use atphp\exception\ExceptionExt;
use atphp\util\JsonUtil;

class ExceptionHandle
{
    public static function errorHandle($errno, $errstr, $errfile, $errline)
    {
        self::exceptionLog($errno, $errstr, $errfile, $errline);
        throw  new ExceptionExt($errstr,$errno);

    }

    public static function exceptionHandle(ExceptionExt $e)
    {
        $error['status'] = '0';
        $error['msg'] = $e->getMessage();
        $error['type'] = 'error';
        $error['info'] = '系统发生异常,请联系客服!';

        if (DEBUG) {

            $error["trace"] = $e->getError();
            QiyueLog::error($error, 'exception');
        }

        echo JsonUtil::encode($error);
    }

    protected static function exceptionLog($errno, $errstr, $errfile, $errline)
    {
        $data = [
            'file' => $errfile,
            'line' => $errline,
            'msg' => $errstr,
            "errno" => $errno,
            $error['info'] = '系统发生异常,请联系客服!',
        ];
        QiyueLog::error($data, 'error');
    }

}