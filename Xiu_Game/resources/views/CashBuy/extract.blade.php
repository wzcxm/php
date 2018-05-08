
@extends('Layout.WeUiLayout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/extract.css')}}?v=201805074">
@endsection
@section('content')
    <div  class="weui-flex head_bg" >

    </div>
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 28%;text-align: right;">
                    我的提成：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    {{$backgold}}
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 28%;text-align: right;">
                    本次提取：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="input_number" type="number" id="gold" style="width: 50%;">
                    <div style="font-size: 0.6rem;font-weight: bold;color: coral;">
                        提示：提取必须大于50。
                    </div>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 28%;text-align: right;">
                    手机验证码：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="input_number" type="number" id="code" style="width: 50%;">
                    <button class="code_bg" id="get_code">获取</button>
                    @if(empty($tel))
                        <div style="font-size: 0.6rem;font-weight: bold;color: coral;">
                            提示：请先绑定手机。
                        </div>
                    @else
                        <input type="hidden" id="tel" value="{{$tel}}">
                    @endif
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <button class="search_bg" style="width: 80%;margin-left: 10%;"  id="btn_extr">提现到微信钱包</button>
        </div>
        <div style="width:100%;text-align: center;font-size: 0.8rem;">
            <a href="javascript:window.location.href = '/Extract/extlist'" style="color: #2dbaa7;">提现记录</a>
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
            $("#get_code").click(function () {
                var tel = $("#tel").val();
                if(!comm.is_null(tel)){
                    $.alert("请先绑定手机号!");
                    return;
                }
                $.get('/sms/'+tel,function(data){
                    if(comm.is_null(data.Error)){
                        $.alert(data.Error);
                    }else{
                        settime();
                    }
                });
            });


            $("#btn_extr").click(function () {
                comm.btn_status('btn_extr','Disabled');
                var R = new Object();
                R.gold = parseInt($("#gold").val());
                R.code = $("#code").val();
                $.post("/Extract/ext",{data:R},function (data) {
                    if(comm.is_null(data.Error)){
                        $.alert(data.Error);
                    }else{
                        $.alert('提现成功！',function () {
                            window.location.reload();
                        });
                    }
                    comm.btn_status('btn_extr','Enabled');
                });
            });
        });
        var countdown=60;
        function settime() {
            if (countdown == 0) {
                $("#get_code").prop('disabled',false).css({'background-color':'#30c0a4'});
                $("#get_code").html("获取");
                countdown = 60;
            } else {
                $("#get_code").prop('disabled',true).css({'background-color':'#a6afad'});
                $("#get_code").html(countdown + "s");
                countdown--;
                setTimeout(function() {
                        settime();
                    },
                    1000)
            }
        }
    </script>
@endsection