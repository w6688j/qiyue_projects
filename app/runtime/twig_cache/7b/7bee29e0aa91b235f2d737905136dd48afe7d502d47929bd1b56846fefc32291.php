<?php

/* WeiXinPay/wx_inside_pay.html */
class __TwigTemplate_518e243e1014058c6aa4e9573cbd5f8ff2926afe0fa0e0690e7a8093f301ce2e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"/>
    <meta name=\"viewport\" content=\"width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\">
    <meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\">
    <meta http-equiv=\"Pragma\" content=\"no-cache\">
    <meta http-equiv=\"Expires\" content=\"0\">
    <title>PP游戏账单支付</title>
    <link rel=\"stylesheet\" href=\"/public/css/payCtrl.css\">
</head>
<body>

<!--";
        // line 14
        if ($this->getAttribute(($context["return_arr"] ?? null), "status", array())) {
            echo "-->
<section data-bind=\"visible:tipViews.index()==3\" class=\"wrongpage\">
    <div class=\"pic_right\"></div>
    <p data-bind=\"html:tipViews.msg\">";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute(($context["return_arr"] ?? null), "msg", array()), "html", null, true);
            echo "</p>
</section>
<!--";
        } else {
            // line 19
            echo "-->
<section data-bind=\"visible:tipViews.index()==3\" class=\"wrongpage\">
    <div class=\"pic_warn\"></div>
    <p data-bind=\"html:tipViews.msg\">";
            // line 22
            echo twig_escape_filter($this->env, $this->getAttribute(($context["return_arr"] ?? null), "msg", array()), "html", null, true);
            echo "</p>
</section>
<!--";
        }
        // line 24
        echo "-->


<!--";
        // line 27
        if ($this->getAttribute(($context["return_arr"] ?? null), "status", array())) {
            echo "-->

<script type=\"text/javascript\">

    //调用微信JS api 支付
    function jsApiCall() {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', {
                \"appId\":\"";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute(($context["js_request"] ?? null), "appId", array()), "html", null, true);
            echo "\",
                \"timeStamp\":\"";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute(($context["js_request"] ?? null), "timeStamp", array()), "html", null, true);
            echo "\",
                \"nonceStr\":\"";
            // line 37
            echo twig_escape_filter($this->env, $this->getAttribute(($context["js_request"] ?? null), "nonceStr", array()), "html", null, true);
            echo "\",
                \"package\":\"";
            // line 38
            echo twig_escape_filter($this->env, $this->getAttribute(($context["js_request"] ?? null), "package", array()), "html", null, true);
            echo "\",
                \"signType\":\"MD5\",
                \"paySign\":\"";
            // line 40
            echo twig_escape_filter($this->env, $this->getAttribute(($context["js_request"] ?? null), "paySign", array()), "html", null, true);
            echo "\"
            },
            function (res) {
                // WeixinJSBridge.log(res.err_msg);
                if (res.err_msg == 'get_brand_wcpay_request:cancel') {
                    alert(\"取消付款\");
                    setTimeout(function () {
                        WeixinJSBridge.call('closeWindow');
                    }, 100);//立即关闭微信当前页面
                } else if (res.err_msg == \"get_brand_wcpay_request:fail\") {
                    //alert(res.err_code + res.err_desc +\"  \"+ res.err_msg);
                    alert(\"支付失败\");
                    setTimeout(function () {
                        WeixinJSBridge.call('closeWindow');
                    }, 100);
                } else if (res.err_msg == \"get_brand_wcpay_request:ok\") {
                    alert(\"支付成功\");
                    setTimeout(function () {
                        WeixinJSBridge.call('closeWindow');
                    }, 100);
                } else {
                    alert(res.err_msg);

                }
            }
        );
    }

    function callpay() {
        if (typeof WeixinJSBridge == \"undefined\") {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        } else {
            jsApiCall();
        }
    }
</script>
<script type=\"text/javascript\">
    window.onload = function () {
        if (typeof WeixinJSBridge == \"undefined\") {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', editAddress, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', editAddress);
                document.attachEvent('onWeixinJSBridgeReady', editAddress);
            }
        } else {
            //editAddress();
        }
    };

</script>
<script type=\"text/javascript\">
    callpay();
</script>


<!--";
        }
        // line 101
        echo "-->


</body>
</html>";
    }

    public function getTemplateName()
    {
        return "WeiXinPay/wx_inside_pay.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  155 => 101,  90 => 40,  85 => 38,  81 => 37,  77 => 36,  73 => 35,  62 => 27,  57 => 24,  51 => 22,  46 => 19,  40 => 17,  34 => 14,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "WeiXinPay/wx_inside_pay.html", "/home/wwwroot/default/qiyue_projects/app/module/pay/view/WeiXinPay/wx_inside_pay.html");
    }
}
