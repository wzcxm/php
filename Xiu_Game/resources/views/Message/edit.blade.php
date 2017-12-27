@extends('Layout.layout')
@section('content')
    <form id="Message_edit"  >
        {{ method_field('PUT') }}
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/Message';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <fieldset class="ui-field-contain">
                <label for="mtype">信息类型：</label>
                <select name="mtype" id="mtype" data-mini="true">
                    <option value="0" {{$message->mtype==0?'selected':''}}>--请选择--</option>
                    <option value="1" {{$message->mtype==1?'selected':''}}>跑马灯</option>
                    <option value="2" {{$message->mtype==2?'selected':''}}>活动公告</option>
                    <option value="3" {{$message->mtype==3?'selected':''}}>游戏规则</option>
                    <option value="4" {{$message->mtype==4?'selected':''}}>客服联系方式</option>
                    <option value="5" {{$message->mtype==5?'selected':''}}>紧急通知</option>
                </select>
                <span style="color: red;" id="mtype_msg"></span>
            </fieldset>
            <fieldset class="ui-field-contain">
                <label for="mgametype">游戏类型：</label>
                <select name="mgametype" id="mgametype" data-mini="true">
                    <option value="0" {{$message->mgametype==0?'selected':''}}>--请选择--</option>
                    <option value="1" {{$message->mgametype==1?'selected':''}}>红中麻将</option>
                    <option value="2" {{$message->mgametype==2?'selected':''}}>长沙麻将</option>
                    <option value="3" {{$message->mgametype==3?'selected':''}}>跑得快</option>
                    <option value="4" {{$message->mgametype==4?'selected':''}}>宁乡麻将</option>
                </select>
            </fieldset>
            <label for="mcontent">信息内容:</label>
            <textarea name="mcontent" style="height: 50px;" id="mcontent" required>
                {{$message->mcontent}}
            </textarea>
            <input type="hidden" id="msgid" value="{{$message->msgid}}">
        </div>
    </form>
    <script>
        $(function () {
            $("#Message_edit").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#Message_edit").valid()) {
                    return;
                }
                if($("#mtype option:selected").val()=='0'){
                    $("#mtype_msg").html("请选择信息类型");
                    return;
                }
                else{
                    $("#mtype_msg").html("");
                }
                var obj = $("#Message_edit").serializeArray();
                $.post('/Message/'+$("#msgid").val(),obj,function(data){
                    if(data.msg==1){
                        location.href="/Message";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection