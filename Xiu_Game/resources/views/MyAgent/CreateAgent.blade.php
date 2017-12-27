@extends('Layout.layout')
@section('content')
    <form id="Agent_create"  >
        <div id="Alert"></div>
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/MyAgent';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="uid">推荐人ID：</label><input type="number" name="front" id="front" required>
            <label for="uid">游戏ID：</label><input type="number" name="uid" id="uid" required>
            <label for="uphone">手机号码：</label><input type="number" name="uphone" id="uphone" maxlength="11" required>
            <label for="wechat">微信号：</label><input type="text" name="wechat" id="wechat" >
            <span id="msg" style="color:red;"></span>
        </div>
    </form>
    <script>
        $(function () {
            $("#Agent_create").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#Agent_create").valid()) {
                    return;
                }
                var obj = $("#Agent_create").serializeArray();
                $.post('/Replace',obj,function(data){
                    if(data.msg==1){
                        window.location.reload();
                    }
                    else{
                        $("#msg").html(data.msg);
                        //alert(data.msg);
                        // location.href="/Replace";
                    }
                })
            })
        });
    </script>
@endsection