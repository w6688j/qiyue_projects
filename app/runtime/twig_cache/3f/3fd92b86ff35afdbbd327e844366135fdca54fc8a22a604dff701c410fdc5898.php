<?php

/* WeixinLogin/callback.html */
class __TwigTemplate_678c146b91c416cab36fbc43b7f2b4574157bcdb369fef6d87008931ad2dab33 extends Twig_Template
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
    <meta charset=\"UTF-8\">
    <title>微信登录</title>
    <script type=\"text/javascript\" src=\"/public/js/jquery.v1.7.1.js\"></script>
    <script type=\"text/javascript\" src=\"/public/js/jquery.json.js\"></script>
</head>
<body>

";
        // line 11
        if ($this->getAttribute(($context["return_array"] ?? null), "status", array())) {
            // line 12
            echo "<script type=\"text/javascript\">
    var openid = \"";
            // line 13
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["return_array"] ?? null), "data", array()), "openid", array()), "html", null, true);
            echo "\";
    var qq_nickname = \"";
            // line 14
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["return_array"] ?? null), "data", array()), "nickname", array()), "html", null, true);
            echo "\";

    function vc_qq_auto_login() {
        var obj = new Object();
        obj.cmd = \"wechatlogin\";
        obj.str1 = openid;
        obj.str2 = qq_nickname;
        var msg = \$.toJSON(obj);
        var url = \"app://\" + msg;
        window.location.href = url;
    }

    \$(document).ready(function () {
        vc_qq_auto_login();
    });

</script>
<div style=\"text-align: center;margin-top: 100px;\">正在登录中...</div>

";
        } else {
            // line 34
            echo "

<div style=\"text-align: center;margin-top: 100px;\">";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute(($context["return_array"] ?? null), "msg", array()), "html", null, true);
            echo "</div>

";
        }
        // line 39
        echo "
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "WeixinLogin/callback.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 39,  67 => 36,  63 => 34,  40 => 14,  36 => 13,  33 => 12,  31 => 11,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "WeixinLogin/callback.html", "/home/wwwroot/default/qiyue_projects/app/module/gamefish/view/WeixinLogin/callback.html");
    }
}
