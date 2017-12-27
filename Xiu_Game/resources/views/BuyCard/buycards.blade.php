@extends('Layout.WeUiLayout')
@section('style')

@endsection
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>商品购买</h3>
    </header>
    <div class="weui-cells__title">商品列表</div>
    <div class="weui-cells weui-cells_radio" id="radio_list">
        @if($List)
            @foreach($List as $item)
                <label class="weui-cell weui-check__label" for="{{$item->sid}}" >
                    <div class="weui-cell__hd"><i class="fa fa-diamond" style="color: #0baae4;"></i></div>
                    <div class="weui-cell__bd">
                        <p style="color: red;padding-left: 10px;">{{$item->scommodity}}</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="radio1" class="weui-check" id="{{$item->sid}}" onclick="changeId('{{$item->sid}}','{{$item->sprice}}','{{$item->snumber}}')">
                        <span class="weui-icon-checked"></span>
                        {{$item->sremarks}}
                    </div>
                </label>
            @endforeach
        @endif
    </div>
    <div class="weui-flex">
        <div class="weui-flex__item">
            <div style="padding:10px 0 0 20px; ">总计获得：<span id="sum_card"></span>&nbsp;&nbsp;卡</div>
        </div>
        <div class="weui-flex__item">
            <div style="padding:10px 0 0 20px; ">应付金额：<span id="sum_total"></span>&nbsp;&nbsp;元</div>
            <input type="hidden" id="sid">
        </div>
    </div>
    <div class="weui-btn-area">
        <button class="weui-btn weui-btn_primary"  id="btn_buy">微信购买</button>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_buy").click(function () {
                comm.btn_status('btn_buy','Disabled');
                var sid = $("#sid").val();
                if(comm.is_null(sid)){
                    $.get("/BuyBubble/buy/"+sid,function (data) {
                        if(comm.is_null(data.Param)){
                            callpay(data.Param,data.orderno);
                        }
                        comm.btn_status('btn_buy','Enabled');
                    })
                }else{
                    $.alert("请选择需要购买的商品！");
                    comm.btn_status('btn_buy','Enabled');
                }
            });
        });
        function changeId(sid,amount,number) {
            $("#sid").val(sid);
            $("#sum_card").html(number);
            $("#sum_total").html(amount);
        }
        function jsApiCall(Parame,orderno) {
            var param = $.parseJSON(Parame);
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                param,
                function(res){
                    if (res.err_msg == "get_brand_wcpay_request:ok") {
                        $.post("/BuyBubble/SetCard/"+orderno, function (data) {
                            if (data.msg == 1){
                                $.alert('购买成功！',function () {
                                    window.location.reload();
                                });
                            }else{
                                $.alert(data.Error);
                            }
                        });
                    }else{
                        //支付失败或取消支付，删除订单信息
                        $.post("/BuyBubble/del",{NO:orderno}, function (data) {
                           if(comm.is_null(data.Error)){
                               $.alert(data.Error);
                           }else{
                               //$.alert("取消支付！");
                           }
                        });
                    }
                }
            );
        }
        function callpay(Parame,orderno) {
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