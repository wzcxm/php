@extends('Layout.WeUiLayout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/museumset.css')}}?v=201804255">
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
            <div style="font-size: 0.7rem;">
                <div style="font-size: 0.8rem;font-weight: 600;">一号厅规则设置：</div>
                <div>
                    1、结算1分等于
                    <input type="number" id="jifen1" class="inp_sty" value="{{empty($tea->jifen1)?0:$tea->jifen1}}">
                    积分</div>
                <div>
                    2、积分负
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
                    3、大赢家获得积分，不足
                    <input type="number" id="bzfen1" class="inp_sty" value="{{empty($tea->bzfen1)?0:$tea->bzfen1}}">
                    收
                    <input type="number" id="mincf1" class="inp_sty" value="{{empty($tea->mincf1)?0:$tea->mincf1}}">
                    ，大于等于收
                    <input type="number" id="huilv1" class="inp_sty" value="{{empty($tea->huilv1)?0:$tea->huilv1}}">
                    。是否启用
                    @if(!empty($tea) && !empty($tea->jfoff1) && $tea->jfoff1==1)
                        <input type="checkbox" id = "jfoff1" class="weui-agree__checkbox " checked="checked" onclick="ckb_setvalue('jfoff_one')">
                        <input type="hidden" id="jfoff_one" value="1">
                    @else
                        <input type="checkbox" id = "jfoff1" class="weui-agree__checkbox" onclick="ckb_setvalue('jfoff_one')">
                        <input type="hidden" id="jfoff_one" value="0">
                    @endif
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="font-size: 0.7rem;">
                <div style="font-size: 0.8rem;font-weight: 600;">二号厅规则设置：</div>
                <div>
                    1、结算1分等于
                    <input type="number" id="jifen2" class="inp_sty" value="{{empty($tea->jifen2)?0:$tea->jifen2}}">
                    积分</div>
                <div>
                    2、积分负
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
                    3、大赢家获得积分，不足
                    <input type="number" id="bzfen2" class="inp_sty" value="{{empty($tea->bzfen2)?0:$tea->bzfen2}}">
                    收
                    <input type="number" id="mincf2" class="inp_sty" value="{{empty($tea->mincf2)?0:$tea->mincf2}}">
                    ，大于等于收
                    <input type="number" id="huilv2" class="inp_sty" value="{{empty($tea->huilv2)?0:$tea->huilv2}}">
                    。是否启用
                    @if(!empty($tea) && !empty($tea->jfoff2) && $tea->jfoff2==1)
                        <input type="checkbox" id = "jfoff2" class="weui-agree__checkbox " checked="checked" onclick="ckb_setvalue('jfoff_two')">
                        <input type="hidden" id="jfoff_two" value="1">
                    @else
                        <input type="checkbox" id = "jfoff2" class="weui-agree__checkbox" onclick="ckb_setvalue('jfoff_two')">
                        <input type="hidden" id="jfoff_two" value="0">
                    @endif
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="font-size: 0.7rem;">
                <div style="font-size: 0.8rem;font-weight: 600;">三号厅规则设置：</div>
                <div>
                    1、结算1分等于
                    <input type="number" id="jifen3" class="inp_sty" value="{{empty($tea->jifen3)?0:$tea->jifen3}}">
                    积分</div>
                <div>
                    2、积分负
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
                    3、大赢家获得积分，不足
                    <input type="number" id="bzfen3" class="inp_sty" value="{{empty($tea->bzfen3)?0:$tea->bzfen3}}">
                    收
                    <input type="number" id="mincf3" class="inp_sty" value="{{empty($tea->mincf3)?0:$tea->mincf3}}">
                    ，大于等于收
                    <input type="number" id="huilv3" class="inp_sty" value="{{empty($tea->huilv3)?0:$tea->huilv3}}">
                    。是否启用
                    @if(!empty($tea) && !empty($tea->jfoff3) && $tea->jfoff3==1)
                        <input type="checkbox" id = "jfoff3" class="weui-agree__checkbox " checked="checked" onclick="ckb_setvalue('jfoff_three')">
                        <input type="hidden" id="jfoff_three" value="1">
                    @else
                        <input type="checkbox" id = "jfoff3" class="weui-agree__checkbox" onclick="ckb_setvalue('jfoff_three')">
                        <input type="hidden" id="jfoff_three" value="0">
                    @endif
                </div>
            </div>
        </div>
        <div class="weui-cell" style="color: red;font-size: 0.7rem;">
            &nbsp;&nbsp;&nbsp;&nbsp;提示：大赢家不止一位时，茶水费除以大赢家人数，向上取整，例如：5积分茶水费，大赢家人数2，则每人扣3积分
        </div>
        <div class="weui-cell">
            <button class="save_bg" style="width: 80%;margin-left: 10%;"  id="btn_save">保存</button>
            {{--<button class="cancel_bg" style="width: 80%;margin-left: 10%;" onclick="javascript:window.location.href='/Museum'">返回</button>--}}
        </div>
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
                obj.tea_id = $("#teaid").val();
                obj.score1 = $("#score1").val();
                obj.off1 = $("#off_one").val();
                obj.jifen1 = $("#jifen1").val();
                obj.huilv1 = $("#huilv1").val();
                obj.bzfen1 = $("#bzfen1").val();
                obj.mincf1 = $("#mincf1").val();
                obj.jfoff1 = $("#jfoff_one").val();
                obj.score2 = $("#score2").val();
                obj.off2 = $("#off_two").val();
                obj.jifen2 = $("#jifen2").val();
                obj.huilv2 = $("#huilv2").val();
                obj.bzfen2 = $("#bzfen2").val();
                obj.mincf2 = $("#mincf2").val();
                obj.jfoff2 = $("#jfoff_two").val();
                obj.score3 = $("#score3").val();
                obj.off3 = $("#off_three").val();
                obj.jifen3 = $("#jifen3").val();
                obj.huilv3 = $("#huilv3").val();
                obj.bzfen3 = $("#bzfen3").val();
                obj.mincf3 = $("#mincf3").val();
                obj.jfoff3 = $("#jfoff_three").val();
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
    </script>
@endsection