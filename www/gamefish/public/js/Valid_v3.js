$(function () {
    $("form").submit(function (e) {
        e.preventDefault();

    });
    //注册方法
    function registerSub() {
        var url = "/Register/register";
        var data = {
            account: $("[name=account]").val(),
            password: $("[name=password]").val(),
            repassword: $("[name=repassword]").val(),
            nickname: $("[name=nickname]").val(),
            realname: $("[name=realname]").val(),
            idcard: $("[name=idcard]").val(),
            user_vid: $("[name=user_vid]").val(),
            license: $("[name=license]:checked").val(),
            token: $("[name=token]").val(),
            gender: $("[name=sex]").val(),
            netbarid: $("[name=netbarid]").val(),
            verifyimg: $("#verify_input").val(),//点选验证码
        }

        $(".clickable").html("正在注册");

        $.post(url, data, function (res) {
            if (res.status) {
                window.location.href = "/Login/index?account="+data.account+"&password="+data.password;
            } else {
                alert(res.msg);
                $(".clickable").html("免费注册");
            }
        }, "JSON");
    }

    $("#register_form").Validform({
        tiptype: 2,

        // btnSubmit:"#registerform",
        datatype: {//传入自定义datatype类型【方式二】;
            "z2-4": /^[\u4E00-\u9FA5\uf900-\ufa2d]{2,4}$/,
            "nicheng6-18": function (gets, obj, curform, regxp) {
                var reg = /^[a-zA-Z0-9_@]{6,18}$/;
                if (!reg.test(gets)) {
                    return false;
                }
            },
            "range4-16": function (gets, obj, curform, regxp) {
                var atleast = 4,
                    atmax = 20;
                var getAnsiLength = function (b, ansi) {
                    if (!(typeof b == 'string') || !ansi) {
                        return b.length;
                    }
                    var a = b.match(/[^\x00-\x80]/g);
                    return b.length + (a ? a.length : 0);
                };

                var len = getAnsiLength(gets, true);
                if (len < atleast) {
                    return "昵称不能少于" + atleast + "个字符";
                } else if (len > atmax) {
                    return "昵称不能多于" + atmax + "个字符";
                }
                return true;
            },
            "idcard": function (gets, obj, curform, datatype) {

                var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1];// 加权因子;
                var ValideCode = [1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2];// 身份证验证位值，10代表X;

                if (gets.length == 15) {
                    return isValidityBrithBy15IdCard(gets);
                } else if (gets.length == 18) {
                    var a_idCard = gets.split("");// 得到身份证数组
                    if (isValidityBrithBy18IdCard(gets) && isTrueValidateCodeBy18IdCard(a_idCard)) {
                        return true;
                    }
                    return false;
                }
                return false;

                function isTrueValidateCodeBy18IdCard(a_idCard) {
                    var sum = 0; // 声明加权求和变量
                    if (a_idCard[17].toLowerCase() == 'x') {
                        a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
                    }
                    for (var i = 0; i < 17; i++) {
                        sum += Wi[i] * a_idCard[i];// 加权求和
                    }
                    valCodePosition = sum % 11;// 得到验证码所位置
                    if (a_idCard[17] == ValideCode[valCodePosition]) {
                        return true;
                    }
                    return false;
                }

                function isValidityBrithBy18IdCard(idCard18) {
                    var year = idCard18.substring(6, 10);
                    var month = idCard18.substring(10, 12);
                    var day = idCard18.substring(12, 14);
                    var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
                    // 这里用getFullYear()获取年份，避免千年虫问题
                    if (temp_date.getFullYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
                        return false;
                    }
                    return true;
                }

                function isValidityBrithBy15IdCard(idCard15) {
                    var year = idCard15.substring(6, 8);
                    var month = idCard15.substring(8, 10);
                    var day = idCard15.substring(10, 12);
                    var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
                    // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法
                    if (temp_date.getYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
                        return false;
                    }
                    return true;
                }

            }
        },
        callback: function (form) {
            if (argee()) {
                // layer.msg('请稍后...',{
                //     time: 0,
                //     icon: 16,
                //     shade: [0.8, '#393D49'],
                //     scrollbar: false
                // });
                //验证点选验证码方法
                if (!check_verify()) {
                    return false;
                }


                registerSub();
            } else {
                var agreeTop = $("input[type='checkbox']").offset().top;
                $('body,html').animate({scrollTop: agreeTop});
                alert("请选择同意《服务条款》");
                //   layer.alert("请选择同意《服务条款》！",{icon:0,shade: [0.8, '#393D49']})
                return false;
            }
        }
    });
});
