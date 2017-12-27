@extends('Layout.layout')
@section('content')
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/Group';" >
            返回
        </button>
        <hr>
        <table width="100%" style="font-size: 14px;">
            <tr>
                <td width="20%" align="right">群原名：</td>
                <td width="70%">{{$Group->group_name}}</td>
                <td width="10%"><input type="hidden" id="gid" value="{{$Group->id}}"></td>
            </tr>
            <tr>
                <td align="right">修改为：</td>
                <td><input type="text" placeholder="请输入新群名！" name="group_name" id="group_name" ></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><span id="msg" style="color:red;"></span></td>
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
                var newname = $("#group_name").val();
                var gid = $("#gid").val();
                if(!comm.is_null(newname)){
                    $("#msg").html("请输入新群名！");
                    return;
                }else{
                    $("#msg").html("");
                }
                $.post('/Group/editSave/'+gid+'/'+newname,function (data) {
                    if(comm.is_null(data.Error)){
                        $("#msg").html(data.Error);
                    }else{
                        window.location.href='/Group';
                    }
                })
            });

        })

    </script>
@endsection