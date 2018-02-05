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
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}">
</head>
<body>
<div class="qrcode_bg">
    <div style="height: 20%">
        <div style="float: left;margin: 5px 0 0 5px;">
            <img src="{{empty($user)?"/img/ui-default.jpg":$user->head_img_url}}"
                 style="border-radius:10px;
                 border: 3px solid white;"
                 width="70">
        </div>
        <div style="float: left;margin: 15px 0 0 5px; font-size: 1.8rem;color: white;font-weight: 400;">
            <div>{{empty($user)?"":$user->nickname}}</div>
            <div>ID:{{empty($user)?"":$user->uid}}</div>
        </div>
    </div>
    <div style="height: 35%"></div>
    <div style="height: 45%;text-align: center;">
        <img style="border-radius:10px;"  src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(210)->encoding('UTF-8')->generate($url)) !!} ">
    </div>
</div>
</body>
</html>
