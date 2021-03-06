<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="推广码">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>休休科技--推广码</title>
    {{--<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}?v=201805222">
</head>
<body>
<div id="images" ></div>
<div  class="qrcode_bg" >
    <div style=" padding-top: 8px;">
        <div style="float: left;width: 20%;padding-left: 5px;">
            <img id="head" src="data:image/png;base64,{{empty($head)?'/img/ui-default.jpg':$head}}"
                 style="border-radius:10px;border: 3px solid white;"
                 width="100%">
        </div>
        <div class="font_bg" style="width: 40%;margin-top: 5px;">
            <div style="height:20%;font-size: 1.2rem;color: white;font-weight: 600;padding: 5px 8px 5px 15px;">
                <div style="padding-top: 5px;">{{empty($user)?"":$user->nickname}}</div>
                <div style="padding-top: 5px;">ID:{{empty($user)?"":$user->uid}}</div>
            </div>
        </div>
    </div>
    <div style="position: fixed;bottom:5%;">
        <div style="text-align: center;">
            <img style="border-radius:10px;"
                 width="50%"
                 src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(400)->margin(1)->encoding('UTF-8')->generate($url)) !!} ">
        </div>
        <div style="text-align: center;padding-top: 20px;">
            <img src="/img/qrcode/download.png" width="80%">
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
        var width = shareContent.offsetWidth; //获取dom 宽度window.screen.width;
        var height = shareContent.offsetHeight; //获取dom 高度window.screen.height;
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
                "width": window.screen.width+"px",
                "height": window.screen.height+"px"
            });

        });

    }

</script>
</body>
</html>
