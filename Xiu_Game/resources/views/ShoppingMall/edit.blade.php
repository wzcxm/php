@extends('Layout.layout')
@section('content')
    <form id="spml_edit"  >
        {{ method_field('PUT') }}
        <button type="button"  data-inline="true" id="btn_save" >
            保存
        </button>
        <button type="button" data-inline="true" onclick="javascript: window.location.href = '/ShoppingMall';" >
            返回
        </button>
        <hr>
        <div class="ui-field-contain">
            <label for="scommodity">商品名称：</label><input type="text" name="scommodity" id="scommodity" value="{{$spml->scommodity}}" required>
            <label for="sprice">价格:</label><input type="number" name="sprice" value="{{$spml->sprice}}" id="sprice" required>
            <label for="snumber">数量:</label><input type="number" name="snumber" value="{{$spml->snumber}}" id="snumber" required>
            <label for="sgive">赠送数量:</label><input type="number" name="sgive" value="{{$spml->sgive}}" id="sgive" required>
            <label for="sremarks">备注:</label><input type="text" name="sremarks" value="{{$spml->sremarks}}" id="sremarks" >
            <input type="hidden" id="sid" value="{{$spml->sid}}">
        </div>
    </form>
    <script>
        $(function () {
            $("#spml_edit").validate();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                if (! $("#spml_edit").valid()) {
                    return;
                }
                var obj = $("#spml_edit").serializeArray();

                $.post('/ShoppingMall/'+$("#sid").val(),obj,function(data){
                    if(data.msg==1){
                        location.href="/ShoppingMall";
                    }else{
                        //alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection
