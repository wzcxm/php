@extends('Layout.layout')
@section('content')
    购买房卡
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%">
            <tr>
                <td width="5%"></td>
                <td width="30%" align="right">房卡单价：</td>
                <td width="50%"><span id="sprice">{{$sprice}}</span>元/个</td>
                <td width="15%">
                    <input type="hidden" id="sid" value="{{$sid}}">
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">购买数量：</td>
                <td><input type="number" id="number" /></td>
                <td>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">付款方式：</td>
                <td><div class="wechat" ><img src="{{asset('/img/wx.jpg')}}" style="width:100px;height:35px;"/></div></td>
                <td>
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2"><button  data-theme="b"  id="btn_buy">立即购买</button></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3"><span id="msg" style="color:red;"></span></td>
            </tr>
        </table>
    </div>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_buy").click(function () {
                var number = $("#number").val();
                var sprice = $("#sprice").html();
                if(!comm.is_null(number)){
                    $("#msg").html("请输入购买数量！");
                    return;
                }else{$("#msg").html("");}
                var msg = "共计获得："+number+" 卡；\n应付金额："+(number*sprice)+" 元；\n您确定购买吗？"
                if(confirm(msg)){
                    $.get("/BuyBubble/buy/"+number,function (data) {
                        if(comm.is_null(data.Param)){
                            callpay(data.Param,data.orderno);
                        }
                        // alert(data.Error);
                    })
                }


            });
        });
        function jsApiCall(Parame,orderno)
        {
            var param = $.parseJSON(Parame);
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                param,
                function(res){
                    if (res.err_msg == "get_brand_wcpay_request:ok") {
                        $.post("/BuyBubble/SetCard/"+orderno, function (data) {
                            if (data.msg == 1){
                                window.location.href = "/Home";
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
@endsection