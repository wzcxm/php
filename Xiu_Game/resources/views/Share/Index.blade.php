<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="游戏分享">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>休休游戏--分享</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}">
</head>
<body>
<div class="share_bg">
    <div style="height: 40%;"></div>
    <div style="height: 40%;">
        <div style="font-size: 2.5rem;color: green;text-align: center;font-weight: 800;">
            <span>局数：{{empty($item['number'])?"":$item['number'].'局'}}</span>
            <span>玩法：{{empty($item['play'])?"":$item['play']}}</span>
        </div>
        <div style="height: 100%;">
            <div class="desk_bg">
                <div style="height: 35%">
                    <div style="width: 35%;float: left;">
                        <div style="margin-top: 3px;font-weight: bold;text-align: right;">
                            @if(!empty($item))
                                {{empty($item['teaid'])?"":$item['teaid']}}
                            @endif
                        </div>
                        <div style="font-weight: bold;">&nbsp;&nbsp;
                            @if(!empty($item))
                                {{empty($item['hallid'])?"":$item['hallid']}}号厅 第{{empty($item['desk'])?"":$item['desk']}}桌
                            @endif

                        </div>
                    </div>
                    <div style="width: 65%;float: left;">
                        <div style="margin:3px 0 0 20px;">
                            @if(!empty($user) && count($user)>0)
                                @if(!empty($user[0]))
                                    <div style="float:left;width:40px;height:40px;background:url({{empty($user[0]['head'])?"":$user[0]['head']}});background-size:100% 100%;border-radius:10px;">
                                        @if(!empty($user[0]['ready']) && $user[0]['ready']==1)
                                            <img src="/img/share/ok.png" style="border-radius:10px;" width="40">
                                        @endif
                                    </div>
                                    <div style="float:left;margin-top: 10px;font-size: 0.6rem;">&nbsp;
                                        {{empty($user[0]['nick'])?"":$user[0]['nick']}}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div style="height: 35%">
                    <div style="width: 70%;float: left;">
                        <div style="margin-left: 5px;">
                            @if(!empty($user) && count($user)>0)
                                @if(!empty($user[1]))
                                    <div style="float:left;width:40px;height:40px;background:url({{empty($user[1]['head'])?"":$user[1]['head']}});background-size:100% 100%;border-radius:10px;">
                                        @if(!empty($user[1]['ready']) && $user[1]['ready']==1)
                                            <img src="/img/share/ok.png" style="border-radius:10px;" width="40">
                                        @endif
                                    </div>
                                    <div style="float:left;margin-top: 10px;font-size: 0.6rem;">&nbsp;
                                        {{empty($user[1]['nick'])?"":$user[1]['nick']}}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div style="width: 30%;float: left;">
                        <div style="margin-left: 5px;">
                            @if(!empty($user) && count($user)>0)
                                @if(!empty($user[2]))
                                    <div style="float:left;width:40px;height:40px;background:url({{empty($user[2]['head'])?"":$user[2]['head']}});background-size:100% 100%;border-radius:10px;">
                                        @if(!empty($user[2]['ready']) && $user[2]['ready']==1)
                                            <img src="/img/share/ok.png" style="border-radius:10px;" width="40">
                                        @endif
                                    </div>
                                    <div style="float:left;margin-top: 10px;font-size: 0.6rem;">&nbsp;
                                        {{empty($user[2]['nick'])?"":$user[2]['nick']}}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div style="height: 30%">
                    <div style="margin:3px 0 0 30px;">
                        @if(!empty($user) && count($user)>0)
                            @if(!empty($user[3]))
                                <div style="float:left;width:40px;height:40px;background:url({{empty($user[3]['head'])?"":$user[3]['head']}});background-size:100% 100%;border-radius:10px;">
                                    @if(!empty($user[3]['ready']) && $user[3]['ready']==1)
                                        <img src="/img/share/ok.png" style="border-radius:10px;" width="40">
                                    @endif
                                </div>
                                <div style="float:left;margin-top: 10px;font-size: 0.6rem;">&nbsp;
                                    {{empty($user[3]['nick'])?"":$user[3]['nick']}}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 20%;" align="center">
        <a href="http://fir.im/6svl">
            <img class="img-rounded " width="180" src="/img/share/download.png" />
        </a>
    </div>
</div>
</body>
</html>
