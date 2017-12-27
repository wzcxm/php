@extends('Layout.layout')
@section('content')
    <form id="menu_edit"  >
        {{ method_field('PUT') }}
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/Menus';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="name">菜单名称：</label><input type="text" name="name" id="name" value="{{$menu->name}}" required>
            <label for="linkurl">菜单地址：</label><input type="text" name="linkurl" value="{{$menu->linkurl}}" id="linkurl" required>
            <label for="remarks">菜单顺序:</label><input type="number" name="remarks" value="{{$menu->remarks}}" id="remarks"  required>
            <input type="hidden" id="menuid" value="{{$menu->menuid}}">
        </div>
    </form>
    <script>
        $(function () {
            $("#menu_edit").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#menu_edit").valid()) {
                    return;
                }
                var obj = $("#menu_edit").serializeArray();

                $.post('/Menus/'+$("#menuid").val(),obj,function(data){
                    if(data.msg==1){
                        location.href="/Menus";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection