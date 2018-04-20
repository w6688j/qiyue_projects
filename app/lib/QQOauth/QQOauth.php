<?php
/**
 * 微信扫码登录
 * 支持OAuth 2.0
 * @author 3Dnn 20160911
 */

namespace app\lib\QQOauth;

use atphp\Config;
use atphp\Request;

class QQOauth
{
    private $oauth;

    function __construct()
    {
        $this->oauth = new QQOauthTool();
    }

    /**
     * 请求登陆
     */
    public function sendLogin()
    {
        $sUrl = "https://graph.qq.com/oauth2.0/authorize?response_type=code"
            . "&client_id=" . Config::get("qqOauth")['app_id']
            . "&redirect_uri=" . urlencode(Config::get("qqOauth")["redirect_uri"])
            . "&scope=" . Config::get("qqOauth")['qq_scope'];
        //file_put_contents(__DIR__."/../../tmp/url.txt",$sUrl."\n");


        header("location:" . $sUrl);
    }

    //https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=100292182&redirect_uri=http%3A%2F%2Fgamefish.pp158.com&scope=get_user_info,add_topic,add_one_blog,add_album,upload_pic,list_album,add_share,check_page_fans,add_t,add_pic_t,get_info
    /**
     * 获取access_token
     */
    public function getAccesToken()
    {
        $sUrl = "https://graph.qq.com/oauth2.0/token";
        $aGetParam = array(
            "grant_type" => "authorization_code",
            "client_id" => Config::get("qqOauth")['app_id'],
            "client_secret" => Config::get("qqOauth")['app_key'],
            "code" => Request::getString("code"),
            "state" => Request::getString("state"),
            "redirect_uri" => Config::get("qqOauth")["redirect_uri"],
        );
        $sContent = $this->oauth->get($sUrl, $aGetParam);
        $aTemp = explode("&", $sContent);
        if (empty($aTemp[0])) {
            return FALSE;
        }
        $accessArray = explode("=", $aTemp[0]);
        if (empty($accessArray[1])) {
            return FALSE;
        }
        return $accessArray[1];
    }

    /**
     * 获取OpenId
     */
    public function getOpenId($access_token)
    {
        $sUrl = "https://graph.qq.com/oauth2.0/me";
        $aGetParam = array(
            "access_token" => $access_token,
        );
        $sContent = $this->oauth->get($sUrl, $aGetParam);
        if (empty($sContent)) {
            return FALSE;
        }
        preg_match('/callback\(\s+(.*?)\s+\)/i', $sContent, $aTemp);
        $aResult = json_decode($aTemp[1], true);
        if (empty($aResult["openid"])) {
            return FALSE;
        }
        return $aResult["openid"];
    }

    /**
     * 获取用户个人信息
     * @param $access_token string
     * @param $appid string
     * @param $openid string
     */
    public function get_info($access_token, $openid)
    {
        $sUrl = "https://graph.qq.com/user/get_info";
        $aGetParam = array(
            "access_token" => $access_token,
            "oauth_consumer_key" => Config::get("qqOauth")["app_id"],
            "openid" => $openid,
            "format" => "json"
        );
        $sContent = $this->oauth->get($sUrl, $aGetParam);
        if (FALSE !== $sContent) {
            return json_decode($sContent, true);
        } else {
            return FALSE;
        }
    }

    /**
     * 获取用户QQ空间的个人信息
     * @param $access_token string
     * @param $appid string
     * @param $openid string
     */
    public function get_KJ_userInfo($access_token, $openid)
    {
        $sUrl = "https://graph.qq.com/user/get_user_info";
        $aGetParam = array(
            "access_token" => $access_token,
            "oauth_consumer_key" => Config::get("qqOauth")["app_id"],
            "openid" => $openid,
            "format" => "json"
        );
        $sContent = $this->oauth->get($sUrl, $aGetParam);
        if (FALSE !== $sContent) {
            return json_decode($sContent, true);
        } else {
            return FALSE;
        }
    }
}

?>