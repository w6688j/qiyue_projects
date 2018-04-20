<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/19
 * Time: 15:18
 */
namespace app\module\home\controller;

class IndexController
{
    public function index()
    {
       echo addslashes('<h3 style="color: red;">你好</h3> ');



    }
}