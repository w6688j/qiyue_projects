<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/21
 * Time: 11:07
 */

namespace app\module\phone\controller;


use atphp\Controller;
use atphp\Request;

class GamesController extends Controller
{
    //下载页
    public function index()
    {
        $gameid = Request::getInteger("gameid");
        $type = Request::getInteger("type");

        $protocol = Request::isSsl() ? "https://" : "http://";
        $url = $protocol . $_SERVER["HTTP_HOST"] . "/public/soft/";

        $file_path = WEB_PATH . "public/soft/";

        $files_name = '';
        if ($type == 0) {
            $files_name = $gameid . ".zip";
        } else if ($type == 1) {
            $files_name = $gameid . "ex.zip";
        }
        if (!is_file($file_path . $files_name)) {
            echo "文件没有找到!";
            exit;
        }
        header("location:" . $url . $files_name);
    }

}