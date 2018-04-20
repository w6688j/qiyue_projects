<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/14
 * Time: 9:55
 * socket基类
 */

namespace app\core;


class BaseSocketModel
{
    protected $hander = null;
    private $baseSocket = null;

    public function __construct($host, $port, $timeout = 5)
    {
        $this->baseSocket = new BaseSocket();
       // LogUtil::write("$host, $port", LogUtil::INFO,LogUtil::FILE, 'alipay_web/' . DateUtil::format(time(), "Ymd") . "/sid_s.log");
        $this->hander = $this->baseSocket->connect($host, $port, $timeout);
    }



    public function close()
    {
        $this->baseSocket->close();
    }
}