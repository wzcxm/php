@extends('Layout.layout')
@section('content')
    我的群
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%" style="font-size: 14px;">
            <tr>
                <td width="20%" align="right"></td>
                <td width="70%">
                    <input type="text" placeholder="请输入群名称！" name="gname" id="gname" >
                </td>
                <td width="10%"></td>
            </tr>
            <tr>
                <td></td><td><span  style="color:red;">提示：群名称不能包含涉及金额的敏感字符，如元、块等；</span></td><td></td>
            </tr>
            <tr>
                <td ></td><td><button class="ui-btn ui-btn-icon-left ui-corner-all ui-btn-b" id="btn_save" >添加群</button></td><td></td>
            </tr>
            <tr>
                <td></td><td><span id="msg" style="color:red;"></span></td><td></td>
            </tr>
        </table>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="30%">群名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr>
                    <td>{{$item->group_name}}</td>
                    <td>
                        <a href="#" style="text-decoration: none;font-size: 14px;" onclick="javascript:window.location.href='/Group/edit/{{$item->id}}'"  >群改名</a>&nbsp;&nbsp;
                        <a href="#" style="text-decoration: none;font-size: 14px;" onclick="javascript:window.location.href='/Group/Info/{{$item->id}}'"  >群管理</a>&nbsp;&nbsp;
                        <a href="#" style="text-decoration: none;font-size: 14px;" onclick="javascript:del_group({{$item->id}});"  >群解散</a>&nbsp;&nbsp;
                        <a href="#" style="text-decoration: none;font-size: 14px;" onclick="javascript:window.location.href='/Group/record/{{$item->id}}'"  >查看战绩</a>
                        <a href="#" style="text-decoration: none;font-size: 14px;" onclick="javascript:window.location.href='/Group/QrCode/{{$item->id}}'"  >群二维码</a>
                        {{--<div data-role="controlgroup" data-type="horizontal" class="ui-mini">--}}
                            {{--<a href="#" onclick="javascript:window.location.href='/Group/edit/{{$item->id}}'"  class="ui-btn" >群改名</a>--}}
                            {{--<a href="#" onclick="javascript:window.location.href='/Group/Info/{{$item->id}}'"  class="ui-btn" >群管理</a>--}}
                            {{--<a href="#" onclick="javascript:del_group({{$item->id}});" class="ui-btn" >群解散</a>--}}
                            {{--<a href="#" onclick="javascript:window.location.href='/Group/record/{{$item->id}}'" class="ui-btn"  >查看战绩</a>--}}
                        {{--</div>--}}
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
                var gname = $("#gname").val();
                if(!comm.is_null(gname)){
                    $("#msg").html("请输入群名称");
                    return;
                }else{
                    $("#msg").html("");
                }
                $.post('/Group/save/'+gname,function (data) {
                    if(comm.is_null(data.Error)){
                        $("#msg").html(data.Error);
                    }else{
                        window.location.reload();
                    }
                })
            });

        })
        function del_group(id) {
            if(confirm("您确定解散该群吗？")) {
                $.post('/Group/del/' + id, function (data) {
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