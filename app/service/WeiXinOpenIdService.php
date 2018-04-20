<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/18
 * Time: 13:06
 * 网页授权接口,获取openID
 */

namespace app\service;


use atphp\Config;
use atphp\Request;
use atphp\util\HttpUtil;

class WeiXinOpenIdService
{
    /**
     * 获取code的链接
     * @param $redirectUrl
     * @param string $scope
     * @return string
     */
    private static  function getCodeUrl($redirectUrl, $scope = 'snsapi_base')
    {

//        echo $redirectUrl;exit;
        $urlObj["appid"] = Config::get("weixin_web")["appid"];
        $urlObj["redirect_uri"] = $redirectUrl;
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = $scope;
        $urlObj["state"] = "STATE" . "#wechat_redirect";
        $bizString = self::ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?" . $bizString;
    }

    /**
     * 根据code 获得access_token链接
     * @param $code
     * @return string
     */
    private static function getAccessTokenUrl($code)
    {
        $urlObj["appid"] = Config::get("weixin_web")["appid"];
        $urlObj["secret"] = Config::get("weixin_web")["appsecret"];
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = self::ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?" . $bizString;
    }


    /**
     * 通过code从工作平台获取openid机器access_token
     * @param $code
     * @return mixed
     */
    public static function getAccessToken($code)
    {
        $url = self::getAccessTokenUrl($code);
        $res = HttpUtil::curl($url);
        //返回数据
        //{ "access_token":"ACCESS_TOKEN",   "expires_in":7200,     "refresh_token":"REFRESH_TOKEN",    "openid":"OPENID",  "scope":"SCOPE" }
        return json_decode($res, true);
    }


    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     * @return null | string
     */
    public  static function getOpenId()
    {
        //通过code获得openid
        if (!isset($_GET['code'])) {
            //触发微信返回code码
            $url = Request::isSsl() ? "https://" : "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = urlencode($url);

            $url = self::getCodeUrl($url);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $access_token = self::getAccessToken($code);
            if ($access_token && $access_token["openid"]) {
                return $access_token["openid"];
            }
            return null;
        }
    }


    /**
     * 拼接参数
     * @param $urlObj
     * @return string
     */
    private static function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v) {
            if ($k != "sign") {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

}