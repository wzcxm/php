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
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}?v=20180712">
</head>
<body>
<div class="download_bg" >
    <div style="height: 80%;"></div>
    <div style="height: 20%;" align="center">
        <a  href="https://xiuxiu-game.oss-cn-shenzhen.aliyuncs.com/Demo/xxqp/xxqp.apk">
            <img class="img-rounded " width="180" src="/img/download/download.png" />
        </a>
        <a  href="itms-services://?action=download-manifest&url=https://xiuxiu-game.oss-cn-shenzhen.aliyuncs.com/Demo/xxqp/xxqp.plist">
            <img class="img-rounded " width="180" src="/img/download/download.png" />
        </a>
    </div>
</div>
{{--<script src="{{asset('js/weui/js/jquery-2.1.4.js')}}"></script>--}}
{{--<script type="text/javascript">--}}
{{--$(function(){--}}
{{--var ua = navigator.userAgent.toLowerCase();--}}
{{--if(ua.match(/MicroMessenger/i) == "micromessenger") {--}}
{{--$('#bg_down').removeClass().addClass("download_tz_bg");--}}
{{--$("#down_load").hide();--}}
{{--//$("#add").hide();--}}
{{--}else {--}}
{{--if (ua.indexOf('android') > -1 || ua.indexOf('linux') > -1) {//安卓手机--}}
{{--$("#down_load").attr('href','https://xiuxiu-game.oss-cn-shenzhen.aliyuncs.com/Demo/xxqp/xxqp.apk');--}}
{{--// $("#add").hide();--}}
{{--} else if (ua.indexOf('iphone') > -1) {//苹果手机--}}
{{--$("#down_load").attr('href','itms-services://?action=download-manifest&url=https://xiuxiu-game.oss-cn-shenzhen.aliyuncs.com/Demo/xxqp/xxqp.plist');--}}
{{--}else{--}}

{{--}--}}
{{--}--}}
{{--});--}}
{{--</script>--}}
</body>
</html>
