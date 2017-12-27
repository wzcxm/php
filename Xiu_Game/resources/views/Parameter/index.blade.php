@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>参数设置</h3>
    </header>
    <div class="weui-cells weui-cells_form" style="font-size: 15px;">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">上级返利：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="upper_one" placeholder="%" value="{{empty($param['upper_one'])?0:$param['upper_one']}}">
            </div>
        </div>
        <div class="weui-cell" >
            <div class="weui-cell__hd">
                <label class="weui-label">上上级返利：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="upper_two" placeholder="%" value="{{empty($param['upper_two'])?0:$param['upper_two']}}">
            </div>
        </div>
        <div class="weui-cell" >
            <div class="weui-cell__hd">
                <label class="weui-label">玩家返利：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="invitation" value="{{empty($param['invitation'])?0:$param['invitation']}}">
            </div>
        </div>
        <div class="weui-cell" >
            <div class="weui-cell__hd">
                <label class="weui-label">分享送钻：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="share"  value="{{empty($param['share'])?0:$param['share']}}">
            </div>
        </div>
    </div>
    <div class="weui-btn-area" >
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">保存</a>
        {{--<a href="/Role" class="weui-btn weui-btn_default weui-btn_loading">返回</a>--}}
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                var obj = new Object();
                obj.upper_one = $("#upper_one").val();
                obj.upper_two = $("#upper_two").val();
                obj.invitation = $("#invitation").val();
                obj.share = $("#share").val();
                $.post('/Parameter',{data:obj},function(data){
                    if(data.msg==1){
                        //location.href="/Role_Menu";
                        $.alert('保存成功！')
                    }else{
                        $.alert(data.msg);
                    }
                });
            });
        });
    </script>
@endsection