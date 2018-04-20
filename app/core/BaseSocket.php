<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/12
 * Time: 17:44
 * socket连接基础方法 ---就一个单例
 */

namespace app\core;


use atphp\exception\ExceptionExt;

final  class BaseSocket
{
    protected $hander = null;

    public function __construct()
    {
    }

    //连接方式
    public function connect($host, $port, $timeout)
    {
        if (!$host || !$port) {
            throw new ExceptionExt("socket host/port 不能为空");
        }
        $this->hander = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$this->hander) {
            throw  new ExceptionExt("@socket 连接失败,错误信息是:" . $errstr);
        }
        stream_set_blocking($this->hander, TRUE);
        stream_set_timeout($this->hander, $timeout);

        return $this->hander;
    }

    //关闭连接
    public function close()
    {
        if ($this->hander) {
            fclose($this->hander);
            $this->hander = null;
        }
    }


}