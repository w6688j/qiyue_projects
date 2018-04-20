<?php

/* HeePayNotice/return_url.html */
class __TwigTemplate_712aec29c3dec6995c3c6ef05c7a1465b6024b815ea04bcc65c2cca40f356a34 extends Twig_Template
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
    <meta name=\"viewport\" content=\"width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\">
    <meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store, must-revalidate\">
    <meta http-equiv=\"Pragma\" content=\"no-cache\">
    <meta http-equiv=\"Expires\" content=\"0\">
    <title>PP游戏支付通知</title>

    <style type=\"text/css\">
        *{
            padding: 0;
            margin: 0;
        }
        body{
            background: #eeeeee;
        }
        div.wrap{
            width: 280px;
            height:300px;
            margin: 0 auto;
            /*border:1px solid red;*/
            margin-top: 100px;
        }
        
        div.right img,div.error img{
            float: left;
            width: 70px;
        }
        
        div.right div,div.error div{
            font-weight: bold;
            font-size: 20px;
            margin-left: 13px;
            margin-top: 4px;
            /* border: 1px solid red; */
            display: block;
            float: left;
            color: #adadad;

        }
        div.right div span{
            color: #2aa515;
            font-size: 25px;
        }
        div.info a{
            width: 120px;
            display: block;
            margin-left: 20px;
            /*border: 1px solid red;*/
            float: left;
            margin-top: 6px;
            color: #9E9E9E;
        }
        div.right a:hover{
            color:green;
        }
    </style>
</head>
<body>

<div class=\"wrap\">

    ";
        // line 65
        if ($this->getAttribute(($context["return_array"] ?? null), "status", array())) {
            // line 66
            echo "    <div class=\"info right\">
        <img src=\"/public/images/duihao.png\" />
        <div>支付成功,您成功支付 <span>";
            // line 68
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["return_array"] ?? null), "data", array()), "pay_amt", array()), "html", null, true);
            echo "</span> 元 </div>
        <a href=\"http://www.pp158.com\" target=\"_blank\">返回首页>></a>
    </div>

    ";
        } else {
            // line 73
            echo "
    <div class=\"info error\">
        <img src=\"/public/images/cuowu.png\" />
        <div>支付失败,请重新支付 </div>
        <a href=\"http://www.pp158.com\" target=\"_blank\">返回首页>></a>
    </div>
    ";
        }
        // line 80
        echo "</div>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "HeePayNotice/return_url.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  108 => 80,  99 => 73,  91 => 68,  87 => 66,  85 => 65,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "HeePayNotice/return_url.html", "/home/wwwroot/default/qiyue_projects/app/module/pay/view/HeePayNotice/return_url.html");
    }
}
