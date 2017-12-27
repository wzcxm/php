@extends('Layout.layout')
@section('content')
    <form id="set_add"  >
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: history.go(-1);" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="player_id">玩家ID：</label><input type="text" name="player_id"  id="player_id" required>
            <fieldset class="ui-field-contain">
                <label for="gtype">游戏类型：</label>
                <select name="gtype" id="gtype" data-mini="true">
                    <option value="1">跑得快</option>
                    <option value="2">宁乡麻将</option>
                    <option value="3">红中麻将</option>
                </select>
            </fieldset>
            <label for="upper_limit">分数上限:</label><input type="text" name="upper_limit"  id="upper_limit" required>
            <label for="lower_limit">分数下限:</label><input type="text" name="lower_limit"  id="lower_limit" required>
            <span id="error" style="color:red;"></span>
        </div>
    </form>
    <script>
        $(function () {
            $("#set_add").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#set_add").valid()) {
                    return;
                }
                var obj = $("#set_add").serializeArray();
                $.post('/White/SetSave',obj,function(data){
                    if(data.msg==1){
                        $("#error").html('');
                        location.href="/White/Set";
                    }else{
                        $("#error").html(data.msg);
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection
