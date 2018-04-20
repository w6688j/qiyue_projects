<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 10:31
 * 检测字符合法的工具类
 *
 */

namespace app\core\utils;

use app\lib\IllegalCheck\IllegalCheck;
use atphp\exception\ExceptionExt;

class CheckValueUtil
{
    private static $regex = array(
        'require' => '/.+/', //匹配任意字符，除了空和断行符
        'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'phone' => '/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/',
        'mobile' => '/^1[3|4|5|7|8][0-9]\d{4,8}$/',
        'url' => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
        // 图片连接 http://www.example.com/xxx.jpg
        'img' => '^(http|https|ftp):(\/\/|\\\\)(([\w\/\\\+\-~`@:%])+\.)+([\w\/\\\.\=\?\+\-~`@\':!%#]|(&amp;)|&)+\.(jpg|bmp|gif|png)$',
        'currency' => '/^\d+(\.\d+)?$/',
        'number' => '/\d+$/',
        'zip' => '/^[1-9]\d{5}$/',
        'qq' => '/^[1-9]\d{4,12}$/',
        'int' => '/^[-\+]?\d+$/',
        'double' => '/^[-\+]?\d+(\.\d+)?$/',
        'english' => '/^[A-Za-z]+$/',
        'password' => '/^.{6,20}$/',
        //'username' => '/^[a-zA-Z0-9][a-zA-Z0-9_]{3,18}[a-zA-Z0-9]$/',
        'num_eng' => '/^[A-Za-z0-9_]+$/',
        'nickname' => '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{4,20}$/u', //昵称
        'zhanghao' => '/^[a-zA-Z0-9][a-zA-Z0-9_@.]{4,16}[a-zA-Z0-9]$/', //账号模糊带@
        'realname' => '/^[\x{4e00}-\x{9fa5}]{2,4}$/u',//2-4 个汉字 真实姓名
    );


    /**
     * 验证数据
     * @param string $value 待验证的数据
     * @param string $checkName 验证类型
     * @return bool
     */
    public static function check($value, $ruleName)
    {
        $matchRegex = self::getRegex($ruleName);
        return preg_match($matchRegex, trim($value));
    }

    /**
     * 检测非法的敏感字符
     * @param $value
     * @return bool  正确true, 错误false
     */
    public static function checkIllegal($value)
    {
        $illegalCheck = new IllegalCheck();
        return !$illegalCheck->check($value);
    }

    /**
     * 验证身份证号码
     * @param $value
     * @return bool 正确true ,错误false
     */
    public static function checkIdCard($value)
    {
        return IdCardUtil::check($value);
    }

    /*
     * 取得验证类型的正则表达式
     * @param string $name 验证类型
     * @return string
     */
    private static function getRegex($name)
    {
        if (isset(self::$regex[strtolower($name)])) {
            return self::$regex[strtolower($name)];
        } else {
            throw new ExceptionExt("正则表达式名称: {$name}未找到");
        }


    }

}