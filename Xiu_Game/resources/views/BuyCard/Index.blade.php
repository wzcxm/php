@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>钻石赠送</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">我的钻石：</label>
            </div>
            <div class="weui-cell__bd">
                <span style="border-radius: 4px;background-color:#c35858;color:white;font-weight: 400;"><b id="card">{{$card}}</b></span>
            </div>
            <div class="weui-cell__hd">
                <label class="weui-label">我的金币：</label>
            </div>
            <div class="weui-cell__bd">
                <span style="border-radius: 4px;background-color:#c35858;color:white;font-weight: 400;"><b id="gold">{{$gold}}</b></span>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">玩家ID：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number" name="payer_id" id="payer_id" >

            </div>
            <div class="weui-cell__hd">
                <div class="button_sp_area">
                    <a class="weui-btn weui_btn_mini weui-btn_primary" href="javascript:"  id="btn_search" >查询</a>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <table width="100%" style="border-collapse:   separate;   border-spacing:   10px;">
                <tr>
                    <td width="20%" align="right">昵称：</td>
                    <td>
                        <img  id="head_url" src="" style="border-radius:6px;" width="30" align="absmiddle" >
                        <span id="player_nick"></span>
                        <input type="hidden" id="uid">
                    </td>
                </tr>
                <tr>
                    <td align="right">钻石：</td>
                    <td><span id="u_card"></span></td>
                </tr>
                <tr>
                    <td align="right">金币：</td>
                    <td><span id="u_gold"></span></td>
                </tr>
                <tr>
                    <td colspan="2"><span style="color:red;">请仔细核对玩家信息后，再确认赠送!</span></td>
                </tr>
            </table>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">赠送类型：</label>
            </div>
            <div class="weui-cell__bd">
                <select class="weui-select"  id="sel_type">
                    <option  selected="selected" value="1">钻石</option>
                    <option  value="2">金币</option>
                </select>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">赠送数量：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number" name="card_number" id="card_number" >
            </div>
        </div>
        <div class="weui-btn-area">
            <button class="weui-btn weui-btn_primary"  id="btn_buy">确认赠送</button>
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
                    $.alert("请输入充值数量！");
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