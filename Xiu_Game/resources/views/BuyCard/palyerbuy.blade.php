<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品购买</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <meta name="apple-touch-fullscreen" content="yes" />
    <link rel="stylesheet" href="{{ asset('/css/theme-classic.css')}}">
    <link rel="stylesheet" href="{{ asset('/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css')}}">
    <script src="{{asset('/js/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js')}}"></script>
    <script src="{{asset('/js/common.js?v=20170925')}}"></script>
</head>
<body>
<div data-role="page"  data-theme="d" >
    <div data-role="main" class="ui-content"  >
    <div class="ui-field-contain">
        @for($i=0;$i<sizeof($List);$i++)
            @if($i%2==0)
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <a href="#"
                           value="{{$List[$i]['sid']}}"
                           amount="{{$List[$i]['sprice']}}"
                           number="{{$List[$i]['snumber']+$List[$i]['sgive']}}"
                           onclick="changeId(this);"
                           class="ui-btn ui-corner-all ui-shadow" >
                            <b>{{$List[$i]['scommodity']}}</b><br>{{$List[$i]['sremarks']}}
                        </a>
                    </div>
                    @else
                        <div class="ui-block-b">
                            <a href="#"
                               value="{{$List[$i]['sid']}}"
                               amount="{{$List[$i]['sprice']}}"
                               number="{{$List[$i]['snumber']+$List[$i]['sgive']}}"
                               onclick="changeId(this);"
                               class="ui-btn ui-corner-all ui-shadow" >
                                <b>{{$List[$i]['scommodity']}}</b><br>{{$List[$i]['sremarks']}}
                            </a>
                        </div>
                </div>
            @endif
        @endfor
        @if(sizeof($List)%2!=0)
    </div>
    @endif

    <hr />
    <h5>支付方式：</h5>
    <div class="wechat" >
        <img src="{{asset('/img/wx.jpg')}}" style="width:100px;height:35px;"/>
    </div>
    <br />
    <br />
    <hr />
    <div style="width:100%;">
        <div style="width:50%;float: left;">总计获得：<span id="sumnumber"></span>&nbsp;&nbsp;卡</div>
        <div style="width:50%;float: right;">应付金额：<span id="sumamount"></span>&nbsp;&nbsp;元</div>
        <input type="hidden"  name='buyId' id="buyId" />
    </div>

    <hr />
        <table width="100%" >
            <tr>
                <td width="30%">游戏ID：</td>
                <td><input type="number"  name='gameid' id="gameid" /></td>
            </tr>
        </table>
    <hr>
    <div style="font-size: 13px;color: #a30f21;">
        提示：微信支付偶尔有到账延迟的问题，如出现这种情况，请联系客服【电话：0731-87081879】
    </div>
    <hr>
    <button  data-theme="b"  id="btn_buy">立即购买</button>
        <span id="msg" style="color:red;"></span>
    </div>
</div>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#btn_buy").click(function () {
            comm.btn_status('btn_buy','Disabled');
            var sid = $("#buyId").val();
            var gameid = $("#gameid").val();
            $("#msg").html("");
            if(!comm.is_null(gameid)){
                $("#msg").html("请输入游戏ID");
                comm.btn_status('btn_buy','Enabled');
                return;
            }
            if(!comm.is_null(sid)){
                $("#msg").html("请选择需要购买的商品！");
                comm.btn_status('btn_buy','Enabled');
                return;
            }
            $.get("/PlayerBuy/buy/"+sid+"/"+gameid,function (data) {
                if(comm.is_null(data.Error)){
                    $("#msg").html(data.Error);
                }else{
                    if(comm.is_null(data.Param)){
                        callpay(data.Param,data.orderno);
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
                    $.post("/PlayerBuy/SetCard/"+orderno, function (data) {
                        if (data.msg == 1){
                            alert('购买成功！')
                            window.location.reload();
                        }else{
                            alert(data.Error);
                        }
                    });
                }else{
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