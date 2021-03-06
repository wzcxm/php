<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="游戏充值">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>休休游戏--游戏充值</title>
    <link rel="stylesheet" href="{{asset('js/weui/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/weui/css/jquery-weui.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}?v=2018072002">
</head>
<body>
<div class="buy_head_bg"></div>
<div class="buy_bg">
    <div style="width:100%;">
        <div style="float: left;width: 40%;text-align: right;">
            <p style="font-size: 0.9rem;color: #0f695f;font-weight: 500;">玩家ID：</p>
        </div>
        <div style="float: right;width: 60%;text-align: left;">
            <div style="width:100%;">
                <div style="float: left;width: 50%;text-align: left;">
                    <input id="plyerid" type="number" class="buy_input" value="{{empty($player)?"":$player->uid}}">
                </div>
                <div style="float: right;width: 50%;text-align: left;">
                    <a href="#" id="getnick" style="margin-left: 10px;">
                        <img class="img_border " width="70%" src="/img/diamond/nick.png" />
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div style="width:100%;">
        <div style="float: left;width: 40%;text-align: right;">
            <p style="font-size: 0.9rem;color: #0f695f;font-weight: 500;">玩家昵称：</p>
        </div>
        <div style="float: right;width: 60%;text-align: left;">
            <div id="nick" class="buy_nick" >{{empty($player)?"":$player->nickname}}</div>
        </div>
    </div>
    <div style="margin-top:5px;" id="mall">
        @if(!empty($mallList))
            @foreach($mallList as $item)
                <img class="img_border " width="22%" src="/img/diamond/{{$item->img}}?v=20181013" id="{{$item->sid}}" onclick="img_click(this)"/>
            @endforeach
        @endif
    </div>
    <div style="margin-top:5px;text-align: center;" id="first">
        {{--<img class="img_border " width="95%" src="/img/diamond/first.png?v=20180817" />--}}
    </div>
    <div style="width: 100%;text-align: center;">
        <img class="img_border " width="95%" src="/img/diamond/title.png">
    </div>
    <div id="div_front">
        @if(empty($player->front_uid) && $player->rid != 3 && $player->rid != 4)
            <div style="width:100%;margin-top: 5px;">
                <div style="float: left;width: 50%;text-align: right;">
                    <p style="font-size: 0.9rem;color: #0f695f;font-weight: 500;">推荐代理ID：</p>
                </div>
                <div style="float: right;width: 50%;text-align: left;">
                    <input id="front" type="number" class="front_input" value="{{!empty($at_id)?$at_id:$player->chief_uid}}">
                </div>
            </div>
        @endif
    </div>
    <div style="width: 100%;margin-top: 5px;text-align: center;">
        <div style="width: 100%;">
            <a href="#" id="btn_buy" >
                <img class="img_border " width="35%" src="/img/diamond/query.png" />
            </a>
        </div>
    </div>
    <input type="hidden" id="sid">
</div>
<script src="{{asset('js/weui/js/jquery-2.1.4.js')}}"></script>
<script src="{{asset('js/weui/js/jquery-weui.min.js')}}"></script>
<script src="{{asset('js/weui/js/fastclick.js')}}"></script>
<script src="{{asset('/js/common.js')}}"></script>
<script>
    $(function () {
        $('body').height($('body')[0].clientHeight);
        FastClick.attach(document.body);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //获取昵称
        $("#getnick").click(function () {
            var uid = $("#plyerid").val();
            if(!comm.is_null(uid)){
                $.alert('请输入玩家ID！');
                return;
            }
            $.get("/PlayerBuy/getnick/"+uid,function (reslut) {
                if(!comm.is_null(reslut.user)){
                    $.alert('玩家ID错误，找不到该玩家昵称!');
                }else{
                    $("#nick").html(reslut.user['nickname']);
                    if(comm.is_null(reslut.user['front_uid']) && reslut.user['front_uid']!=0){
                        $("#div_front").empty()
                    }else {
                        $("#div_front").empty().append("<div style='width:100%;margin-top: 5px;'>"+
                            "<div style='float: left;width: 50%;text-align: right;'>"+
                            "<p style='font-size: 0.9rem;color: #0f695f;font-weight: 500;'>推荐代理ID：</p></div>"+
                            "<div style='float: right;width: 50%;text-align: left;'>"+
                            "<input id='front' type='number' class='front_input' value='"+reslut.user['chief_uid']+"' ></div></div>");
                    }
                }
            });
        });

        //确认充值
        $("#btn_buy").click(function () {
            comm.btn_status('btn_buy','Disabled');
            var Obj = new Object();
            Obj.sid = $("#sid").val();
            Obj.playerid = $("#plyerid").val();
            Obj.front = $("#front").val();
            if(!comm.is_null(Obj.playerid)){
                $.alert('请输入玩家ID！');
                comm.btn_status('btn_buy','Enabled');
                return;
            }
            if(!comm.is_null(Obj.sid)){
                $.alert("请选择需要购买的商品！");
                comm.btn_status('btn_buy','Enabled');
                return;
            }
            $.get("/PlayerBuy/buy",{data:Obj},function (reslut) {
                if(comm.is_null(reslut.Error)){
                    $.alert(reslut.Error);
                }else{
                    if(comm.is_null(reslut.Param)){
                        callpay(reslut.Param,reslut.orderno);
                    }
                }
                comm.btn_status('btn_buy','Enabled');
            });
        });
    });

    function img_click(img) {
        $("#mall img").removeClass().addClass("img_border");
        $(img).removeClass().addClass("img_border_select");
        $("#sid").val(img.id);
    }

    function jsApiCall(Parame,orderno)
    {
        var param = $.parseJSON(Parame);
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            param,
            function(res){
                if (res.err_msg == "get_brand_wcpay_request:ok") {
                    $.alert('支付成功！',function () {
                        //window.location.reload();
                    });
                }else{
                    // $.post("/PlayerBuy/delno",{no:orderno}, function (data) {
                    //     $.alert('支付失败！');
                    // });
                }
            }
        );
    }

    function callpay(Parame,orderno)
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall(Parame,orderno);
        }
    }
</script>
</body>
</html>