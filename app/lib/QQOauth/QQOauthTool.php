<?php
/**
 * 微信扫码登录
 * 支持OAuth 2.0
 * @author 3Dnn 20160911
 */


namespace app\lib\QQOauth;

use atphp\util\LogUtil;

class QQOauthTool
{
    /**
     * GET 请求
     */
    public function get($sUrl, $aGetParam)
    {
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $aGet = array();
        foreach ($aGetParam as $key => $val) {
            $aGet[] = $key . "=" . urlencode($val);
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl . "?" . join("&", $aGet));
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);

        self::dataLog($sUrl, $aGetParam, array(), array(), $aStatus, $sContent);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return FALSE;
        }
    }

    /**
     * POST 请求
     */
    public function post($sUrl, $aPOSTParam)
    {
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $aPOST = array();
        foreach ($aPOSTParam as $key => $val) {
            $aPOST[] = $key . "=" . urlencode($val);
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, TRUE);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, join("&", $aPOST));
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);

        self::dataLog($sUrl, array(), $aPOSTParam, array(), $aStatus, $sContent);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return FALSE;
        }
    }

    /**
     * 上传图片
     */
    public function upload($sUrl, $aPOSTParam, $aFileParam)
    {
        set_time_limit(0);//防止请求超时
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $aPOSTField = array();
        foreach ($aPOSTParam as $key => $val) {
            if (preg_match("/^@/i", $val) > 0) {
                $aPOSTField[$key] = " " . $val;
            } else {
                $aPOSTField[$key] = $val;
            }
        }
        foreach ($aFileParam as $key => $val) {
            $aPOSTField[$key] = "@" . $val; //此处对应的是文件的绝对地址
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_POST, TRUE);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aPOSTField);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);

        self::dataLog($sUrl, array(), $aPOSTParam, $aFileParam, $aStatus, $sContent);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return FALSE;
        }
    }

    /**
     * 下载文件
     */
    public function download($sUrl, $sFileName)
    {
        $oCurl = curl_init();
        set_time_limit(0);
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_USERAGENT, $_SERVER["USER_AGENT"] ? $_SERVER["USER_AGENT"] : "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.7) Gecko/20100625 Firefox/3.6.7");
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_REFERER, $sUrl);
        curl_setopt($oCurl, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);

        self::dataLog($sUrl, array(), array(), array(), $aStatus, "");
        file_put_contents($sFileName, $sContent);
        return (intval($aStatus["http_code"]) == 200);
    }

    /**
     * 通讯信息打印
     * @param string $sUrl 请求地址
     * @param array $aGetParam GET请求信息
     * @param array $aPOSTParam POST请求信息
     * @param array $aFileParam 文件参数信息
     * @param array $aStatus 返回状态
     * @param string $sContent 返回结果
     */
    public static function dataLog($sUrl, $aGetParam, $aPOSTParam, $aFileParam, $aStatus, $sContent)
    {
        if (false) {
            $str = "";
            $str .= "url:" . $sUrl . "\r\n";
            if (!empty($aGetParam)) {
                $str .= "GET:" . var_export($aGetParam, TRUE) . "\r\n";
            }
            if (!empty($aPOSTParam)) {
                $str .= "POST:" . var_export($aPOSTParam, TRUE) . "\r\n";
            }
            if (!empty($aFileParam)) {
                $str .= "FILE:" . var_export($aFileParam, TRUE) . "\r\n";
            }
            if (!empty($aStatus)) {
                $str .= "status:" . var_export($aStatus, TRUE) . "\r\n";
            }
            if (!empty($sContent)) {
                $str .= "ret:" . $sContent . "\r\n";
            }

            $path = "qq_oauth/access.log";;
            LogUtil::write("QQ授权信息 :{$str}", LogUtil::INFO, LogUtil::FILE, $path);

        }
    }
}

?>