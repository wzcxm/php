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
    <title>游戏充值</title>
    <link rel="stylesheet" href="{{asset('js/weui/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/weui/css/jquery-weui.min.css')}}">
    <style>
        body, html {
            height: 100%;
            -webkit-tap-highlight-color: transparent;
        }
        .ipt{
            height: 1.3rem;
            border-radius:6px;
            border: 1px solid #DBDBDB;
            outline:none;
        }
        .mall{
            width: 70px;
            background-color: #a7cfea;
            border-radius:6px;
            border: 1px solid #DBDBDB;
            margin: 5px 5px 5px 5px ;
            color: #FAFAD2;
            font-size: 0.65rem;
            float: left;
        }
        .mall_n{
            background-color: #79bae7;
            border-radius:6px;
            margin: 3px 3px 3px 3px ;
        }
        .mall_n_cl{
            background-color: #5b8fb3;
            border-radius:6px;
            margin: 3px 3px 3px 3px ;
        }
    </style>
</head>
<body>
<div style="height: 27%;background:url(/img/diamond/buy_bg.png);background-size:100% 100%;"></div>
<div style="height: 58%;">
    <div style="height: 20%;">
        <div style="height: 5px;"></div>
        <table width="100%">
        <tr>
            <td align="right" width="30%">玩家ID：</td>
            <td>
                <input id="plyerid" class="ipt" style="color:green;" value="{{empty($player)?"":$player->uid}}">
                <button class="ipt" style="background-color: coral;color: white;" id="getnick"> 显示昵称 </button>
            </td>
        </tr>
        <tr>
            <td align="right">玩家昵称：</td>
            <td><span id="nick" style="color:red;">{{empty($player)?"":$player->nickname}}</span></td>
        </tr>
    </table>
        <div style="color: red;font-size: 0.5rem;">&nbsp;&nbsp;&nbsp;&nbsp;注：请仔细核对ID，慎重充值，充值提成以玩家ID为准，冲错自理！</div>
    </div>
    <div style="height: 45%; ">
        <div style="margin: 0 20px 0 20px;" id="mall_list">
            @if(!empty($mallList))
                @foreach($mallList as $item)
                    <div class="mall">
                        <div class="mall_n" name="sp_mall" id="{{$item->sid}}">
                            <div >{{$item->scommodity}}</div>
                            <div style="width: 65px;height: 65px;background:url(/img/diamond/{{$item->img}});background-size:100% 100%;"></div>
                            @if(strpos($item->sremarks,"，")!== false)
                                <div style="text-align: center;">{{explode("，",$item->sremarks)[0]}}</div>
                                <div style="text-align: center;color:red;font-size: 0.4rem;">{{explode("，",$item->sremarks)[1]}}</div>
                            @else
                                <div style="text-align: center;">{{$item->sremarks}}</div>
                                <div style="text-align: center;color:red;font-size: 0.4rem;"><br><br></div>
                            @endif

                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div style="height: 35%; ">
        <hr>

        <div style="font-size: 0.6rem;">&nbsp;&nbsp;&nbsp;&nbsp;充值5000元以上，请联系客服：刘先生 18153816881</div>
        <table width="100%" style="margin-top: 5px;">
            <tr>
                <td align="right" width="35%">推荐代理ID：</td>
                <td><input id="front" type="number" class="ipt" value="{{empty($player)?"":$player->front_uid}}"></td>
            </tr>
            <tr>
                <td></td><td><span style="font-size: 0.6rem;">注：填写普通玩家ID无效！</span></td>
            </tr>
        </table>
        <input type="hidden" id="sid">
    </div>
</div>
<div style="height:15%;background-color: #79bae7;">
    <div style="height: 1px;"></div>
    <div class="weui-btn-area" style="text-align: center;">
        <button class="weui-btn weui-btn_primary"  id="btn_buy">确认充值</button>
        <a  href="javascript:window.location.href = '/PlayerBuy/list/'+$('#plyerid').val()">充值记录</a>
    </div>
</div>
<script src="{{asset('js/weui/js/jquery-2.1.4.js')}}"></script>
<script src="{{asset('js/weui/js/jquery-weui.min.js')}}"></script>
<script src="{{asset('/js/common.js')}}"></script>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("div[name='sp_mall']").on('click',function () {
            $("div[name='sp_mall']").removeClass().addClass("mall_n");
            $(this).removeClass().addClass("mall_n_cl");
            $("#sid").val(this.id);
        });

        //获取昵称
        $("#getnick").click(function () {
            var uid = $("#plyerid").val();
            if(!comm.is_null(uid)){
                $.alert('请输入玩家ID！');
                return;
            }
            $.get("/PlayerBuy/getnick/"+uid,function (reslut) {
                if(!comm.is_null(reslut)){
                    $.alert('玩家ID错误，找不到该玩家昵称!');
                }else{
                    $("#nick").html(reslut);
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
    function  changeId(obj) {
        var id = $(obj).attr('value');
        var number = $(obj).attr('number');
        var amount = $(obj).attr('amount');
        $("#buyId").val(id);
        $("#sumnumber").html(number);
        $("#sumamount").html(amount);
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
                        window.location.reload();
                    });
                }else{
                    $.post("/PlayerBuy/delno",{no:orderno}, function (data) {
                        $.alert('支付失败！');
                    });
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