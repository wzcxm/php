<!DOCTYPE html>
<html lang="zh_cn">
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="休休游戏管理系统">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>休休游戏管理系统</title>
    <link rel="stylesheet" href="{{asset('js/weui/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/weui/css/jquery-weui.min.css')}}">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    @yield('style')
    <style>
        body, html {
            height: 100%;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body ontouchstart>
<div class="weui-tab">
    {{--<div class="weui-navbar" >--}}
        {{--<a class="weui-navbar__item weui-bar__item--on" >--}}
            {{--微友游戏管理系统--}}
        {{--</a>--}}
    {{--</div>--}}
    <div class="weui-tab__bd">
        <div id="tab_one" class="weui-tab__bd-item weui-tab__bd-item--active">
            @yield('content')
        </div>
    </div>
    <div class="weui-tabbar">
        <a href="/Home" class="weui-tabbar__item weui-bar__item--on">
            <div class="weui-tabbar__icon">
                <i class="fa fa-home"></i>
            </div>
            <p class="weui-tabbar__label">首页</p>
        </a>
        <a href="/MyInfo" class="weui-tabbar__item">
            <div class="weui-tabbar__icon">
                <i class="fa fa-user-o"></i>
            </div>
            <p class="weui-tabbar__label">我</p>
        </a>
        <a href="javascript:$.alert('更多内容，敬请期待！');"  class="weui-tabbar__item">
            <div class="weui-tabbar__icon">
                <i class="fa fa-bars"></i>
            </div>
            <p class="weui-tabbar__label">更多</p>
        </a>
    </div>
</div>
<script src="{{asset('js/weui/js/jquery-2.1.4.js')}}"></script>
<script src="{{asset('js/weui/js/jquery-weui.min.js')}}"></script>
<script src="{{asset('/js/weui/js/fastclick.js')}}"></script>
<script>
    $(function () {
        FastClick.attach(document.body);
        $(".weui-tab__bd").css('height',$(".weui-tab__bd").height()-$(".weui-tabbar").height());
    })
</script>
<script src="{{asset('/js/common.js')}}"></script>
@yield('script')
</body>
</html>