<?php
/**
 * 微信扫码登录
 * 支持OAuth 2.0
 * @author 3Dnn 20160911
 */

namespace app\lib\WeixinLogin;

use atphp\Config;
use atphp\exception\ExceptionExt;
use atphp\Request;
use atphp\util\SessionUtil;

class WeixinLogin
{
    /**
     * 跳转到二维码界面等待用户授权
     * 用户授权给第三方获取自己存在服务商的信息后
     * 第三方可获得临时授权令牌 CODE
     */
    public function queryLogin()
    {
        $appId = Config::get("weixinLogin")["app_id"];     //APPID
        $callback = urlencode(Config::get("weixinLogin")["redirect_uri"]); //回调地址
        $state = md5(uniqid(rand(), true));    //生成唯一随机串


        //存入session
        SessionUtil::set("wechat_status", $state);

        $wxurl = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $appId
            . "&redirect_uri=" . $callback
            . "&response_type=code&scope=snsapi_login&state=" . $state
            . "#wechat_redirect";
        header("Location: $wxurl");
    }

    /**
     * 回调时检查状态标识防CSRF攻击
     */
    public function checkStatus()
    {
        if (Request::get("state") != SessionUtil::get("wechat_status")) {
            throw new ExceptionExt("登录信息或跳转地址失效，请重试");
        }
    }

    /**
     * 获取TOKEN 和 OpenId
     */
    public function getTokenOpenid()
    {
        $data = $this->getDataAboutToken();
        return array('token' => $data['access_token'], 'openid' => $data['openid']);
    }

    /**
     * 获取用户昵称
     */
    public function getNickName($token, $openId)
    {
        $data = $this->getDataAboutUser($token, $openId);
        return $this->stringFilter($data['nickname']);
    }


    /**
     * 回调后，获得临时令牌 CODE
     * 用临时令牌、APPID、Secret 换取访问令牌 access_token 等信息
     */
    private function getDataAboutToken()
    {
        $code = $_GET['code'];
        $appId = Config::get("weixinLogin")['app_id'];
        $secret = Config::get("weixinLogin")['app_secret'];
        $re = array();
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appId
            . '&secret=' . $secret
            . '&code=' . $code
            . '&grant_type=authorization_code';
        $data = $this->httpGet($url);
        $data = json_decode($data, true);
        $re['access_token'] = $data['access_token'];
        $re['expires_in'] = $data['expires_in']; //access_token有效期默认7200秒(2小时)
        $re['refresh_token'] = $data['refresh_token'];
        $re['openid'] = $data['openid'];
        $re['scope'] = $data['scope'];
        $re['unionid'] = $data['unionid'];

        return $re;
    }

    /**
     * 回调后，获得访问令牌 access_token 和 用户  openid
     * 并这两个换取用户信息
     * @param  $token //接口调用凭证
     * @param  $openId //授权用户唯一标识：openid
     * @return array
     */
    private function getDataAboutUser($token, $openId)
    {
        $re = array();
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $token
            . '&openid=' . $openId
            . '&lang=zh_CN';
        $data = $this->httpGet($url);
        $data = json_decode($data, true);
        $re['openid'] = $data['openid'];
        $re['nickname'] = $data['nickname'];
        $re['sex'] = $data['sex'];              //性别，整形数字，如 1，表示男
        $re['city'] = $data['city'];            //城市
        $re['unionid'] = $data['unionid'];
        $re['country'] = $data['country'];      //国家，如 中国
        $re['language'] = $data['language'];    //地区语言，如 en
        $re['province'] = $data['province'];    //省份
        $re['privilege'] = $data['privilege'];  //[?]特权，是一个数组类型
        $re['headimgurl'] = $data['headimgurl'];//头像icon链接，微信服务器链接

        return $re;
    }

    /**
     * HTTP GET 请求
     */
    private function httpGet($url)
    {
        $ch = curl_init();
        //地址都是HTTPS请求，需要验证SSL证书，CURLOPT_SSL_VERIFYPEER 默认为 true
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 去掉除英文大小写字母、数字、中文以外的所有字符
     */
    private function stringFilter($str)
    {
        //双字节中的中文字符
        $zh = '\x{4e00}-\x{9fa5}';
        return preg_replace("/[^A-Za-z0-9'.$zh.']+/u", "", $str);
    }
}