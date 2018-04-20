<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 9:42
 *
 * 非法字符/敏感字符检测
 *
 */

namespace app\lib\IllegalCheck;

class IllegalCheck
{
    private $data = [];

    //加载数据
    public function __construct()
    {
        $illegal = array();    //非法字符集

        $handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . "data.txt", "r");

        if ($handle) {
            while (!feof($handle)) {
                $illegal[] = trim(fgets($handle));//默认1024个字节
            }
            fclose($handle);
        }

        $this->data = $illegal;

    }

    /**
     * 检测字符
     * @param $str
     * @return bool 存在返回true ,否则返回false
     */
    public function check($str)
    {
        if (empty($str)) {
            return false;
        }
        //如果是英文,那么就全部匹配,如果是中文的话,就检测是否存在相关字符
        if (preg_match('/^[A-Za-z0-9\._ +\\\\\-*%{}\'"\?=()&^$#\@\!~`:;\[\]<\/>,.]+$/', $str, $match)) {
            //全部匹配
            return in_array($str, $this->data);

        } else {
            //中文只要存在就可以
            foreach ($this->data as $value) {
                if (strpos($value, $str) !== false) {
                    return true;
                }
            }

        }
        return false;

    }
}