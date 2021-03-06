<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/28
 * Time: 10:15
 * 微信支付
 */

namespace service;


use atphp\Config;
use atphp\util\HttpUtil;
use atphp\util\LogUtil;
use atphp\util\XmlUtil;

class WeiXinPayWebService
{
    /**
     * 微信二维码支付方法
     * @param $request
     * @return array
     */
    public static  function pay($request)
    {
        $result = self::unifiedOrder($request);
        $log_msg = "请求微信下单,返回信息:" . var_export($result, true);

        $return_arr = array(
            "msg" => 'fail',
            "status" => false,
        );

        if (self::checkNoticeSign($result)) {
            if ($result["return_code"] == 'SUCCESS' && $result["result_code"] == 'SUCCESS') {
                //这里是成功状态
                $return_arr['status'] = true;
                $return_arr["msg"] = 'success';
                $return_arr = array_merge($return_arr, $result);
            } else {
                $log_msg = ',请求失败';
            }
        } else {
            $log_msg .= ', 秘钥验证失败';
        }


        //打印错误日志
        if (!$return_arr['status']) {
            if (Config::get("log_status")) {
                LogUtil::write($log_msg, LogUtil::ERR, LogUtil::FILE, "weixin_web/request_underOrder.log");
            }
        }


        return $return_arr;
    }


    /**
     *
     * 检测通知的sign是否和生成的sign相匹配
     * @param $params
     * @return bool
     */
    public static function checkNoticeSign($result)
    {
        if ($result["sign"] && self::makeSign($result) == $result["sign"]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param  array $inputObj
     * @param int $timeOut
     * @throws \Exception
     * @return array 成功时返回，其他抛异常
     */
    private static function unifiedOrder(Array &$request, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //检测必填参数

        if (self::checkEmpty($request['out_trade_no'])) {
            throw new \Exception("缺少统一支付接口必填参数out_trade_no！");
        }

        if (self::checkEmpty($request['body'])) {
            throw new \Exception("缺少统一支付接口必填参数body！");
        }

        if (self::checkEmpty($request['total_fee'])) {
            throw new \Exception("缺少统一支付接口必填参数total_fee！");
        }

        if (self::checkEmpty($request['trade_type'])) {
            throw new \Exception("缺少统一支付接口必填参数trade_type！");
        }

        if ($request['trade_type'] == 'JSAPI' && self::checkEmpty($request['openid'])) {
            throw new \Exception("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
        }

        if ($request['trade_type'] == 'NATIVE' && self::checkEmpty($request['product_id'])) {
            throw new \Exception("统一支付接口中，缺少必填参数product_id！trade_type为NATIVE时，product_id为必填参数！");
        }

        $request['appid'] = Config::get("weixin_web")['appid'];
        $request['mch_id'] = Config::get("weixin_web")['mchid'];
        $request['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $request['nonce_str'] = self::getNonceStr();


        //签名
        $request['sign'] = self::makeSign($request);
//        $xml = self::toXml($request);
        $xml = XmlUtil::encodeSimple($request);

        $response = HttpUtil::curl($url, $xml, $timeOut, function ($ch) {
            //微信这里要设置证书,我这里就直接用回调的方式来设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, Config::get("weixin_web")["sslcert_path"]);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, Config::get("weixin_web")['sslkey_path']);

        });


        $result = XmlUtil::decode($response);


        return $result;
    }


    /**
     * 生成签名
     * @return string 签名
     */
    public static function makeSign($request)
    {
        //签名步骤一：按字典序排序参数
        ksort($request);
        $string = self::toUrlParams($request);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . Config::get("weixin_web")["key"];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }


    /**
     * 格式化参数格式化成url参数
     */
    private static function toUrlParams($request)
    {
        $buff = "";
        foreach ($request as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }


    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return string 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected static function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
}