<!DOCTYPE html>
<html lang="zh_cn">
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="游戏充值">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>休休游戏管理系统</title>
    <link rel="stylesheet" href="{{ asset('/css/wxLoginCss.css')}}?v=0404">
</head>
<body>
    <div class="login_bg">
        <div style="height: 55%;"></div>
        <div style="text-align: center;">
            <img src="img/login/wx_login.png" width="60%" href="#" onclick="window.location.href = '/wxlogin'">
        </div>
        <div style="height: 5%;"></div>
        <div style="text-align: center">
            <img src="img/login/phone_login.png" width="60%" href="#" onclick="$('#phone_login').show();">
        </div>
    </div>
    <div id="phone_login" class="phone_bg" style="display: ;">
        <div style="height: 7%;"></div>
        <div style="text-align: right;">
            <img src="img/login/close.png" style="margin-right: 10%;" width="20" href="#" onclick="$('#phone_login').hide();">
        </div>
        <div style="text-align:center;margin-top: 18%;">
            <span class="span_font">手机号</span>&nbsp;&nbsp;&nbsp;&nbsp;<input id="phone" type="number" value="13545678912" style="width: 48%" class="input_txt">
            <br><hr width="70%">
        </div>
        <div style="text-align:center;margin-top: 8%;">
            <span class="span_font">验证码</span>&nbsp;&nbsp;&nbsp;&nbsp;<input id="phone" type="number" value="9527" style="width: 25%" class="input_txt" >
            &nbsp;&nbsp;<img src="img/login/get.png" width="18%" style="vertical-align:middle;">
            <br><hr width="70%">
        </div>
        <div style="text-align:center;margin-top: 5%;">
            <img src="img/login/login.png"  width="45%" href="#" onclick="">
        </div>
    </div>
</body>
<script src="{{ asset('/js/jquery-1.12.4.min.js') }}"></script>
<script type="text/javascript">
    // (function (doc, win) {
    //     var docEl = doc.documentElement,
    //         resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
    //         recalc = function () {
    //             var clientWidth = docEl.clientWidth;
    //             if (!clientWidth) return;
    //             docEl.style.fontSize = 20 * (clientWidth / 640) + 'px';
    //         };
    //
    //     if (!doc.addEventListener) return;
    //     win.addEventListener(resizeEvt, recalc, false);
    //     doc.addEventListener('DOMContentLoaded', recalc, false);
    // })(document, window);
    $(function () {

    })
</script>
</html>