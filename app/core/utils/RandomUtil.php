<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 14:08
 */

namespace app\core\utils;


use atphp\Config;

class RandomUtil
{
    /**
     * 获取随机字符
     *
     * @param integer $num 字符串位数 默认返回32位随机数
     * @return string
     */
    public static function getRandom($num = 32)
    {
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
        $rs = '';
        $len = strlen($str) - 1;
        for ($i = 0; $i < $num; $i++) {
            $rs .= $str[mt_rand(0, $len)];
        }
        return $rs;
    }

    /**
     * 生成不可逆的加密码---适合生成用户密码等
     * @param string $password 密码
     * @return string
     */
    public static function getPassword($password)
    {
        return md5(md5($password) . Config::get("encrypt_key"));
    }


}