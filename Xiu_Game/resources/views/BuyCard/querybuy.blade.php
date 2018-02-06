<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="充值记录">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <title>充值记录</title>
    <link rel="stylesheet" href="{{asset('js/weui/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/weui/css/jquery-weui.min.css')}}">
    <style>
        body, html {
            height: 100%;
            -webkit-tap-highlight-color: transparent;
        }
        .bordered {
            *border-collapse: collapse; /* IE7 and lower */
            border-spacing: 0;
            width: 100%;
            border: solid #ccc 1px;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            -webkit-box-shadow: 0 1px 1px #ccc;
            -moz-box-shadow: 0 1px 1px #ccc;
            box-shadow: 0 1px 1px #ccc;
            font-size: 13px;
        }

        .bordered tr {
            -o-transition: all 0.1s ease-in-out;
            -webkit-transition: all 0.1s ease-in-out;
            -moz-transition: all 0.1s ease-in-out;
            -ms-transition: all 0.1s ease-in-out;
            transition: all 0.1s ease-in-out;
        }
        .bordered .highlight,
        .bordered tr:hover {
            background: #fbf8e9;
        }
        .bordered td,
        .bordered th {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        .bordered th {
            background-color: #dce9f9;
            background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
            background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: -moz-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: -ms-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: -o-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: linear-gradient(top, #ebf3fc, #dce9f9);
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr=#ebf3fc, endColorstr=#dce9f9);
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#ebf3fc, endColorstr=#dce9f9)";
            -webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;
            -moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;
            box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;
            border-top: none;
            text-shadow: 0 1px 0 rgba(255,255,255,.5);
        }
        .bordered td:first-child,
        .bordered th:first-child {
            border-left: none;
        }
        .bordered th:first-child {
            -moz-border-radius: 6px 0 0 0;
            -webkit-border-radius: 6px 0 0 0;
            border-radius: 6px 0 0 0;
        }
        .bordered th:last-child {
            -moz-border-radius: 0 6px 0 0;
            -webkit-border-radius: 0 6px 0 0;
            border-radius: 0 6px 0 0;
        }
        .bordered tr:last-child td:first-child {
            -moz-border-radius: 0 0 0 6px;
            -webkit-border-radius: 0 0 0 6px;
            border-radius: 0 0 0 6px;
        }
        .bordered tr:last-child td:last-child {
            -moz-border-radius: 0 0 6px 0;
            -webkit-border-radius: 0 0 6px 0;
            border-radius: 0 0 6px 0;
        }
        /*----------------------*/

    </style>
</head>
<body>
<header style="text-align: center;padding-top: 10px;">
    <h3>充值记录</h3>
</header>
<div class="weui-cells weui-cells_form" style="overflow: auto;">
    <table id="table_list" class="bordered">
        <thead>
        <tr>
            <th width="40%">充值日期</th>
            <th width="30%">充值金额</th>
            <th width="30%">钻石数量</th>
        </tr>
        </thead>
        <tbody>
        @foreach($List as $item)
            <tr>
                <td>{{$item->create_time}}</td>
                <td>{{$item->total}}</td>
                <td>{{$item->cardnum}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="weui-btn-area">
    <a href="/PlayerBuy/index" class="weui-btn weui-btn_default weui-btn_loading">返回</a>
</div>
</body>
</html>