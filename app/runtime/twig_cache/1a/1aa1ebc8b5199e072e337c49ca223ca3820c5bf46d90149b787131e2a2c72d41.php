<?php

/* Gift/index.html */
class __TwigTemplate_8f3cc0bdbd830671ea6c0ebbfb00bfe736bdeec4bd4c8dc352fc47242efa608d extends Twig_Template
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
<html lang=\"en\" style=\"overflow: hidden;\">
<head>
    <meta charset=\"UTF-8\">
    <title>新老用户反馈活动</title>
    <link type=\"text/css\" href=\"/public/gift/css/gift.css?id=";
        // line 6
        echo twig_escape_filter($this->env, ($context["time"] ?? null), "html", null, true);
        echo "\" rel=\"stylesheet\"/>
    <script type=\"text/javascript\" src=\"http://libs.baidu.com/jquery/1.8.2/jquery.min.js\"></script>
</head>
<body style=\"overflow: hidden;\">


<div class=\"wrap\">

    <div class=\"question\">
        <div class=\"img\"></div>

        <div class=\"info\">
            <img src=\"/public/gift/images/jiantou.png\" />
            活动赠送的金币不能兑换奖品，只可作为游戏消耗。若存在违规行为，官方有权扣除相应金币。
            <span> 本活动的最终解释权归PP游戏中心所有</span>
        </div>

    </div>
    <div class=\"left\">
        <div class=\"old_btn\" onclick=\"Gift.selectBox(1)\">
            <div class=\"old_anniu yes_press\">
                历史充值回馈
            </div>
            <!--<img src=\"/public/gift/images/old_btn1.png\" class=\"old_btn1\"/>-->
            <!--<img src=\"/public/gift/images/old_btn2.png\" class=\"old_btn2\"/>-->
        </div>
        <div class=\"new_btn\" onclick=\"Gift.selectBox(2)\">
            <div class=\"new_anniu no_press\">
                新手七日好礼
            </div>

            <!--<img src=\"/public/gift/images/new_btn1.png\" class=\"new_btn1\"/>-->
            <!--<img src=\"/public/gift/images/new_btn2.png\" class=\"new_btn2\"/>-->
        </div>
    </div>
    <div class=\"right\">
        <div class=\"box\">
            <!--<img class=\"top_header\" src=\"/public/gift/images/top_header.png\"/>-->
            <div class=\"inner_box old_box\">
                <ul class=\"header\">
                    <li>累计金额(元)</li>
                    <li>奖励</li>
                    <li>操作</li>
                </ul>
                ";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["config"] ?? null), "old_player", array()));
        foreach ($context['_seq'] as $context["key"] => $context["value"]) {
            // line 51
            echo "                <ul class=\"border\" ";
            if (($context["key"] == 4)) {
                echo " style=\"border: none;\" ";
            }
            echo ">
                    <li class=\"color_black\">";
            // line 52
            echo twig_escape_filter($this->env, $this->getAttribute($context["value"], "money", array()), "html", null, true);
            echo "</li>
                    <li class=\"color_black\">";
            // line 53
            echo twig_escape_filter($this->env, ($this->getAttribute($context["value"], "jinbi", array()) / 10000), "html", null, true);
            echo "W</li>
                    <li>


                        ";
            // line 57
            if (($context["old_time_flag"] ?? null)) {
                // line 58
                echo "                        ";
                if (($this->getAttribute($context["value"], "is_get", array()) == 1)) {
                    // line 59
                    echo "                        <span class=\"yellow_bg btn_img\" onclick=\"Gift.getGrade(this,1,'";
                    echo twig_escape_filter($this->env, ($context["key"] + 1), "html", null, true);
                    echo "')\">可领取</span>
                        ";
                } elseif (($this->getAttribute(                // line 60
$context["value"], "is_get", array()) == 2)) {
                    // line 61
                    echo "                        <span class=\"gray_bg btn_img\">已领取</span>
                        ";
                } else {
                    // line 63
                    echo "                        <span class=\"gray_bg btn_img\">未达到</span>
                        ";
                }
                // line 65
                echo "                        ";
            } else {
                // line 66
                echo "                        <!--不在时间范围内,让你是可领取状态-->
                        <span class=\"gray_bg btn_img\">可领取</span>
                        ";
            }
            // line 69
            echo "
                        <!--<img src=\"/public/gift/images/btn_ylq.png\" class=\"btn_img\"/>-->

                    </li>
                </ul>

                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 76
        echo "            </div>
            <div class=\"inner_box new_box\" style=\"display: none;\">
                <div class=\"center\">
                    <div class=\"item fl\">
                        <div class=\"img_div\">
                            <img src=\"/public/gift/images/money.png\"/>
                        </div>
                        <p>金币X";
        // line 83
        echo twig_escape_filter($this->env, ($this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "jinbi", array()) / 10000), "html", null, true);
        echo "W</p>
                    </div>
                    <div class=\"plus fl\"> +</div>
                    <div class=\"item fl\">
                        <div class=\"img_div\">
                            <img src=\"/public/gift/images/vip.png\"/>
                        </div>
                        <p>VIP";
        // line 90
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "vip", array()), "html", null, true);
        echo "X";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "vip_days", array()), "html", null, true);
        echo "天</p>
                    </div>
                    <div class=\"plus fl\"> +</div>
                    <div class=\"item fl\">
                        <div class=\"img_div\">
                            <img src=\"/public/gift/images/qb.png\"/>
                        </div>
                        <p>";
        // line 97
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "qb", array()), "html", null, true);
        echo "Q币</p>
                    </div>
                </div>

                <h2 class=\"jindu\">当前进度 :";
        // line 101
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "user_login_num", array()), "html", null, true);
        echo "/";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "login_day", array()), "html", null, true);
        echo " </h2>

                ";
        // line 103
        if (($context["new_time_flag"] ?? null)) {
            // line 104
            echo "                      ";
            if (($this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "is_get", array()) == 1)) {
                // line 105
                echo "                        <div class=\"img_box_lg yellow_bg\" onclick=\"Gift.getGrade(this,2,0)\"> 可领取 </div>
                       ";
            } elseif (($this->getAttribute($this->getAttribute(            // line 106
($context["config"] ?? null), "new_player", array()), "is_get", array()) == 2)) {
                // line 107
                echo "                             <div class=\"img_box_lg gray_bg\">已领取</div>
                      ";
            } else {
                // line 109
                echo "                            <div class=\"img_box_lg gray_bg\"> 未达到 </div>
                      ";
            }
            // line 111
            echo "                ";
        } else {
            // line 112
            echo "                  <div class=\"img_box_lg gray_bg\"> 可领取 </div>
                ";
        }
        // line 114
        echo "            </div>

        </div>

        <span class=\"old_span_notice notice\">
             <div>活动时间:";
        // line 119
        echo twig_escape_filter($this->env, ($context["old_start_time"] ?? null), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, ($context["old_end_time"] ?? null), "html", null, true);
        echo "</div>
            <div>
                活动详情: <b>1.</b>活动开始前，根据历史充值的累计充值金额可获得金币奖励<br/>
                <b>2.</b>可领取多档充值奖励（如：历史充值200，可同时获得充值50元和200元奖励）

            </div>
        </span>
        <span class=\"new_span_notice notice\">
              <div>活动时间:";
        // line 127
        echo twig_escape_filter($this->env, ($context["new_start_time"] ?? null), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, ($context["new_end_time"] ?? null), "html", null, true);
        echo "</div>
            <!--<div>-->
                <!--活动详情: <b>1.</b>活动开始前，根据历史充值的累计金额可获得金币奖励<br/>-->
                  <!--<b>2</b>：可领取多档充值奖励（如：历史充值200，可同时获得充值50元和200元奖励）-->
            <!--</div>-->
             <div>活动详情: 自活动上线,新注册用户连续登陆";
        // line 132
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["config"] ?? null), "new_player", array()), "login_day", array()), "html", null, true);
        echo "天,即可领取豪华礼包</div>
        </span>
    </div>
</div>


<!--加载layer     -->
<script type=\"text/javascript\" src=\"/public/gift/plugin/layer/layer.js\"></script>
<script type=\"text/javascript\">
    //layui.use('layer', function(){
    //        layer.ready(function(){
    //             layer.msg('木有了，看看别的吧！',{icon:'5'});
    //        });
    //    });
    // layer.msg('数据处理中...', {time: 0, icon: 16, shade: [0.8, '#393D49'], scrollbar: false});

    var old_time_flag = '";
        // line 148
        echo twig_escape_filter($this->env, ($context["old_time_flag"] ?? null), "html", null, true);
        echo "';
    var new_time_flag = '";
        // line 149
        echo twig_escape_filter($this->env, ($context["new_time_flag"] ?? null), "html", null, true);
        echo "';

    var Gift = {
        selectBox: function (type) {
            \$(\".old_anniu\").removeClass(\"yes_press\").addClass(\"no_press\");
            \$(\".new_anniu\").removeClass(\"yes_press\").addClass(\"no_press\");
            \$(\"span.notice\").hide();
            \$(\".old_box\").hide();
            \$(\".new_box\").hide();
            if (type == 1) {
                //显示老用户
                \$(\".old_anniu\").addClass(\"yes_press\");
                \$(\".old_box\").show();
                \$(\"span.old_span_notice\").show();
            } else {
                //显示新用户
                \$(\".new_anniu\").addClass(\"yes_press\");
                \$(\"span.new_span_notice\").css({\"display\":\"block\"});
                \$(\".new_box\").show();

            }
        },

        //领取奖励
        getGrade: function (obj,grade_type, grade_level) {
            if (!this.checkTime(grade_type)) {
                return false;
            }
            \$.ajax({
                url: '/Gift/getGrade',//请求接口
                data: {grade_type: grade_type, grade_level: grade_level},
                type: 'post',
                dataType: 'json',
                beforeSend: function () {
                    layer.msg('正在领取奖励', {icon: 16});
                },
                success: function (res) {
                    if (res.status) {

                        if(grade_type==2){
                            layer.msg(res.info, {icon: 1,time: 2000});
                        }else{
                            layer.msg(res.info, {icon: 1});
                        }
                        \$(obj).removeClass(\"yellow_bg \").addClass(\"gray_bg\").text(\"已领取\");

                        \$(obj).removeAttr(\"onclick\");
                    } else {
                        layer.msg(res.info, {icon: 2});
                    }
                },
                error: function () {
                    layer.msg('操作失败，请重试', {time: 1500, icon: 7, shade: [0.8, '#393D49'], scrollbar: false});
                }
            });

        },

        checkTime: function (type) {

            if(type==1){
                //这里是老用户
                if (!old_time_flag) {
                    layer.ready(function () {
                        layer.msg('历史充值活动已结束!', {icon: '5'});
                    });
                    return false;
                }
            }else  if(type==2){
                //这里是新用户
                if (!new_time_flag) {
                    layer.ready(function () {
                        layer.msg('新手七日好礼活动已结束!', {icon: '5'});
                    });
                    return false;
                }
            }


            return true;
        }


    };

    \$(function () {
        //上来就是检测一下时间
        //Gift.checkTime();
    })

</script>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "Gift/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  258 => 149,  254 => 148,  235 => 132,  225 => 127,  212 => 119,  205 => 114,  201 => 112,  198 => 111,  194 => 109,  190 => 107,  188 => 106,  185 => 105,  182 => 104,  180 => 103,  173 => 101,  166 => 97,  154 => 90,  144 => 83,  135 => 76,  123 => 69,  118 => 66,  115 => 65,  111 => 63,  107 => 61,  105 => 60,  100 => 59,  97 => 58,  95 => 57,  88 => 53,  84 => 52,  77 => 51,  73 => 50,  26 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "Gift/index.html", "/home/wwwroot/default/qiyue_projects/app/module/activity/view/Gift/index.html");
    }
}
