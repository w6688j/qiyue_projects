<?php

/* MixedPay/payCtrl.html */
class __TwigTemplate_d641e1453ac69f16cac70a2b68e3f6dec4e85d0aae28fc72f1c8906ccdedfcf2 extends Twig_Template
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
        echo "<html><head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\">
    <meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\">
    <meta http-equiv=\"Pragma\" content=\"no-cache\">
    <meta http-equiv=\"Expires\" content=\"0\">

    <title>PP游戏支付</title>

    <link rel=\"stylesheet\" href=\"/public/css/payCtrl.css\">

</head>
<body>
<div  class=\"paypage_wp\">
    <div class=\"cont\">
        <div class=\"price_box\">
            <p data-bind=\"text:tradeMap().name\">PP游戏支付</p>
            <p class=\"totalprice\"><i data-bind=\"text:tradeMap().priceTxt\" class=\"num\">";
        // line 18
        echo twig_escape_filter($this->env, ($context["total_fee"] ?? null), "html", null, true);
        echo "</i>元</p>
        </div>
        <div class=\"orderinfo_box\">
            <div class=\"orderinfo open\">
                <p>下单帐号：
                    <span data-bind=\"text:userInfo.username\">";
        // line 23
        echo twig_escape_filter($this->env, ($context["trade_no"] ?? null), "html", null, true);
        echo "</span>
                </p>
            </div>

        </div>
    </div>
    <div data-bind=\"foreach :paytypename\" class=\"payway_box\">
        <a  class=\"payway\" href=\"";
        // line 30
        echo twig_escape_filter($this->env, ($context["alipay_url"] ?? null), "html", null, true);
        echo "\" data-tradeid=\"1403\" data-clickid=\"N2\" >
            <i data-bind=\"css:\$data.ico\" class=\"ico_wx ico_alipay\"></i>
            <span data-bind=\"text:\$data.name\">支付宝支付</span>
        </a>

        <!--<a class=\"payway\" href=\"";
        // line 35
        echo twig_escape_filter($this->env, ($context["weixin_url"] ?? null), "html", null, true);
        echo "\" data-tradeid=\"1403\" data-clickid=\"W4\">
            <i data-bind=\"css:\$data.ico\" class=\"ico_wx\"></i>
            <span data-bind=\"text:\$data.name\">微信支付(支持6.0.2及以上版本使用)</span>
        </a>-->
    </div>

</div>

</body></html>";
    }

    public function getTemplateName()
    {
        return "MixedPay/payCtrl.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  64 => 35,  56 => 30,  46 => 23,  38 => 18,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "MixedPay/payCtrl.html", "/home/wwwroot/default/qiyue_projects/app/module/pay/view/MixedPay/payCtrl.html");
    }
}
