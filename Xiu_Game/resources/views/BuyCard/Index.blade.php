@extends('Layout.EasyUiLayOut')
@section('easyui_style')
    <link rel="stylesheet" href="{{asset('css/gift.css')}}?v=201804161">
@endsection
@section('easyui_content')
    <div  class="weui-flex gift_head_bg" >
        <div style="margin-top: 8%;"><i class="fa fa-diamond">&nbsp;&nbsp;钻石赠送</i></div>
    </div>
    <div  class="weui-flex zs_jd_bg" >
        <div class="weui-flex__item" style="text-align: center;">
            我的钻石：{{$card}}
        </div>
        <div class="weui-flex__item" style="text-align: center;">
            我的金豆：{{$gold}}
        </div>
    </div>
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                玩家ID&nbsp;&nbsp;&nbsp;&nbsp;
                <input class="input_number" type="number"  id="payer_id" />
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="search_bg" id="btn_search">查询</button>
                {{--<img src="img/gift/search.png">--}}
            </div>
        </div>
        <div class="weui-cell">
            <table width="100%" style="border-collapse:   separate;   border-spacing:   10px;font-size: 0.8rem;">
                <tr>
                    <td width="45%" align="right">昵称：</td>
                    <td>
                        <img  id="head_url" src="" style="border-radius:6px;" width="30" align="absmiddle" >
                        <span style="color: #545454" id="player_nick"></span>
                        <input type="hidden" id="uid">
                    </td>
                </tr>
                <tr>
                    <td align="right">钻石：</td>
                    <td><span  style="color: #545454" id="u_card"></span></td>
                </tr>
                <tr>
                    <td align="right">金豆：</td>
                    <td><span  style="color: #545454" id="u_gold"></span></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><span style="color:#ee3723;">请仔细核对玩家信息后，再确认赠送!</span></td>
                </tr>
            </table>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 28%;text-align: right;">
                    赠送类型
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    钻石<input id="sel_type" value="1" type="hidden">
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 28%;text-align: right;">
                    赠送数量
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="input_number" type="number" id="card_number" style="width: 50%;">
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <button class="search_bg" style="width: 80%;margin-left: 10%;"  id="btn_buy">确认赠送</button>
        </div>
        <div style="width:100%;text-align: center;font-size: 0.8rem;">
            <a href="javascript:window.location.href = '/BuyCard/list'" style="color: #2dbaa7;">赠送记录</a>
        </div>
    </div>
@endsection
@section('easyui_script')
    <script>
        $(function () {
//            $('#payer_id').autocompleter({
//                source: '/autocompleter'
//
//            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_search").click(function () {
                var payer_id = $("#payer_id").val();
                if(!comm.is_null(payer_id)){
                    $.alert("请输入玩家ID");
                    return;
                }
                $.get('/BuyCard/Search/'+payer_id,function (data) {
                    if(comm.is_null(data.msg)){
                        $.alert(data.msg);
                        $("#head_url").attr('src',"");
                        $("#player_nick").html("");
                        $("#u_card").html("");
                        $("#u_gold").html("");
                        $("#uid").val("");
                    } else {
                        var player = data.user;
                        $("#head_url").attr('src',player['head_img_url']);
                        $("#player_nick").html(player['nickname']);
                        $("#u_card").html(player['roomcard']);
                        $("#u_gold").html(player['gold']);
                        $("#uid").val(player['uid']);
                    }
                });
            });
            $("#btn_buy").click(function () {
                $('#btn_buy').prop('disabled',true);
                var R = new Object();
                R.payer_id = $("#uid").val();
                R.sel_type = $('#sel_type').val();
                R.card_number = $('#card_number').val();
                if(!comm.is_null(R.payer_id)){
                    $.alert("请查询玩家信息，并仔细核对玩家信息后赠送！");
                    $('#btn_buy').prop('disabled',false);
                    return;
                }
                if(!comm.is_null(R.card_number)){
                    $.alert("请输入赠送数量！");
                    $('#btn_buy').prop('disabled',false);
                    return;
                }
                $.post("/BuyCard/Gift",{data:R},function (reslut) {
                    if(comm.is_null(reslut.msg)){
                        $.alert(reslut.msg);
                    }
                    else {
                        $.alert('赠送成功！',function () {
                            window.location.reload();
                        });
                    }
                    $('#btn_buy').prop('disabled',false);
                })
            });
        });

    </script>
@endsection