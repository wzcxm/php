@extends('Layout.WeUiLayout')
<style>
    .inp_sty{
        font-weight: lighter;
        font-size: 0.8rem;
        border:2px solid #c1c1c1;
        outline:none;
        cursor: pointer;
        padding: 5px 10px 5px 10px;
        border-radius:5px;
        width: 80%;
        vertical-align: unset;
    }
</style>
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>牌馆规则设置</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <table width="100%" style="border-spacing:4px;">
            <tr>
                <td colspan="2" align="center">
                    <h4>1号厅规则设置</h4>
                    @if(!empty($tea) && !empty($tea->tea_id))
                        <input type="hidden" id="teaid" value="{{$tea->tea_id}}">
                    @else
                        <input type="hidden" id="teaid" >
                    @endif
                </td>
            </tr>
            <tr>
                <td width="35%" align="right">最低积分：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->score1))
                        <input type="number" id="score1" class="inp_sty" value="{{$tea->score1}}">
                    @else
                        <input type="number" id="score1" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">是否启用：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->off1) && $tea->off1==1)
                        <input type="checkbox" id = "off1" class="weui-switch" checked="checked">
                        <input type="hidden" id="off_one" value="{{$tea->off1}}">
                    @else
                        <input type="checkbox" id = "off1" class="weui-switch">
                        <input type="hidden" id="off_one" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">基础积分：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->jifen1))
                        <input type="number" id="jifen1" class="inp_sty" value="{{$tea->jifen1}}">
                    @else
                        <input type="number" id="jifen1" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">茶水费：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->huilv1))
                        <input type="number" id="huilv1" class="inp_sty" value="{{$tea->huilv1}}">
                    @else
                        <input type="number" id="huilv1" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><h4>2号厅规则设置</h4></td>
            </tr>
            <tr>
                <td align="right">最低积分：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->score2))
                        <input type="number" id="score2" class="inp_sty" value="{{$tea->score2}}">
                    @else
                        <input type="number" id="score2" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">是否启用：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->off2) && $tea->off2==1)
                        <input type="checkbox" id = "off2" class="weui-switch" checked="checked">
                        <input type="hidden" id="off_two" value="{{$tea->off2}}">
                    @else
                        <input type="checkbox" id = "off2" class="weui-switch">
                        <input type="hidden" id="off_two" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">基础积分：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->jifen2))
                        <input type="number" id="jifen2" class="inp_sty" value="{{$tea->jifen2}}">
                    @else
                        <input type="number" id="jifen2" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">茶水费：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->huilv2))
                        <input type="number" id="huilv2" class="inp_sty" value="{{$tea->huilv2}}">
                    @else
                        <input type="number" id="huilv2" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><h4>3号厅规则设置</h4></td>
            </tr>
            <tr>
                <td align="right">最低积分：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->score3))
                        <input type="number" id="score3" class="inp_sty" value="{{$tea->score3}}">
                    @else
                        <input type="number" id="score3" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">是否启用：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->off3) && $tea->off3==1)
                        <input type="checkbox" id = "off3" class="weui-switch" checked="checked">
                        <input type="hidden" id="off_three" value="{{$tea->off3}}">
                    @else
                        <input type="checkbox" id = "off3" class="weui-switch">
                        <input type="hidden" id="off_three" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">基础积分：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->jifen3))
                        <input type="number" id="jifen3" class="inp_sty" value="{{$tea->jifen3}}">
                    @else
                        <input type="number" id="jifen3" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">茶水费：</td>
                <td>
                    @if(!empty($tea) && !empty($tea->huilv3))
                        <input type="number" id="huilv3" class="inp_sty" value="{{$tea->huilv3}}">
                    @else
                        <input type="number" id="huilv3" class="inp_sty" value="0">
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="weui-btn-area" >
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">保存</a>
        <a href="/Museum" class="weui-btn weui-btn_default weui-btn_loading">返回</a>
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
            $("#off1").bind('click',function () {
               if($("#off_one").val() == 1){
                   $("#off_one").val(0);
               }else{
                   $("#off_one").val(1);
               }
            });
            $("#off2").bind('click',function () {
                if($("#off_two").val() == 1){
                    $("#off_two").val(0);
                }else{
                    $("#off_two").val(1);
                }
            });
            $("#off3").bind('click',function () {
                if($("#off_three").val() == 1){
                    $("#off_three").val(0);
                }else{
                    $("#off_three").val(1);
                }
            });

            $("#btn_save").click(function () {
                var obj = new Object();
                obj.tea_id = $("#teaid").val();
                obj.score1 = $("#score1").val();
                obj.off1 = $("#off_one").val();
                obj.jifen1 = $("#jifen1").val();
                obj.huilv1 = $("#huilv1").val();
                obj.score2 = $("#score2").val();
                obj.off2 = $("#off_two").val();
                obj.jifen2 = $("#jifen2").val();
                obj.huilv2 = $("#huilv2").val();
                obj.score3 = $("#score3").val();
                obj.off3 = $("#off_three").val();
                obj.jifen3 = $("#jifen3").val();
                obj.huilv3 = $("#huilv3").val();
                $.post('/Museum/save',{data:obj},function(data){
                    if(data.status==1){
                        //location.href="/Role_Menu";
                        $.alert('保存成功！')
                    }else{
                        $.alert(data.message);
                    }
                });
            });
        });
    </script>
@endsection