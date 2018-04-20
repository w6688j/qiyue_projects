<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 15:18
 */
namespace app\module\activity\controller;

use app\core\QiyueLog;
use app\module\activity\service\GiftService;
use app\service\PayDetailService;
use atphp\Controller;
use atphp\exception\ExceptionExt;
use atphp\util\SessionUtil;

class IndexController extends Controller
{
    public function index()
    {

        //echo 1/0;
       header("location:http://www.pp158.com");

        //dump(PayDetailService::getOneByTradeNo("0fe5292071ce44eb9f667260a6112aac"));

//        $sid = SessionUtil::get("sid");
//        echo GiftService::sendClientTicket(121457,1,40,$sid);
//
//        echo "打印了";
    }
}