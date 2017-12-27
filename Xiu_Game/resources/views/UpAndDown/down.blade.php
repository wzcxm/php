@extends('Layout.layout')
@section('content')
    <form id="down_edit"  >
        <button type="button"  data-inline="true" id="btn_save" >
            下分
        </button>
        <button type="button" data-inline="true" onclick="javascript: history.go(-1);" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="payer_id">玩家：{{$user->uid}}</label>
            <label for="now_scores">当前分数:{{$user->scores}}</label>
            <label for="scores">下分数:</label><input type="text" name="scores"  id="scores" required>
            <input type="hidden" id="type" name="type" value="2">
            <input type="hidden" id="uid" name="uid" value="{{$user->uid}}">
        </div>
    </form>
    <script>
        $(function () {
            $("#down_edit").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#down_edit").valid()) {
                    return;
                }
                var obj = $("input").serializeArray();
                $.post('/UpAndDown/Save',obj,function(data){
                    if(data.msg==1){
                        location.href="/UpAndDown";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection
