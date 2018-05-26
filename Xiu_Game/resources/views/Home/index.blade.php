@extends('Layout.WeUiLayout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/home.css')}}?v=2018042402">
@endsection
@section('content')
@if(!empty($User))
    <div  class=" home_bg" >
        <div style="float:left;width: 40%;">
            <div style="text-align: right;">
                <img src="{{$User->head_img_url}}" class="head" width="80%">
            </div>
        </div>
        <div style="float:right;width: 60%;color: white;">
            <div style="text-align:right;margin-top: 20px;">
                <span class="role">{{$User->rname}}</span>
            </div>
           <div style="margin-left: 15%;">
               <span style="font-weight: 400;font-size: 1rem;">{{$User->nickname}}</span>
           </div>
            <div style="width: 100%">
                <div style="float: left;width: 30%;text-align: right;">
                    ID：
                </div>
                <div style="float: right;width: 70%;text-align: left;">
                    {{$User->uid}}
                </div>
            </div>
            <div style="width: 100%">
                <div style="float: left;width: 30%;text-align: right;">
                    手机：
                </div>
                <div style="float: right;width: 70%;text-align: left;">
                    <a href="javascript:$('#bind_tel').show('fast');"  style="color:#a3ff04;">
                        @if(!empty(trim($User->uphone)))
                            {{$User->uphone}}
                        @else
                            绑定手机
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div  class="weui-flex zs_bg" >
        <div class="weui-flex__item" style="text-align: center;">
            钻石：{{$User->roomcard}}
        </div>
        <div class="weui-flex__item" style="text-align: center;">
            @if($roleid==3 || $roleid==4)
                渠道ID：{{$User->aisle}}
            @else
                金豆：{{$User->gold}}
            @endif
        </div>
    </div>
@endif
<div  class="weui-flex" style="font-size:  0.75rem;padding: 5px 5px 5px 5px;">
    @if($roleid==1)
        <div class="weui-flex__item" style="text-align: center;">
            <span>下载人数：</span>
            <span style="color:#f11367;">{{$count_person}}</span>
        </div>
        <div class="weui-flex__item" style="text-align: center;">
            <span>在线人数：</span>

            <span style="color:#f11367;">{{$today_person}}</span>
        </div>
    @else
        <div class="weui-flex__item" style="text-align: center;">
            当月提成：<a style="color:#f11367;">{{$month}}</a>
            <br>
            总提成：<a style="color:#f11367;">{{$total_num}}</a>
        </div>
        <div class="weui-flex__item" style="text-align: center;">
            @if($roleid == 3 || $roleid == 4)
               提成比例<br>旗下充值：{{$back_agent}}%
            @else
                提成比例<br>下级充值：{{$back_agent}}%<br>下下级充值：{{$back_agent_front}}%
            @endif
        </div>
    @endif

</div>
<div class="weui-grids">
    @if(!empty($Menus))
        @foreach($Menus as $menu)
            @if($menu->linkurl!='/Home' && $menu->linkurl!='/MyInfo')
                <a href="{{$menu->linkurl=="/BuyBubble"?$menu->linkurl."/index":$menu->linkurl}}" class="weui-grid js_grid">
                    <div class="weui-grid__icon">
                        <img src="img/home/{{$menu->icon}}">
                    </div>
                    <p class="weui-grid__label">
                        {{$menu->name}}
                    </p>
                </a>
            @endif
        @endforeach
    @endif
</div>
<div id="bind_tel" style="display: none;">
    <div class="weui-mask weui-mask--visible"></div>
    <div class="weui-dialog weui-dialog--visible">
        <div class="weui-dialog__hd" style="position:relative;">
            <strong class="weui-dialog__title">绑定手机号</strong>
            <img src="img/login/close.png" width="20"
                 style="position:absolute; top:10px; right:10px; z-index:10;"
                 href="#" onclick="$('#bind_tel').hide('fast');">
        </div>
        <div class="weui-dialog__bd">
            <p class="weui-prompt-text">
                <a style="color: red;">请慎重输入手机号，如果您已绑定手机号，将覆盖原手机号</a>
            </p>
        </div>
        <div style="text-align:center;margin-top: 6%;">
            <span style="font-size: 0.8rem;">手机号</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <input id="tel" type="number"  style="width: 49%" class="inp_txt" />

        </div>
        <hr width="80%" style="margin-left: 10%">
        <div style="text-align:center;margin-top: 6%;">
            <span style="font-size: 0.8rem;">验证码</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <input id="code" type="number"  style="width: 25%" class="inp_txt" />
            <button class="code_bg" id="get_code">点击获取</button>

        </div>
        <hr width="80%" style="margin-left: 10%">
        <div style="text-align:center;height: 21px;">
                <span style="color: red;font-size: 1em;" id="message">
                    @if(session('message'))
                        {{ session('message') }}
                    @endif
                </span>
        </div>
        <button class="save_bg" id="tel_save">保存</button>
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
                $("#message").html("");
                if(!comm.is_null(tel)){
                    $("#message").html("请输入手机号!");
                    return;
                }
                $.get('/sms/'+tel,function(data){
                    if(comm.is_null(data.Error)){
                        $("#message").html(data.Error);
                    }else{
                        settime();
                    }
                });
            });
            $("#tel_save").click(function () {
                var tel = $("#tel").val();
                var code = $("#code").val();
                $("#message").html("");
                if(!comm.is_null(tel)){
                    $("#message").html("请输入手机号!");
                    return;
                }
                if(!comm.is_null(code)){
                    $("#message").html("请输入验证码!");
                    return;
                }
                $.post('/Home/bindPhone',{tel:tel,code:code},function (reslut) {
                    if(comm.is_null(reslut.Error)){
                        $("#message").html(reslut.Error);
                    }else{
                        $('#bind_tel').hide('fast',window.location.reload());

                    }
                });
            });
        });

        var countdown=60;
        function settime() {
            if (countdown == 0) {
                $("#get_code").prop('disabled',false).css({'background-color':'#30c0a4'});
                $("#get_code").html("点击获取");
                countdown = 60;
            } else {
                $("#get_code").prop('disabled',true).css({'background-color':'#a6afad'});
                $("#get_code").html(countdown + "s");
                countdown--;
                setTimeout(function() {
                        settime();
                    },
                    1000);
            }
        }
    </script>
@endsection

