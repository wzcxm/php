@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <table id="tab_grid" ></table>
@endsection
@section('easyui_script')
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#tab_grid").datagrid({
                title:'角色设置',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Role/data',
                idField:'roleid',
                toolbar: [{
                    iconCls: 'icon-add',
                    text:'添加',
                    handler: function(){
                        window.location.href='/Role/add';
                    }
                },'-',{
                    iconCls: 'icon-edit',
                    text:'修改',
                    handler: function(){
                        var rows =  $("#tab_grid").datagrid('getChecked');
                        if(rows.length<=0){
                            $.alert('请选择要修改的记录','提示');
                            return;
                        }
                        window.location.href='/Role/add/'+rows[0].roleid;
                    }
                },'-',{
                    iconCls: 'icon-remove',
                    text:'删除',
                    handler: function(){
                        //调通用删除方法
                        comm.Generic_Del('/Role/del/','tab_grid','roleid');
                    }
                },'-',{
                    iconCls: 'icon-reload',
                    text:'刷新',
                    handler: function(){
                        comm.Reload('tab_grid');
                    }
                }],
                columns:[[
                    {field:'ck',checkbox:true},
                    {field:'roleid',title:'角色编码',width:60,hidden:true},
                    {field:'rname',title:'角色名称',width:80},
                    {field:'remarks',title:'角色说明',width:150}
                ]]
            });
        })
    </script>
@endsection