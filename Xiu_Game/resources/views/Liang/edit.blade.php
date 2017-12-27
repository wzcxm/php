@extends('Layout.layout')
@section('content')
    <form id="Liang_edit"  >
        {{ method_field('PUT') }}
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/Liang';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="liang">靓号：</label><input type="text" name="liang" id="liang" readonly="readonly" value="{{$liang->liang}}" >
            <label for="olduid">绑定ID：</label><input type="text" name="olduid" value="{{$liang->olduid}}" id="linkurl" required>
            <input type="hidden" id="id" value="{{$liang->id}}">
        </div>
    </form>
    <script>
        $(function () {
            $("#Liang_edit").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#Liang_edit").valid()) {
                    return;
                }
                var obj = $("#Liang_edit").serializeArray();
                $.post('/Liang/'+$("#id").val(),obj,function(data){
                    if(data.msg==1){
                        location.href="/Liang";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection