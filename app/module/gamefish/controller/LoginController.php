<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 17:23
 */

namespace app\module\gamefish\controller;


use atphp\Controller;
use atphp\Request;

class LoginController extends Controller
{

    public function index()
    {
        $account = Request::getString("account");
        $password = Request::getString("password");

        $this->assign([
            "account" => $account,
            "password" => $password,
        ]);

        $this->display();
    }
}