<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="游戏下载">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>休休科技--游戏下载</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}?v=20180207">
    <style type="text/css">
        *{
            margin:0;
            padding:0;
        }
        a{
            text-decoration: none;
        }
        img{
            max-width: 100%;
            height: auto;
        }
        .weixin-tip{
            display: none;
            position: fixed;
            left:0;
            top:0;
            bottom:0;
            background: rgba(0,0,0,0.8);
            filter:alpha(opacity=80);
            height: 100%;
            width: 100%;
            z-index: 100;
        }
        .weixin-tip p{
            text-align: center;
            margin-top: 10%;
            padding:0 5%;
        }
    </style>
</head>
<body>
<div class="download_bg">
    <div style="height: 40%;"></div>
    <div style="height: 40%;">
    </div>
    <div style="height: 20%;" align="center">
        <a href="http://fir.im/ysrn">
            <img class="img-rounded " width="180" src="/img/download/download.png" />
        </a>
        <a href="itms-services://?action=download-manifest&url=https://xiuxiu-game.oss-cn-shenzhen.aliyuncs.com/Demo/xxqp/xxqp.plist">点击下载</a>
    </div>
    <div class="weixin-tip">
        <p>
            <img src="live_weixin.png" alt="微信打开"/>
        </p>
    </div>
</div>
<script src="{{asset('js/jquery-1.12.4.min.js')}}"></script>
<script type="text/javascript">
    $(window).on("load",function(){
        var winHeight = $(window).height();
        function is_weixin() {
            var ua = navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == "micromessenger") {
                return true;
            } else {
                return false;
            }
        }
        var isWeixin = is_weixin();
        if(isWeixin){
            $(".weixin-tip").css("height",winHeight);
            $(".weixin-tip").show();
        }
    })
</script>
</body>
</html>
