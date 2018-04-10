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
    {{--<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}?v=20180411">
</head>
<body>
<div id="images" style="width: 100%;height: 100%;"></div>
<div  class="qrcode_bg" >
    <div style="height:55%">
        <div style="float: left;margin: 8px 0 0 5px;width: 18%;">
            <img id="head" src="data:image/png;base64,{{empty($head)?'/img/ui-default.jpg':$head}}"
                 style="border-radius:10px;border: 3px solid white;"
                 width="100%">
        </div>
        <div style="float: left;margin: 18px 0 0 5px; font-size: 2em;color: white;font-weight: 400;">
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
<script src="{{asset('js/jquery-1.12.4.min.js')}}"></script>
<script src="{{ asset('js/html2canvas.min.js') }}"></script>
<script src="{{ asset('js/canvas2image.js') }}"></script>
<script type="text/javascript">
    $(function () {
        convert2canvas();
    });
    window.onload=function (ev) {
        document.body.removeChild(document.querySelector('.qrcode_bg'));
    };
    function convert2canvas() {
        var shareContent = document.querySelector('.qrcode_bg');//需要截图的包裹的（原生的）DOM 对象
        var width = shareContent.offsetWidth; //获取dom 宽度
        var height = shareContent.offsetHeight; //获取dom 高度
        var canvas = document.createElement("canvas"); //创建一个canvas节点
        var scale = 2; //定义任意放大倍数 支持小数
        canvas.width = width * scale; //定义canvas 宽度 * 缩放
        canvas.height = height * scale; //定义canvas高度 *缩放
        canvas.getContext("2d").scale(scale, scale); //获取context,设置scale
        var opts = {
            scale: scale, // 添加的scale 参数
            canvas: canvas, //自定义 canvas
            width: width, //dom 原始宽度
            height: height
        };

        html2canvas(shareContent, opts).then(function (canvas) {

            var context = canvas.getContext('2d');
            // 【重要】关闭抗锯齿
            context.mozImageSmoothingEnabled = false;
            context.webkitImageSmoothingEnabled = false;
            context.msImageSmoothingEnabled = false;
            context.imageSmoothingEnabled = false;

            // 【重要】默认转化的格式为png,也可设置为其他格式
            var img = Canvas2Image.convertToJPEG(canvas, canvas.width, canvas.height);

            document.getElementById('images').innerHTML="";
            document.getElementById('images').appendChild(img);

            $(img).css({
                "width": "100%",
                "height": "100%"
            });

        });

    }

</script>
</body>
</html>
