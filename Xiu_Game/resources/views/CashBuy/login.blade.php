@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>请输入您的提现密码</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">提现密码：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="password"  id="pwd" placeholder="请输入您的提现密码">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_sub">提交</a>
    </div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div style="margin-bottom:30px;text-align: center;font-size: 14px;font-weight: bold;">
                <a href="/Extract/first">如果您忘记密码，点此重设</a>
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
            $("#btn_sub").click(function () {
                var pwd =$('#pwd').val();
                if(!comm.is_null(pwd)){
                    $.alert("请输入密码！");
                    return;
                }
                $.post('/Extract/login/submit',{pwd:pwd},function (result) {
                    if(!comm.is_null(result.Error)){
                        window.location.href = '/Extract/index';
                    }else{
                        $.alert(result.Error);
                    }
                })
            });
        });
    </script>
@endsection