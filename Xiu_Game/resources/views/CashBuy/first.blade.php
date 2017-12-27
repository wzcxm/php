@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>请设置您的提现密码</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">提现密码：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  id="pwd" placeholder="请设置提现密码（长度不少于6位）">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">确认密码：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  id="query_pwd" placeholder="请输入确认密码">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_sub">提交</a>
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
            $("#btn_sub").click(function () {
                var pwd  = $("#pwd").val();
                var query_pwd  = $("#query_pwd").val();
                if(!comm.is_null(pwd)){
                    $.alert("请输入密码！");
                    return;
                }else{
                    if(pwd.length<6){
                        $.alert("密码长度必须大于6位！");
                        return;
                    }
                }
                if(!comm.is_null(query_pwd)){
                    $.alert("请输入确认密码！");
                    return;
                }else{
                    if(pwd != query_pwd){
                        $.alert("两次密码不相同！");
                        return;
                    }
                }
                $.post('/Extract/first/save',{pwd:pwd},function (result) {
                    if(!comm.is_null(result.Error)){
                        $.alert('提现密码设置成功！',function () {
                            window.location.href = '/Extract/login';
                        });
                    }else{
                        $.alert(result.Error);
                    }
                });
            });
        });
    </script>
@endsection