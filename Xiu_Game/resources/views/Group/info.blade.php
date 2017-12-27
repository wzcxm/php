@extends('Layout.layout')
@section('content')
    群名称:{{$Group->group_name}}
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%" style="font-size: 14px;">
            <tr>
                <td width="20%" align="right"></td>
                <td width="70%"><input type="number" placeholder="请输入玩家ID！" name="player" id="player" ></td>
                <td width="10%"><input type="hidden" id="gid" value="{{$Group->id}}"></td>
            </tr>
            <tr>
                <td ></td>
                <td><button class="ui-btn ui-btn-icon-left ui-corner-all ui-btn-b" id="btn_save" >添加玩家</button></td>
                <td></td>
            </tr>
            <tr>
                <td ></td>
                <td> <button class="ui-btn ui-btn-icon-left ui-corner-all ui-btn-b" onclick="javascript: window.location.href = '/Group';" >返回</button></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><span id="msg" style="color:red;"></span></td>
                <td></td>
            </tr>
        </table>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="25%">玩家ID</th>
                <th width="35%">昵称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr>
                    <td>{{$item->player_id}}</td>
                    <td>{{$item->nickname}}</td>
                    <td>
                        <button class="ui-btn ui-corner-all ui-mini" onclick="javascript:del_player({{$item->id}});" >删除</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
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
                var player = $("#player").val();
                var gid = $("#gid").val();
                if(!comm.is_null(player)){
                    $("#msg").html("请输入玩家ID");
                    return;
                }else{
                    $("#msg").html("");
                }
                $.post('/Group/InfoSave/'+gid+'/'+player,function (data) {
                    if(comm.is_null(data.Error)){
                        $("#msg").html(data.Error);
                    }else{
                        window.location.reload();
                    }
                })
            });

        })
        function del_player(id) {
            if(confirm("您确定删除该玩家吗？")) {
                $.post('/Group/InfoDel/' + id, function (data) {
                    if (comm.is_null(data.Error)) {
                        alert(data.Error);
                    } else {
                        window.location.reload();
                    }
                });
            }
        }
    </script>
@endsection