@extends('Layout.layout')
@section('content')
    <form id="bepower_edit"  >
        {{ method_field('PUT') }}
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/UserManage';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="uid">ID：</label><input type="text" name="uid" id="uid" readonly="readonly" value="{{$user->uid}}" >
            <label for="oldjb">原有级别:</label><input type="text" name="oldjb" value="{{$user->rname}}" id="oldib" readonly="readonly">
            <label for="front_uid">上级ID:</label><input type="number" name="front_uid" value="{{$user->front_uid}}" id="front_uid" >
            <fieldset class="ui-field-contain">
            <label for="rname">晋升级别:</label>
                <select name="rname" id="rname" data-mini="true">
                    <option value="2" >客服</option>
                    <option value="3" >总代</option>
                    <option value="4" >代理</option>
                    <option value="5" >玩家</option>
                </select>
            </fieldset>
        </div>
    </form>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                var obj = $("#bepower_edit").serializeArray();
                $.post('/UserManage',obj,function(data){
                    if(data.msg==1){
                        location.href="/UserManage";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection
