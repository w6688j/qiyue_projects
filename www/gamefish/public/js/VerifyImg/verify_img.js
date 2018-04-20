/**
 *
 * 这是第一种写法--ie789貌似不支持,我在写一个兼容版本--verify_img_pre.js
 * Created by Administrator on 2017/6/26.
 * 调用方法 --里面的参数可以不传
 *  VerifyImg.init({
 *       imgWidth:198,//验证码图片的宽度
        imgHeight:90,//验证码图片的高度
        circleSize:30,//和后台圆形选框一致
        selNums:3,//可以选择三个字符
         verifyUrl: 'http://gamefish.pp158.loc/index.php?m=getverifyimg&type=1',//加载验证码路径
        selCodesUrl: 'http://gamefish.pp158.loc/index.php?m=getverifyimg&type=2',//需要选择验证码的路径
        getCheckImgUrl: 'http://gamefish.pp158.loc/index.php?m=getverifyimg&type=3',//验证验证码
 *  });
 *
 */

var VerifyImg = {
    //默认参数
    eles: {
        imgWidth: 198,//验证码图片的宽度
        imgHeight: 90,//验证码图片的高度
        circleSize: 30,//和后台圆形选框一致
        selNums: 3,//可以选择三个字符,
        eleBox: ".Verify",//包装盒子的类
        verifyUrl: '',//加载验证码路径
        selCodesUrl: '',//需要选择验证码的路径
        getCheckImgUrl: '',//验证验证码

        success:function(){},//这是一个成功验证回调函数
        error:function(){},//这是失败验证的回调函数
    },
    seledNums: 0,//已选择多少字符
    selData: [],


    selectChar: function (e) {
        if (this.seledNums >= this.eles.selNums) {
            return false;
        }
        var e =e || window.event || arguments.callee.caller.arguments[0];
        var offset = $("#verify_img").offset();
      //  var relativeX = (e.pageX - offset.left);
       // var relativeY = (e.pageY - offset.top);


        //必须要兼容IE8...好尴尬
        var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
        var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
        var relativeX =( e.pageX || e.clientX + scrollX) -offset.left ;
        var relativeY = (e.pageY || e.clientY + scrollY) -  offset.top;


        var left = relativeX - this.eles.circleSize / 2;
        var top = relativeY - this.eles.circleSize / 2;

        //赋值选中的坐标
        this.selData.push(left);
        this.selData.push(top);


       // alert("x=" + left + ",y=" + top);

        var circle_html = '<span  class="verify_sel" style="width:' + this.eles.circleSize + 'px;height:' + this.eles.circleSize + 'px;left:' + left + 'px;top:' + top + 'px; "></span>';
        $("#verify_img").parent().append(circle_html);

        this.seledNums++;


//             if(this.seledNums>=this.eles.selNums){
//                 alert("执行查询");
//                 return false;
//             }
    },
    init: function (eles) {

        this.eles = $.extend({}, this.eles, eles);//jquery 閲岄潰鐨勫悎骞跺璞℃柟娉�

        var html = '<div class="verify_box"><div class="verify_title"><div class="verify_text" onclick="VerifyImg.openOrClose(this)">点击完成验证</span></div></div><div class="verify_con" style="display: none;"><span class="verify_refresh" onclick="VerifyImg.refresh()"></span><span class="verify_check" onclick="VerifyImg.verify_check()">验证</span><span class="verify_notice">正在加载</span><div class="verify_img_box" style="z-index:1;position:relative;height:' + this.eles.imgHeight + 'px;"><img id="verify_img" style="width:' + this.eles.imgWidth + 'px;" onclick="VerifyImg.selectChar()"src=""/><span class="check_span_info"></span></div></div></div>';

        $(this.eles.eleBox).html(html);
    },
    refresh: function () {
        //刷新

        //清除选择的验证码
        this.seledNums = 0;
        this.selData = [];
        $(".verify_sel").remove();//清除已选择的圆框

        var random = Math.random();
//            var str_stop = this.eles.verifyUrl.indexOf("?")>-1 ?this.eles.verifyUrl.indexOf("?"):this.eles.verifyUrl.length;
//            var verifyUrl = this.eles.verifyUrl.substring(0,str_stop);
        var verifyUrl = this.eles.verifyUrl + "?id=" + random;
        $("#verify_img").attr("src", verifyUrl);
        this.getSelCodes();

    },
    openOrClose: function (obj) {
        //console.log($(obj).html().indexOf("已通过验证"));
        if ($(obj).html().indexOf("已通过验证") != -1) {
            return false;
        }

        //打开或者隐藏验证码
        $(".verify_con").fadeToggle(200);
        if ($(obj).html() == '安全验证') {
            $(obj).html("点击完成验证");
        } else {
            $(obj).html("安全验证");
            //加载验证码
            this.refresh();
        }
    },
    getSelCodes: function () {
        //获取需要点击的验证码
        setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: VerifyImg.eles.selCodesUrl,
                data: {},
                success: function (res) {
                    if (res.status == true) {
                        var codes = '';
                        for (var i = 0; i < res.data.length; i++) {
                            codes += res.data[i] + ' ';
                        }

                        $("span.verify_notice").html('请依次点击<span class="verify_zimu">' + codes + '</span>完成验证');
                    } else {
                        $("span.verify_notice").html(res.msg);
                    }
                },
                dataType: "json",
            });
        }, 200);


    },
    verify_check: function () {
        //验证选择的验证码
        if (this.seledNums <= 0) {
            this.showMsg("您还没有选择图片上的字符");
        } else if (this.seledNums < this.eles.selNums) {
            this.showMsg("请把字符选完");
        } else {
            //请求服务器验证字符
            $.ajax({
                type: 'POST',
                url: VerifyImg.eles.getCheckImgUrl,
                data: {"data": VerifyImg.selData.join(",")},
                success: function (res) {
                    if (res.status == true) {
                        VerifyImg.showSuccessMsg();
                        //执行回调函数
                        if(typeof  VerifyImg.eles.success==='function'){
                            VerifyImg.eles.success(res);
                        }
                    } else {
                        VerifyImg.showMsg(res.msg);
                        VerifyImg.refresh();
                        if(typeof  VerifyImg.eles.error==='function'){
                            VerifyImg.eles.error(res);
                        }
                    }
                },
                dataType: "json",
            });

        }


    },
    showMsg: function (msg) {
        $("span.check_span_info").html(msg).fadeIn(100);
        setTimeout(function () {
            $("span.check_span_info").fadeOut();
        }, 1000);
    },
    showSuccessMsg:function(){
        $("div.verify_text").html('已通过验证<span class="verify_status verify_success"></span>');
        $(".verify_con").hide();
    },
    showErrorMsg:function(msg){
        if(!msg){
            msg = "验证码错误,请点击验证";
        }

        $("div.verify_text").html('<span style="color:red;font-weight: bold;">'+msg+'</span>');
        $(".verify_con").hide();
    }

}