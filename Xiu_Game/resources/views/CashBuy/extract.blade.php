
@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>提现申请</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">可提现返利：</label>
            </div>
            <div class="weui-cell__bd">
                <span id="backgold">{{$backgold}}</span>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">本次提取：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="gold" placeholder="请输入本次提取金额">
            </div>
        </div>
        <div class="weui-cell">
            <div style="margin-bottom:10px;text-align: center;font-size: 14px;font-weight: bold;color: coral;">
                提示：返利必须大于50，才能提现，且提现金额必须是正整数！
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_extr">提现到微信钱包</a>
    </div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div style="margin-bottom:10px;font-size: 14px;font-weight: bold;">
                <a href="/Extract/extlist">点此，查看提现记录</a>
            </div>
        </div>
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
            $("#btn_extr").click(function () {
                var gold = parseInt($("#gold").val());
                var backgold = parseInt($("#backgold").html());
                if(!comm.is_null(backgold) || backgold < 50){
                    $.alert("可提现返利小于50元，不能提现！");
                    return;
                }
                if(!comm.is_null(gold)){
                    $.alert("请输入提现金额！");
                    return;
                }
                if(gold > backgold){
                    $.alert("提现金额不能大于可提现返利！");
                    return;
                }
                if(gold < 1){
                    $.alert("提现金额不能小于1元！");
                    return;
                }
                $.post("/Extract/ext/"+gold,function (data) {
                    if(comm.is_null(data.Error)){
                        $.alert(data.Error);
                    }else{
                        $.alert('提现成功！',function () {
                            window.location.reload();
                        });
                    }
                });
            });
        });

    </script>
@endsection