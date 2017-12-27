@extends('Layout.layout')
@section('content')
    <form id="Liang_create"  >
        <div id="Alert"></div>
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/Liang';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="liang">靓号：</label><input type="text" name="liang" id="liang" required>
        </div>
    </form>
    <script>
        $(function () {
            $("#Liang_create").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#Liang_create").valid()) {
                    return;
                }
                var obj = $("#Liang_create").serializeArray();
                $.post('/Liang',obj,function(data){
                    if(data.msg==1){
                        location.href="/Liang";
                    }else if(data.msg==2){
                        comm.Alert('Error','该靓号已存在！')
                    }
                    else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection