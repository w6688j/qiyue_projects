<?php

/* Login/index.html */
class __TwigTemplate_b9f559c839413befbe71dfd290096edcd0e4324a7cdcbb0db1406c62979adf82 extends Twig_Template
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
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <title>PP游戏中心</title>
    <script type=\"text/javascript\" src=\"/public/js/jquery.v1.7.1.js\"></script>
    <script type=\"text/javascript\" src=\"/public/js/jquery.json.js\"></script>
    <script type=\"text/javascript\">
        var uid = \"";
        // line 9
        echo twig_escape_filter($this->env, ($context["account"] ?? null), "html", null, true);
        echo "\";
        var pwd = \"";
        // line 10
        echo twig_escape_filter($this->env, ($context["password"] ?? null), "html", null, true);
        echo "\";

        function vc_register_auto_login(){
            var obj = new Object();
            obj.cmd  = \"weblogin\";
            obj.uid = uid;
            obj.pwd = pwd;
            var msg = \$.toJSON(obj);
            var url = \"app://\"+msg;\t\t\t//浏览器捕捉内嵌页动作调用exe
            window.location.href = url;
        }

        \$(document).ready(function(){
            vc_register_auto_login();
        });

    </script>
</head>

<body>

<div style=\"text-align: center;margin-top: 100px;\">正在登录中...</div>

</body>
</html>";
    }

    public function getTemplateName()
    {
        return "Login/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  33 => 10,  29 => 9,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "Login/index.html", "/home/wwwroot/default/qiyue_projects/app/module/gamefish/view/Login/index.html");
    }
}
