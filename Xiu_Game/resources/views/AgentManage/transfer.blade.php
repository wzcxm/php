@extends('Layout.layout')
@section('content')
    代理转移&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%">
            <tr>
                <td width="20%"></td>
                <td width="60%"><input type="text" placeholder="目标ID" id="target"   ></td>
                <td width="20%">

                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" placeholder="转移ID" id="source"   ></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><button class="ui-btn ui-corner-all ui-btn-b"  id="btn_move">转移</button></td>
                <td></td>
            </tr>
            <tr>
                <td></td><td colspan="2"><span id="msg" style="color:red;"></span></td>
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
            $("#btn_move").click(function () {
                var target = $("#target").val();
                var source = $("#source").val();
                if(!comm.is_null(target)){
                    $("#msg").html("请输入目标ID");
                    return;
                }else{$("#msg").html("");}
                if(!comm.is_null(source)){
                    $("#msg").html("请输入要转移的ID");
                    return;
                }else{$("#msg").html("");}
                $.post('/Transfer/save/'+target+'/'+source,function (data) {
                    if(data.error==""){
                        $("#target").val("");
                        $("#source").val("");
                        $("#msg").html("转移成功！");
                    } else {
                        $("#msg").html(data.error);
                    }
                })

            });
        })
    </script>
@endsection