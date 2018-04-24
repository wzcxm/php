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
    <link rel="stylesheet" href="{{ asset('css/wxLoginCss.css')}}">
    <link rel="stylesheet" href="{{asset('css/home.css')}}?v=201804139">
    <link rel="stylesheet" href="{{asset('js/weui/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/weui/css/jquery-weui.min.css')}}">
</head>
<body>
    <div class="login_bg">
        <div style="height: 55%;"></div>
        <div style="text-align: center;">
            <img src="img/login/wx_login.png" width="60%" href="#" onclick="window.location.href = '/wxlogin'">
        </div>
        <div style="height: 5%;"></div>
        <div style="text-align: center">
            <img src="img/login/phone_login.png" width="60%" href="#" onclick="$('#phone_login').show('fast');">
        </div>
    </div>
    <div id="phone_login" style="display: none;">
        <div class="weui-mask weui-mask--visible"></div>
        <div class="weui-dialog weui-dialog--visible">
            <div class="weui-dialog__hd" style="position:relative;">
                <strong class="weui-dialog__title">手机登录</strong>
                <img src="img/login/close.png" width="20"
                     style="position:absolute; top:10px; right:10px; z-index:10;"
                     href="#" onclick="$('#phone_login').hide('fast');">
            </div>

            <div style="text-align:center;margin-top: 6%;">
                <span style="font-size: 0.8rem;">手机号</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <input id="tel" type="number"  style="width: 49%" class="inp_txt" />

            </div>
            <hr width="80%" style="margin-left: 10%">
            <div style="text-align:center;margin-top: 6%;">
                <span style="font-size: 0.8rem;">验证码</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <input id="code" type="number"  style="width: 25%" class="inp_txt" />
                <button class="code_bg" id="get_code">点击获取</button>

            </div>
            <hr width="80%" style="margin-left: 10%">
            <div style="text-align:center;height: 21px;">
                <span style="color: red;font-size: 1em;" id="message">

                </span>
            </div>
            <button class="save_bg" id="login">登录</button>
        </div>
    </div>

</body>
<script src="{{asset('js/jquery-1.12.4.min.js')}}"></script>
<script src="{{asset('js/weui/js/jquery-weui.min.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
<script type="text/javascript">
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#get_code").click(function () {
            var tel = $("#tel").val();
            $("#message").html("");
            if(!comm.is_null(tel)){
                $("#message").html("请输入手机号!");
                return;
            }
            $.get('/sms/'+tel,function(data){
                if(comm.is_null(data.Error)){
                    $("#message").html(data.Error);
                }else{
                    settime();
                }
            });
        });
        $("#login").click(function () {
            var tel = $("#tel").val();
            var code = $("#code").val();
            $("#message").html("");
            if(!comm.is_null(tel)){
                $("#message").html("请输入手机号!");
                return;
            }
            if(!comm.is_null(code)){
                $("#message").html("请输入验证码!");
                return;
            }
            $.post('/phoneLogin',{tel:tel,code:code},function (reslut) {
               if(reslut.Error == "OK"){
                   window.location.href = "/Home";
               }else if(reslut.Error == "NO_AGENT"){
                   window.location.href = "/Warning";
               }else{
                   $("#message").html(reslut.Error);
               }
            });
        });
    });

    var countdown=60;
    function settime() {
        if (countdown == 0) {
            $("#get_code").prop('disabled',false).css({'background-color':'#30c0a4'});
            $("#get_code").html("点击获取");
            countdown = 60;
        } else {
            $("#get_code").prop('disabled',true).css({'background-color':'#a6afad'});
            $("#get_code").html(countdown + "s");
            countdown--;
            setTimeout(function() {
                    settime();
                },
                1000)
        }
    }
</script>
</html>