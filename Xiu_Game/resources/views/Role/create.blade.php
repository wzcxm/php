@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>编辑角色</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">角色名称：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="rname" id="rname" value="{{empty($role)?"":$role->rname}}">
                <input type="hidden" id="roleid" value="{{empty($role)?"":$role->roleid}}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">角色说明：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="remarks" id="remarks" value="{{empty($role)?"":$role->remarks}}">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">保存</a>
        <a href="/Role" class="weui-btn weui-btn_default weui-btn_loading">返回</a>
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
                var R = new Object();
                R.roleid = $('#roleid').val();
                R.rname = $('#rname').val();
                R.remarks = $('#remarks').val();
                $.post('/Role/save',{data:R},function(data){
                    if(data.msg==1){
                        location.href="/Role";
                    }else{
                        $.alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection