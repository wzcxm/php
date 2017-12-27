@extends('Layout.layout')
@section('content')
    <form id="set_edit"  >
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: history.go(-1);" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="payer_id">玩家ID：{{$user->uid}}</label>
            <fieldset class="ui-field-contain">
                <label for="gtype">游戏类型：</label>
                <select name="gtype" id="gtype" data-mini="true">
                    <option value="1" {{$user->g_type==1?'selected':''}}>宁乡麻将</option>
                </select>
            </fieldset>
            <label for="upper_limit">分数上限:</label><input type="text" name="upper_limit" value="{{$user->upper_limit}}" id="upper_limit" required>
            <label for="lower_limit">分数下限:</label><input type="text" name="lower_limit" value="{{$user->lower_limit}}" id="lower_limit" required>
            <input type="hidden" id="id" name="id" value="{{$user->id}}">
            <input type="hidden" id="uid" name="uid" value="{{$user->uid}}">
        </div>
    </form>
    <script>
        $(function () {
            $("#set_edit").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#set_edit").valid()) {
                    return;
                }
                var obj = $("#set_edit").serializeArray();
                $.post('/White/SetEdit',obj,function(data){
                    if(data.msg==1){
                        location.href="/White/Set";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection
