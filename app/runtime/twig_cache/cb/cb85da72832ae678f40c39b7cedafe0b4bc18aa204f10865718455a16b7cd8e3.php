<?php

/* Register/index.html */
class __TwigTemplate_0507ae436699cbe1f0b0f376c7d72a9d9662a4288d0342fed261ad195c14d663 extends Twig_Template
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
        echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta http-equiv=\"x-ua-compatible\" content=\"ie=edge\">
    <title>pp游戏用户注册</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/register_index.css?version=";
        // line 8
        echo twig_escape_filter($this->env, ($context["version"] ?? null), "html", null, true);
        echo "\"/>

</head>
<body>
<div class=\"container\">

    <div class=\"login\">
        <form action=\"/index.php?m=postregister\" class=\"form\" id=\"register_form\">
            <input name=\"token\" type=\"hidden\" value=\"";
        // line 16
        echo twig_escape_filter($this->env, ($context["token"] ?? null), "html", null, true);
        echo "\">
            <input name=\"netbarid\" type=\"hidden\" value=\"";
        // line 17
        echo twig_escape_filter($this->env, ($context["netbarid"] ?? null), "html", null, true);
        echo "\">
            <div class=\"item\">
                <div class=\"label\">账&nbsp;&nbsp;号</div>
                <div class=\"input\">
                    <input autocomplete=\"false\" name=\"account\" id=\"account\" type=\"text\" datatype=\"nicheng6-18|e\"
                           ajaxurl=\"/Register/checkAccount\" nullmsg=\"账号只能为6-18位的数字或字母或邮箱格式！\"
                           errormsg=\"账号只能为6-18位的数字或字母或邮箱格式！\" placeholder=\"6-18位的数字或字母或邮箱\">
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>
            <div class=\"item\">
                <div class=\"label\">昵&nbsp;&nbsp;称</div>
                <div class=\"input\">
                    <input autocomplete=\"false\" name=\"nickname\" type=\"text\" id=\"nickname\" datatype=\"*\"
                           ajaxurl=\"/Register/checkName\" nullmsg=\"昵称为4-20位字符\"
                           errormsg=\"昵称为4-20位字符！\" placeholder=\"4-20位字符\">
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>
            <div class=\"item\">
                <div class=\"label\">密&nbsp;&nbsp;码</div>
                <div class=\"input\">
                    <input autocomplete=\"false\" name=\"password\" type=\"password\" id=\"password\" datatype=\"*6-12\"
                           nullmsg=\"密码范围在6~12位之间！\"
                           errormsg=\"密码范围在6~12位之间\" placeholder=\"请输入密码\"/>
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>
            <div class=\"item\">
                <div class=\"label\">确认密码</div>
                <div class=\"input\">
                    <input autocomplete=\"false\" name=\"repassword\" type=\"password\" id=\"repassword\" placeholder=\"确认密码\"
                           datatype=\"*\"
                           recheck=\"password\" nullmsg=\"请再输入一次密码\" errormsg=\"您两次输入的账号密码不一致!\"/>
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>


            <div class=\"item\">
                <div class=\"label\">真实姓名</div>
                <div class=\"input\">
                    <input autocomplete=\"false\" name=\"realname\" type=\"text\" datatype=\"z2-4\" nullmsg=\"请输入真实姓名！\"
                           errormsg=\"请输入真实姓名\" placeholder=\"请输入真实姓名\">
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>

            <div class=\"item\">
                <div class=\"label\">身份证号</div>
                <div class=\"input\">
                    <input autocomplete=\"false\" name=\"idcard\" type=\"text\" ajaxurl=\"/Register/checkIdCard\"
                           placeholder=\"18位数字或17位数字+x\" datatype=\"idcard\" nullmsg=\"请填写身份证号码！\" errormsg=\"您填写的身份证号码不对！\"
                           id=\"idcard\">
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>

            <div class=\"item\">
                <div class=\"label\">验证码</div>
                <div class=\"input\">
                    <input type=\"hidden\" id=\"verify_input\" value=\"\"/>
                    <div class=\"Verify\">
                        <!--放置验证码代码-->
                    </div>
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>

            <div class=\"item\">
                <div class=\"label\">性 别</div>
                <div class=\"input\">
                    <div class=\"radio\">
                        <input type=\"radio\" name=\"sex\" value=\"0\" id=\"male\" checked=checked><label for=\"male\">男 </label>
                    </div>
                    <div class=\"radio\">
                        <input type=\"radio\" name=\"sex\" value=\"1\" id=\"female\"><label for=\"female\">女</label>
                    </div>
                </div>
            </div>

            <div class=\"item\">
                <div class=\"label\"></div>
                <div class=\"input mini-input\">
                    <input type=\"checkbox\" name=\"license\" id=\"license\" checked=checked disabled value=\"1\"/>
                    <a href=\"http://www.pp158.com/Register/Service.html\" target=\"_blank\" class=\"read_license\"
                       for=\"license\">请阅读并勾选同意服务条款</a>
                </div>
                <div class=\"Validform_checktip\"></div>
            </div>
            <div class=\"item\">

                <button type=\"submit\" class=\"submit clickable\">免费注册</button>
            </div>
        </form>
    </div>
</div>

<!--基本js-->
<script src=\"/public/js/jquery.v1.7.1.js?version=";
        // line 116
        echo twig_escape_filter($this->env, ($context["version"] ?? null), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
<script src=\"/public/js/Validform_v5.3.2_min.js?version=";
        // line 117
        echo twig_escape_filter($this->env, ($context["version"] ?? null), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
<script src=\"/public/js/Valid_v3.js?version=";
        // line 118
        echo twig_escape_filter($this->env, ($context["version"] ?? null), "html", null, true);
        echo "\" type=\"text/javascript\"></script>

<!--点选验证码-->
<link href=\"/public/js/VerifyImg/verify_img.css?version=";
        // line 121
        echo twig_escape_filter($this->env, ($context["version"] ?? null), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\"/>
<script src=\"/public/js/VerifyImg/verify_img.js?version=";
        // line 122
        echo twig_escape_filter($this->env, ($context["version"] ?? null), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
<!--[if lt IE 10]>
<script src=\"/public/js/VerifyImg/PIE.js?version=";
        // line 124
        echo twig_escape_filter($this->env, ($context["version"] ?? null), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
<![endif]-->
<script type=\"text/javascript\">
    //加载验证码
    function check_verify() {
        var verify_input = \$(\"#verify_input\").val();

        if (!verify_input) {
            VerifyImg.showErrorMsg();
            return false;
        }

        return true;
    }
    VerifyImg.init({
        verifyUrl: '/VerifyCode/loadImg',//加载验证码路径
        selCodesUrl: '/VerifyCode/selCodes',//需要选择验证码的路径
        getCheckImgUrl: '/VerifyCode/CheckImg',//验证验证码
        success: function (res) {
            \$(\"#verify_input\").val(VerifyImg.selData.join(\",\"));
        },
        error: function (res) {
            //错误
            \$(\"#verify_input\").val('');
        }
    });


    function argee(obj) {
        if (\$(\"input[type='checkbox']\").is(':checked')) {
            \$(\"input[type='checkbox']\").closest('td').next('td').html(\"\");
            return true;
        } else {
            \$(\"input[type='checkbox']\").closest('td').next('td').css('color', 'red').html(\"请同意《服务条款》！\");
            return false;
        }
    }


</script>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "Register/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  168 => 124,  163 => 122,  159 => 121,  153 => 118,  149 => 117,  145 => 116,  43 => 17,  39 => 16,  28 => 8,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "Register/index.html", "/home/wwwroot/default/qiyue_projects/app/module/gamefish/view/Register/index.html");
    }
}
