<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="推广码">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>休休科技--推广码</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}?v=20180319">
</head>
<body>
<img width="100%" height="100%" id="img" style="display: none;">
<div class="qrcode_bg" >
    <div style="height:55%">
        <div style="float: left;margin: 8px 0 0 5px;width: 18%;">
            <img id="head" src="data:image/png;base64,{{empty($head)?'/img/ui-default.jpg':$head}}"
                 style="border-radius:10px;border: 3px solid white;"
                 width="100%">
        </div>
        <div style="float: left;margin: 18px 0 0 5px; font-size: 1em;color: white;font-weight: 400;">
            <div>{{empty($user)?"":$user->nickname}}</div>
            <div>ID:{{empty($user)?"":$user->uid}}</div>
        </div>
    </div>
    <div style="text-align: center;">
        <div style="width: 55%;margin-left: 22%">
            <img style="border-radius:10px;"
                 width="100%"
                 src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(400)->margin(1)->encoding('UTF-8')->generate($url)) !!} ">
        </div>
    </div>
</div>
<script src="{{ asset('/js/html2canvas.min.js') }}"></script>
<script type="text/javascript">

        //要显示图片的img标签
        var image = document.querySelector('#img');
        //要保存的元素
        var element = document.querySelector('.qrcode_bg');
        //创建一个新的canvas
        var width = element.offsetWidth ; //获取dom 宽度
        var height = element.offsetHeight; //获取dom 高度
        var canvas = document.createElement("canvas"); //创建一个canvas节点
        var scale = 2; //定义任意放大倍数 支持小数
        canvas.width = width * scale; //定义canvas 宽度 * 缩放
        canvas.height = height * scale; //定义canvas高度 *缩放
        canvas.getContext("2d").scale(scale,scale); //获取context,设置scale
        var opts = {
            scale:scale, // 添加的scale 参数
            canvas:canvas, //自定义 canvas
            logging: true, //日志开关
            width:width, //dom 原始宽度
            height:height //dom 原始高度
        };

        //生成图片
        html2canvas(element,opts).then(function(canvas) {
            image.src = canvas.toDataURL();
        });

        //删除div
        window.onload=function (ev) {
            document.body.removeChild(element);
            image.style.display='block';
        };


</script>
</body>
</html>
