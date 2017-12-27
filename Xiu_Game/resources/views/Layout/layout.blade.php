<!DOCTYPE html>
<html lang="zh_cn">
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="长沙泡泡游戏管理系统">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>长沙泡泡游戏管理系统</title>
    <link rel="stylesheet" href="{{ asset('/css/theme-classic.css')}}">
    <link rel="stylesheet" href="{{ asset('/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/css/autocompleter.css')}}">
    <script src="{{asset('/js/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js')}}"></script>
    <script src="{{asset('/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/js/messages_zh.min.js')}}"></script>
    <script src="{{asset('/js/common.js?v=20170925')}}"></script>
    <script src="{{asset('/js/jquery.autocompleter.min.js')}}"></script>

</head>
<body>
<div data-role="page"  data-theme="d" >
    <div data-role="header" data-position="fixed" data-tap-toggle="false" >
        <a href="#" class="ui-btn ui-shadow ui-corner-all ui-icon-back ui-btn-icon-notext" onclick="javascript:history.go(-1);">返回</a>
        <h1>泡 &nbsp;泡&nbsp;游&nbsp;戏&nbsp;管&nbsp;理&nbsp;系&nbsp;统</h1>
    </div>

    <div data-role="main" class="ui-content"  >
            @yield('content')
    </div>

    <div data-role="footer" data-position="fixed" data-tap-toggle="false" >
        <div data-role="navbar">
            <ul>
                <li><a href="/Home" data-icon="home" >首页</a></li>

                <li><a href="/MyInfo" data-icon="user" >我的信息</a></li>
                <li><a href="#" data-icon="action" >更多</a></li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>