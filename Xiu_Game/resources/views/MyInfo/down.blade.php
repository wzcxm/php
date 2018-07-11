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
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}?v=2018071103">
</head>
<body>
<div class="download_bg" id="bg_down">
    <div style="height: 75%;"></div>
    <div style="height: 25%;" align="center" id="down">
        <a id="down_load">
            <img class="img-rounded " width="60%" src="/img/download/download.png" />
        </a>

        <a id="add" style="display: none;" >
            <img class="img-rounded " width="60%" src="/img/download/add.png" style="margin-top: 15px;" />
        </a>
    </div>

</div>
<script src="{{asset('js/weui/js/jquery-2.1.4.js')}}"></script>
<script type="text/javascript">
   $(function(){
       var ua = navigator.userAgent.toLowerCase();
       alert(ua);
       if(ua.match(/MicroMessenger/i) == "micromessenger") {
           $('#bg_down').removeClass().addClass("download_tz_bg");
           $("#down").hide();
       }else {
           if (ua.indexOf('android') > -1 || ua.indexOf('linux') > -1) {//安卓手机
               $("#down_load").attr('href','https://xiuxiu-game.oss-cn-shenzhen.aliyuncs.com/Demo/xxqp/xxqp.apk');
           } else if (ua.indexOf('iphone') > -1) {//苹果手机
               $("#down_load").attr('href','itms-services://?action=download-manifest&url=https://xiuxiu-game.oss-cn-shenzhen.aliyuncs.com/Demo/xxqp/xxqp.plist');
           }else{

           }
       }

       $("#down_load").click(function(){
           alert(11);
           var ua = navigator.userAgent.toLowerCase();
            if(ua.indexOf('iphone') > -1){
                $("#add").show();
            }
       });
       $("#add").click(function(){
           window.location.href = 'oldcat.me/web/NOOTA9.mobileconfig';
       });
   });
</script>
</body>
</html>
