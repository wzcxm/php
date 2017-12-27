@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>添加黑名单</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">玩家ID：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="uid" >
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">封号</a>
        <a href="/Blacklist" class="weui-btn weui-btn_default weui-btn_loading">返回</a>
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
            $("#btn_save").click(function () {
                $.post('/Blacklist/save',{uid:$("#uid").val()},function(data){
                    if(data.msg==1){
                        location.href="/Blacklist";
                    }else{
                        $.alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection