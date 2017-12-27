<!DOCTYPE html>
<html lang="zh_cn">
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="微友游戏代理申请">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>微友游戏代理申请</title>
    <link rel="stylesheet" href="{{asset('js/weui/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/weui/css/jquery-weui.min.css')}}">
    <style>
        body, html {
            height: 100%;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body ontouchstart>
<div class="weui-tab">
    <div class="weui-navbar" >
        <a class="weui-navbar__item weui-bar__item--on" >
            微友游戏代理申请
        </a>
    </div>
    <div class="weui-tab__bd">
        <div id="tab_one" class="weui-tab__bd-item weui-tab__bd-item--active">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">游戏ID：</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number"  id="uid" placeholder="请输入您的游戏ID">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">联系方式：</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="text"  id="tel" placeholder="请输入您的联系电话">
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">提交申请</a>
            </div>
        </div>
    </div>

</div>
<script src="{{asset('js/weui/js/jquery-2.1.4.js')}}"></script>
<script src="{{asset('js/weui/js/jquery-weui.min.js')}}"></script>
<script src="{{asset('/js/weui/js/fastclick.js')}}"></script>
<script>
    $(function () {
        FastClick.attach(document.body);
        //$(".weui-tab__bd").css('height',$(".weui-tab__bd").height()-$(".weui-tabbar").height());
    })
</script>
<script src="{{asset('/js/common.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#btn_save").click(function () {
            $.post('/Apply/save',{uid:$("#uid").val(),tel:$("#tel").val()},function(data){
                if(data.error==""){
                    $("#uid").val("");
                    $("#tel").val("");
                    $.alert("您的申请已经提交成功，请耐心等待审核！");
                } else {
                    $.alert(data.error);
                }
            })
        })
    });
</script>
</body>
</html>
