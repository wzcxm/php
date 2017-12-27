@extends('Layout.layout')
@section('content')

    <div class="ui-field-contain">

        <table width="100%">
            <tr>
                <td width="10%"></td>
                <td width="30%" align="right">头像：</td>
                <td width="50%"><img src="{{empty($palyer->head_img_url)?asset('/img/ui-default.jpg'):$palyer->head_img_url}}" style="border-radius:6px;" width="40" align="absmiddle" ></td>
                <td width="10%">

                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">ID：</td>
                <td><span id="uid">{{$palyer->uid}}</span></td>
                <td>

                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">昵称：</td>
                <td>{{$palyer->nickname}}</td>
                <td>

                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">电话：</td>
                <td>{{$palyer->uphone}}</td>
                <td>

                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">剩余房卡：</td>
                <td>{{$palyer->gold}}</td>
                <td>

                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">本次冲卡：</td>
                <td><span id="roomcard">{{$roomcard}}</span></td>
                <td>

                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3"><span id="msg" style="color:red;">请仔细核对玩家信息后，再确认冲卡!</span></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2"><button class="ui-btn ui-corner-all ui-btn-b"  id="btn_save">确认充卡</button></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2"><button class="ui-btn ui-corner-all ui-btn-b"  onclick="javascript: window.location.href = '/BuyCard';">返回</button></td>
                <td></td>
            </tr>
        </table>
    </div>

    <script>
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                var payer_id = $("#uid").html();
                var card_number = $("#roomcard").html();
                $.post("/BuyCard/"+payer_id+"/"+card_number,function (data) {
                    if(comm.is_null(data.msg)){
                        //$("#msg").html(data.msg);
                        alert(data.msg);
                    }
                    else {
                        alert("充值成功！");
                        window.location.href = '/BuyCard';
                    }
                })
            })
        });
    </script>
@endsection