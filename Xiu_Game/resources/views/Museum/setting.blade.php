@extends('Layout.WeUiLayout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/museumset.css')}}?v=201804261">
@endsection
@section('content')

    <div  class="weui-flex tea_head_bg" >
        <div style="margin-top: 10px;margin-bottom: 10px">牌馆设置</div>
    </div>
    <div  class="weui-flex muse_name_bg" >
        <div class="weui-flex__item" style="text-align: center;">
            牌馆ID：{{!empty($tea->tea_id)?$tea->tea_id:0}}
        </div>
        <div class="weui-flex__item" style="text-align: center;">
            牌馆名称：{{!empty($tea->tea_name)?$tea->tea_name:''}}
        </div>
        <input type="hidden" id="teaid" value="{{!empty($tea->tea_id)?$tea->tea_id:0}}">
    </div>
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div style="font-size: 0.7rem;width: 100%;">
                <div style="font-size: 0.8rem;font-weight: 600;">
                    <div style="float: left;width: 70%;">一号厅规则设置：</div>
                    <div style="float: right;width: 30%;">
                        <button style="margin-left: 30%;" class="{{$tea->jfoff1==0?'open_btn':'close_btn'}}"
                                onclick="open_close(this,'jfoff1','setting_one')">{{$tea->jfoff1==0?'开启':'关闭'}}
                        </button>
                        <input type="hidden" id="jfoff1" value="{{$tea->jfoff1}}">
                    </div>
                </div>
                <div style="display: {{$tea->jfoff1==0?'none':'block'}};" id="setting_one">
                    <div>
                        1、1积分兑换
                        <input type="number" id="jifen1" class="inp_sty" value="{{empty($tea->jifen1)?0:$tea->jifen1}}">
                        体力</div>
                    <div>
                        2、体力负
                        <input type="number" id="score1" class="inp_sty" value="{{empty($tea->score1)?0:$tea->score1}}">
                        禁入牌桌。是否启用
                        @if(!empty($tea) && !empty($tea->off1) && $tea->off1==1)
                            <input type="checkbox" id = "off1" class="weui-agree__checkbox" checked="checked" onclick="ckb_setvalue('off_one')">
                            <input type="hidden" id="off_one" value="1">
                        @else
                            <input type="checkbox" id = "off1" class="weui-agree__checkbox" onclick="ckb_setvalue('off_one')">
                            <input type="hidden" id="off_one" value="0">
                        @endif
                    </div>
                    <div>
                        3、大赢家扣体力标准：扣体力标准值<input type="number" id="bzfen1" class="inp_sty" value="{{empty($tea->bzfen1)?0:$tea->bzfen1}}">，<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;小于该标准值扣<input type="number" id="mincf1" class="inp_sty" value="{{empty($tea->mincf1)?0:$tea->mincf1}}"> ,<br>
                        <div style="float: left;width: 70%;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;大于等于该标准值扣<input type="number" id="huilv1" class="inp_sty" value="{{empty($tea->huilv1)?0:$tea->huilv1}}">。
                        </div>
                        <div style="float: right;width: 30%;">
                            <button style="margin-left: 30%;" class="open_btn" id="hall_one">保存</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="weui-cell">
            <div style="font-size: 0.7rem;width: 100%;">
                <div style="font-size: 0.8rem;font-weight: 600;">
                    <div style="float: left;width: 70%;">二号厅规则设置：</div>
                    <div style="float: right;width: 30%;">
                        <button style="margin-left: 30%;" class="{{$tea->jfoff2==0?'open_btn':'close_btn'}}"
                                onclick="open_close(this,'jfoff2','setting_two')">{{$tea->jfoff2==0?'开启':'关闭'}}
                        </button>
                        <input type="hidden" id="jfoff2" value="{{$tea->jfoff2}}">
                    </div>
                </div>
                <div style="display: {{$tea->jfoff2==0?'none':'block'}};" id="setting_two">
                    <div>
                        1、1积分兑换
                        <input type="number" id="jifen2" class="inp_sty" value="{{empty($tea->jifen2)?0:$tea->jifen2}}">
                        体力</div>
                    <div>
                        2、体力负
                        <input type="number" id="score2" class="inp_sty" value="{{empty($tea->score2)?0:$tea->score2}}">
                        禁入牌桌。是否启用
                        @if(!empty($tea) && !empty($tea->off2) && $tea->off2==1)
                            <input type="checkbox" id = "off2" class="weui-agree__checkbox" checked="checked" onclick="ckb_setvalue('off_two')">
                            <input type="hidden" id="off_two" value="1">
                        @else
                            <input type="checkbox" id = "off2" class="weui-agree__checkbox" onclick="ckb_setvalue('off_two')">
                            <input type="hidden" id="off_two" value="0">
                        @endif
                    </div>
                    <div>
                        3、大赢家扣体力标准：扣体力标准值<input type="number" id="bzfen2" class="inp_sty" value="{{empty($tea->bzfen2)?0:$tea->bzfen2}}">，<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;小于该标准值扣<input type="number" id="mincf2" class="inp_sty" value="{{empty($tea->mincf2)?0:$tea->mincf2}}">,<br>
                        <div style="float: left;width: 70%;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;大于等于该标准值扣<input type="number" id="huilv2" class="inp_sty" value="{{empty($tea->huilv2)?0:$tea->huilv2}}">。
                        </div>
                        <div style="float: right;width: 30%;">
                            <button style="margin-left: 30%;" class="open_btn" id="hall_two">保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="font-size: 0.7rem;width: 100%;">
                <div style="font-size: 0.8rem;font-weight: 600;width: 100%;">
                    <div style="float: left;width: 70%;">三号厅规则设置：</div>
                    <div style="float: right;width: 30%;">
                        <button style="margin-left: 30%;" class="{{$tea->jfoff3==0?'open_btn':'close_btn'}}"
                                onclick="open_close(this,'jfoff3','setting_three')">{{$tea->jfoff2==3?'开启':'关闭'}}
                        </button>
                        <input type="hidden" id="jfoff3" value="{{$tea->jfoff3}}">
                    </div>
                </div>
                <div style="display: {{$tea->jfoff3==0?'none':'block'}};" id="setting_three">
                    <div>
                        1、1积分兑换
                        <input type="number" id="jifen3" class="inp_sty" value="{{empty($tea->jifen3)?0:$tea->jifen3}}">
                        体力</div>
                    <div>
                        2、体力负
                        <input type="number" id="score3" class="inp_sty" value="{{empty($tea->score3)?0:$tea->score3}}">
                        禁入牌桌。是否启用
                        @if(!empty($tea) && !empty($tea->off3) && $tea->off3==1)
                            <input type="checkbox" id = "off3" class="weui-agree__checkbox" checked="checked" onclick="ckb_setvalue('off_three')">
                            <input type="hidden" id="off_three" value="1">
                        @else
                            <input type="checkbox" id = "off3" class="weui-agree__checkbox" onclick="ckb_setvalue('off_three')">
                            <input type="hidden" id="off_three" value="0">
                        @endif
                    </div>
                    <div>

                        3、大赢家扣体力标准：扣体力标准值<input type="number" id="bzfen3" class="inp_sty" value="{{empty($tea->bzfen3)?0:$tea->bzfen3}}">，<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;小于该标准值扣<input type="number" id="mincf3" class="inp_sty" value="{{empty($tea->mincf3)?0:$tea->mincf3}}">,<br>
                        <div style="float: left;width: 70%;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;大于等于该标准值扣<input type="number" id="huilv3" class="inp_sty" value="{{empty($tea->huilv3)?0:$tea->huilv3}}">。
                        </div>
                        <div style="float: right;width: 30%;">
                            <button style="margin-left: 30%;" class="open_btn" id="hall_three">保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="weui-cell" style="color: red;font-size: 0.7rem;">
            &nbsp;&nbsp;&nbsp;&nbsp;提示：大赢家不止一位时，大赢家扣的体力除以大赢家人数，向上取整，例如：大赢家扣5体力，大赢家人数2，则每人扣3体力
        </div>
        {{--<div class="weui-cell">--}}
            {{--<button class="save_bg" style="width: 80%;margin-left: 10%;"  id="btn_save">保存</button>--}}
            {{--<button class="cancel_bg" style="width: 80%;margin-left: 10%;" onclick="javascript:window.location.href='/Museum'">返回</button>--}}
        {{--</div>--}}
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
            $("#hall_one").click(function () {
                var obj = new Object();
                obj.tea_id = $("#teaid").val();
                obj.score1 = $("#score1").val();
                obj.off1 = $("#off_one").val();
                obj.jifen1 = $("#jifen1").val();
                obj.huilv1 = $("#huilv1").val();
                obj.bzfen1 = $("#bzfen1").val();
                obj.mincf1 = $("#mincf1").val();
                obj.jfoff1 = $("#jfoff1").val();
                $.post('/Museum/save',{data:obj},function(data){
                    if(data.status==1){
                        $.alert('保存成功！',function () {
                            window.location.reload();
                        })
                    }else{
                        $.alert(data.message);
                    }
                });
            });
            $("#hall_two").click(function () {
                var obj = new Object();
                obj.tea_id = $("#teaid").val();
                obj.score2 = $("#score2").val();
                obj.off2 = $("#off_two").val();
                obj.jifen2 = $("#jifen2").val();
                obj.huilv2 = $("#huilv2").val();
                obj.bzfen2 = $("#bzfen2").val();
                obj.mincf2 = $("#mincf2").val();
                obj.jfoff2 = $("#jfoff2").val();
                $.post('/Museum/save',{data:obj},function(data){
                    if(data.status==1){
                        $.alert('保存成功！',function () {
                            window.location.reload();
                        })
                    }else{
                        $.alert(data.message);
                    }
                });
            });
            $("#hall_three").click(function () {
                var obj = new Object();
                obj.tea_id = $("#teaid").val();
                obj.score3 = $("#score3").val();
                obj.off3 = $("#off_three").val();
                obj.jifen3 = $("#jifen3").val();
                obj.huilv3 = $("#huilv3").val();
                obj.bzfen3 = $("#bzfen3").val();
                obj.mincf3 = $("#mincf3").val();
                obj.jfoff3 = $("#jfoff3").val();
                $.post('/Museum/save',{data:obj},function(data){
                    if(data.status==1){
                        $.alert('保存成功！',function () {
                            window.location.reload();
                        })
                    }else{
                        $.alert(data.message);
                    }
                });
            });
        });
        function ckb_setvalue(control) {
            if($("#"+control).val() == 1){
                $("#"+control).val(0);
            }else{
                $("#"+control).val(1);
            }
        }
        function open_close(btn,control,div) {
            if($("#"+control).val() == 1){
                $("#"+control).val(0);
                $(btn).html('开启');
                $(btn).removeClass().addClass('open_btn');
                $("#"+div).hide('fast');
                close_set(control);
            }else{
                $("#"+control).val(1);
                $(btn).html('关闭');
                $("#"+div).show('fast');
                $(btn).removeClass().addClass('close_btn');
            }
        }
        function close_set(control) {
            var obj = new Object();
            obj.tea_id = $("#teaid").val();
            if(control == 'jfoff3'){
                obj.jfoff3 = 0;
            }else if (control == 'jfoff2'){
                obj.jfoff2 = 0;
            }else{
                obj.jfoff1 = 0;
            }
            $.post('/Museum/save',{data:obj},function(data){
                if(data.status==1){
                    console.log()
                }else{
                    console.log(data.message);
                }
            });
        }
    </script>
@endsection