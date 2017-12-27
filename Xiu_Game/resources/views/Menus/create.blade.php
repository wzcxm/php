@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>编辑菜单</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">菜单名称：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="name" id="name" value="{{empty($menu)?"":$menu->name}}">
                <input type="hidden" id="menuid" value="{{empty($menu)?"":$menu->menuid}}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">菜单地址：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="linkurl" id="linkurl" value="{{empty($menu)?"":$menu->linkurl}}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">排序：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number" name="remarks" id="remarks" value="{{empty($menu)?"":$menu->remarks}}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">图标：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text" name="icon" id="icon" value="{{empty($menu)?"":$menu->icon}}">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">保存</a>
        <a href="/Menus" class="weui-btn weui-btn_default weui-btn_loading">返回</a>
    </div>
@endsection
@section('script')
<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#btn_save").click(function () {
            var R = new Object();
            R.menuid = $('#menuid').val();
            R.name = $('#name').val();
            R.linkurl = $('#linkurl').val();
            R.remarks = $('#remarks').val();
            R.icon = $('#icon').val();
            $.post('/Menus/save',{data:R},function(data){
                if(data.msg==1){
                    location.href="/Menus";
                }else{
                    $.alert(data.msg);
                }
            })
        })
    });
</script>
@endsection