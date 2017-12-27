@extends('Layout.layout')
@section('content')
    <form id="role_edit"  >
        {{ method_field('PUT') }}
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/Role';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="name">角色名称：</label><input type="text" name="rname" id="rname" value="{{$role->rname}}" required>
            <label for="remarks">备注:</label><input type="text" name="remarks" value="{{$role->remarks}}" id="remarks" >
            <input type="hidden" id="roleid" value="{{$role->roleid}}">
        </div>
    </form>
    <script>
        $(function () {
            $("#role_edit").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#role_edit").valid()) {
                    return;
                }
                var obj = $("#role_edit").serializeArray();

                $.post('/Role/'+$("#roleid").val(),obj,function(data){
                    if(data.msg==1){
                        location.href="/Role";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection
