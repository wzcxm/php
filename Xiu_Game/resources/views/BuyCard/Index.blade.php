@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>游戏充值</h3>
    </header>
    <div style="width: 100%;padding-top: 10px;">
        <div style="margin-bottom:10px;">
            <table width="100%" style="border-collapse:   separate;   border-spacing:   10px; " >
                <tr >
                    <td width="30%" align="right">我的余额：</td>
                    <td>
                        <span style="border-radius: 4px;background-color:#c35858;color:white;font-weight: 400;"><b id="gold">{{$gold}}</b></span>
                    </td>
                </tr>
            </table>
        </div>
        <hr>
        <div style="margin-bottom:10px">
            <table width="100%" style="border-collapse:   separate;   border-spacing:   10px;">
                <tr >
                    <td width="30%" align="right">玩家ID：</td>
                    <td>
                        <input class="easyui-numberbox" id="payer_id" style="width: 120px;" >&nbsp;
                        <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'" id="btn_search" style="width: 80px;">查询玩家</a>
                    </td>
                </tr>
                <tr>
                    <td align="right">ID：</td>
                    <td><img  id="head_url" src="" style="border-radius:6px;" width="30" align="absmiddle" ><span  id="uid" ></span></td>
                </tr>
                <tr>
                    <td align="right">昵称：</td>
                    <td><span id="player_nick"></span></td>
                </tr>
                <tr>
                    <td align="right">余额：</td>
                    <td><span id="roomcard"></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><span style="color:red;">请仔细核对玩家信息后，再确认充值!</span></td>
                </tr>
            </table>
            <hr>
            <table width="100%" style="border-collapse:   separate;   border-spacing:   10px;">
                <tr>
                    <td width="30%" align="right">充值数量：</td>
                    <td><input class="easyui-numberbox"  id="card_number" style="width: 120px;"  ></td>
                </tr>
                <tr>
                    <td></td>
                    <td><a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" id="btn_buy" style="width:120px">确认充值</a></td>
                </tr>
                <tr>
                    <td></td><td ><span id="error" style="color:red;"></span></td>
                </tr>
            </table>
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
                        $("#roomcard").html("");
                        $("#uid").html("");
                    } else {
                        var player = data.user;
                        $("#head_url").attr('src',player['head_img_url']);
                        $("#player_nick").html(player['nickname']);
                        $("#roomcard").html(player['roomcard']);
                        $("#uid").html(player['uid']);
                    }
                });
            });
            $("#btn_buy").click(function () {
                $('#btn_buy').prop('disabled',true);
                var payer_id=$("#uid").html();
                var card_number=$("#card_number").val();
                if(!comm.is_null(payer_id)){
                    $.alert("请点击查询玩家信息，并仔细核对玩家信息后充值！");
                    $('#btn_buy').prop('disabled',false);
                    return;
                }
                if(!comm.is_null(card_number)){
                    $.alert("请输入充值数量！");
                    $('#btn_buy').prop('disabled',false);
                    return;
                }
                $.post("/BuyCard/"+payer_id+"/"+card_number,function (data) {
                    if(comm.is_null(data.msg)){
                        $.alert(data.msg);
                    }
                    else {
                        $.alert('充值成功！',function () {
                            window.location.reload();
                        });
                    }
                    $('#btn_buy').prop('disabled',false);
                })
            });
        });

    </script>
@endsection