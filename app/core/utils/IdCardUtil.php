<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/20
 * Time: 11:45
 * 身份证工具类
 */

namespace app\core\utils;


class IdCardUtil
{
    /**
     * 隐藏身份证信息
     * @param string $card
     * @return string
     */
    public static function hide($card)
    {
        if ("" == $card) {
            return "";
        }
        if (18 == strlen($card)) {
            $card1 = substr($card, 0, 6);
            $card2 = substr($card, 16, 2);
            $card = $card1 . "**********" . $card2;
        } else {
            $card1 = substr($card, 0, 4);
            $card2 = substr($card, 13, 2);
            $card = $card1 . "*********" . $card2;
        }
        return $card;
    }

    /**
     * 判断身份证是否合法
     * @param string $card
     * @return boolean
     */
    public static function check($card)
    {
        $result = self::checkCN($card);
        if ($result) {
            return true;
        }
        $result = self::checkHK($card);
        if ($result) {
            return true;
        }
        $result = self::checkTW($card);
        if ($result) {
            return true;
        }
        $result = self::checkMO($card);
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * 判断中国大陆居民身份证
     * @param string $card
     * @return boolean
     */
    private static function checkCN($card)
    {
        if ("" == $card || 18 != strlen($card)) {
            return false;
        }
        if (!preg_match("/^\d{17}(\d|x|X)$/", $card)) {
            return false;
        }
        $str = str_split($card);//这里是一个数组
        $year = intval($str[6] . $str[7] . $str[8] . $str[9]);
        $month = ($str[10] > 0) ? intval($str[10] . $str[11]) : intval($str[11]);
        $day = ($str[12] > 0) ? intval($str[12] . $str[13]) : intval($str[13]);
        if ($year >= 1900 && $year <= date("Y") && $month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
            //正常
        } else {
            return false;
        }
        $key = (($str[0] * 7) + ($str[1] * 9) + ($str[2] * 10) + ($str[3] * 5) + ($str[4] * 8) + ($str[5] * 4) + ($str[6] * 2) + ($str[7] * 1) + ($str[8] * 6) + ($str[9] * 3) + ($str[10] * 7) + ($str[11] * 9) + ($str[12] * 10) + ($str[13] * 5) + ($str[14] * 8) + ($str[15] * 4) + ($str[16] * 2)) % 11;
        $codes = array('1', '0', 'x', '9', '8', '7', '6', '5', '4', '3', '2');
        $right = $codes[$key];
        if (is_numeric($str[17])) {
            $vcode = $str[17];
        } else {
            $vcode = strtolower($str[17]);
        }
        if ($vcode == $right) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断中国香港居民身份证
     * @param string $card
     * @return boolean
     */
    private static function checkHK($card)
    {
        if (!preg_match("/^[A-Z]{1,2}[0-9]{6}\\(?[0-9A]\\)?$/", $card)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 判断中国台湾居民身份证
     * @param string $card
     * @return boolean
     */
    private static function checkTW($card)
    {
        /**
         * 台湾身份证总共有10位数字。第一位是字母。后面九位是数字。
         * 台湾省份证的第一位的字母代表地区分别以A——Z表示
         * 规则如下:
         * 地区后面的数字为该字母转换的数字码。
         * A 台北市10
         * B 台中市11
         * C 基隆市12
         * D 台南市13
         * E 高雄市14
         * F 台北县15
         * G 宜兰县16
         * H 桃园县17
         * I 嘉义市34
         * J 新竹县18
         * K 苗栗县19
         * L 台中县20
         * M 南投县21
         * N 彰化县22
         * O 新竹市35
         * P 云林县23
         * Q 嘉义县24
         * R 台南县25
         * S 高雄县26
         * T 屏东县27
         * U 花莲县28
         * V 台东县29
         * W 金门县30
         * X 澎湖县31
         * Y 阳明山32
         * Z 连江县33
         * 第二位数字代表性别 男性是1，女性是2
         * 第三位到第九位为任意的一串数字
         * 第十位为验证码。
         * 字母(ABCDEFGHJKLMNPQRSTUVXYWZIO)对应一组数(10——35)。
         * 令其十位数为X1，个位数为X2；
         * D2到D9分别代表身份证号码的第二至第九位数。
         * Y＝X1＋9×X2＋8×D2＋7×D3＋6×D4＋5×D5＋4×D6＋3×D7＋2×D8＋1×D9
         * 将Y的值除以10。得出的余数结果。
         * 再用10来减去这个余数结果。就得出身份证上的最后一位数字。
         * 例如R123456783，R=25，
         * 检查公式是：2+5*9+1*8+2*7+3*6+4*5+5*4+6*3+7*2+8*1=167，
         * 其167再除以10求余数结果。
         * 其余数结果的个位数为7以10减去得3(检查码)。
         */
        if (!preg_match("/^[a-zA-Z][0-9]{9}$/", $card)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 判断中国澳门居民身份证
     * @param string $card
     * @return boolean
     */
    private static function checkMO($card)
    {
        if (!preg_match("/^[1|5|7][0-9]{6}\\(?[0-9A-Z]\\)?$/", $card)) {
            return false;
        } else {
            return true;
        }
    }
}