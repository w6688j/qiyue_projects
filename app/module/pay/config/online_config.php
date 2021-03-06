<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/3
 * Time: 11:54
 */
//这里面的配置优先,公共配置如果相同会覆盖掉

return array(
    //支付宝配置信息
    'alipay' => [
        //应用ID,您的APPID。
        'app_id' => "2017021605702899",
        //商户私钥，您的原始格式RSA私钥
        'merchant_private_key' => "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDR9oSrUqNIafqQDd8aTsYgfrgGRAC/tZq6uizqn07nNE3HQQGhUQDR8DKwlqyokH44oy2i7E0Igs4f9w+IZd7KNoHZoVv+9DFTGm9hjUUt58rRR7vFTz4lOGPyTdLlWt9DjbLCztZkAC/dlQKM++LASHugt2jrx/pWDsC/cF5DKsooIkJyrVqU8gE0FafLjRjgqT6YasLAYZCMFVVrSgfP6qL26nfVFc772anPrBnNqoHrEVegxwl6F4yUj1jkiPZ8VZvPesaaXrT/DLAHCBebU8qNXwEuy1iboIC7KhgohFXRcS9yoq/A6SaOl21wVnIQMHKe6KDqnjdVuOfnFOm9AgMBAAECggEBAKHOw4tskh1aXxjpN8iEcLfWMGfTvGgBwo6or7jYsOwJKO+nr2Pskx83ZzkjxUfaowtu5dqrFOq8M7H6qmPJbhqUSxm2+rjO47xRoQii4G9yNKF4EMMMQK5aeNvhEehjUbAz4VG0KRDD/B9dmKuh6quYsH25GwiqAsiTcgOWy24igzZqJsOjeHYNQDBYLTmGOXNBMUV/OTfm8GScB5JvYO/Bt73/JuFziQoI+BLsu4KCS/TvTNxc8foP6ZJN3N2TF8S6sYkn8Y4UPC6Np4V0puvJiNW5bQ5PVb7Snlol3GsbJzBHtUDVR5yL0Yse+7poNzAlZQccFBePTR0dciCIzwECgYEA/AVlml3/NmithN8jkVPyx1pjFMHYaprQCohWJK6BasDa7MH4/EygN2MRPPpvyt//lD4TnUt3ESDG98gbKtHXvWNunVrwcmOHEbPK0NMY+YRQhF5rTaohuYqQb4+LUnDYMAzPdsiEaMQhCLsyWKIupYd/VpGFCDmFhtk2fT5FJKECgYEA1UciKqbUgDFOqZO+u9xM7K4hkJEy6DlbGrj7Nos3j3091ularyO4x8rPnKwDReZZdjsFHnuAQLwqS0qLuSanLiEm3lIGbXI1RAIq3A39JVNmJrsUfecdeKzscDHQJhbDdkrbS0d1UzGZui4uzSBzhG9TOJjAptsHIMQlzhuFk50CgYA1Nbp+/iudRDqmnCo5S90thAL5ZfgYgfk80A5IDmQasv9GD6pPMqp9JziDhGjID8U5emXPxxgrkJ0RwbkisE15mh3HsWSk8iiZbzl6H6fdPrd1Sy4iprIaJ4xZUZhb3qF2e63t6WYzbH2BB20Y050/q70R/QV2eBaeIwxjIxucIQKBgQCnVV82LZyOU6FPJS9Bv4/PJrQI9BMH5nBXYAkhe0sFVpMdOgs/XHyOrM4FM2SCBD/uplHW37j5kWhxmDRV+UCzzajsR1jpp/CowjaUXpleHrgH7UIfoiQaWTpMsJiKOdPzzLmPr6oastDVJYTsxeg8YQffYOHOSAyopZyvwCImdQKBgFNe09vaEKCTCmEK/Jrtkdry2tV+Mg0TX8em2cmmGKTV6SUqRDqaRpuc3W6FTMpwQHgVOSEk5ufw/90dLUu8sPs9duw0yE654DOxT4udTE80iaZQCd2nURbGRxqahGo1Tu/Ke24CtmWeAG+qaddQF3rRn+SzdwcZvMM9h+I1sy+k",
        //支付宝公钥
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgdQkEf8cDpNRZvbz/OLLG49697zofurSqmpSiSATuThuXHE09zs82Af+4GyVHyzuQOK9kQFRFJVqGetzir5J+/jyj8E7zS8H+E8y22kHJF1meYQ5seneqEaIV35tj6kuBlMOFcb8ub/wU8z5qYcVdyJOI6EgEOfXS893j0Cpmb3Sfow79OvTm0omSSzDA+JfbOOUXwwb4dWxv7464QNPz/vDUXEJp2pmdepLM5O00+vGoQ1oQjT9kD9duVumwUyeMefdgN3vUP7S89PX/hR+sEEf5j9p8TV/2ZSipf/k/Lkkg1NVD+NOAD10evZbJE0Oer4ikwY6GNmSQzsqqedZKQIDAQAB",

        //异步通知地址
        'notify_url' => "http://pay.pp158.com/AlipayNotice/notify_url",
        //同步跳转
        'return_url' => "http://pay.pp158.com/AlipayNotice/return_url",
        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
    ],

    //微信支付配置--网页端

    'weixin_web' => [
        //异步通知地址
        'notify_url' => "http://pay.pp158.com/WeixinNoticeWeb/notify_url",
        'appid' => 'wxcb8a76fe730b5964',//绑定支付的APPID
        'mchid' => '1352470102',//商户号
        'key' => 'VT2Fp8RC7pEkOseWEyVveRhPn81m7rZR',//商户支付密钥
        'appsecret' => '21f9fc8f14894323b0a6612611bd0903',
        //证书路径
        'sslcert_path' => APP_PATH . 'lib/WeiXinCertWeb/apiclient_cert.pem',
        'sslkey_path' => APP_PATH . 'lib/WeiXinCertWeb/apiclient_key.pem',
    ],

    //微信支付App端
    'weixin_app' => [
        //异步通知地址
        'notify_url' => "http://pay.pp158.com/WeixinNoticeApp/notify_url",
        'appid' => 'wx95a9edfdca7a948d',//绑定支付的APPID
        'mchid' => '1401776502',//商户号
        'key' => 'WYsGnbXC3XlBIOHjJ1XxUwNDAvbyEeIY',//商户支付密钥
        'appsecret' => '569781f3163f76072e9751d8ac12c982',
        //证书路径
        'sslcert_path' => APP_PATH . 'lib/WeiXinCertApp/apiclient_cert.pem',
        'sslkey_path' => APP_PATH . 'lib/WeiXinCertApp/apiclient_key.pem',
    ],


//    //微信测试--沙盒
//    'weixin_test' => [
//        'appid' => 'wxbb880c58be6474de',//绑定支付的APPID
//        'appsecret' => '190df0ab5dae88c743c34ba917751ac1',
//    ],


    //汇付宝配置
    'heepay' => [
        "pay_url" => 'https://pay.Heepay.com/Payment/Index.aspx',//网银支付接口地址
        'query_url' => 'https://query.heepay.com/Payment/Query.aspx',
        'agent_id' => '2070349',
        'sign_key' => 'AF1BB4688A994E7894465AC9',
        'pay_type' => 20,//银行支付
        'version' => 1,
        //异步通知地址
        'notify_url' => "http://pay.pp158.com/HeePayNotice/notify_url",
        //同步跳转
        'return_url' => "http://pay.pp158.com/HeePayNotice/return_url",
    ],

    'mixed_pay' => [
        "key" => "7pEkOseWEyv9GD6pPMqp9JVE789476072e975sqqedZKQI",//这里是我自己定义的加密key,防止恶意用户篡改参数
    ],

    'log_path' => RUNTIME_PATH . "paylog/",//日志路径
);