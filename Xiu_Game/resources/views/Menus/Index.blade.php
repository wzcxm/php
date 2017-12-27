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
                title:'菜单设置',
                singleSelect:true,
                border:false,
                fit:true,
 //               fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Menus/data',
                idField:'menuid',
                toolbar: [{
                    iconCls: 'icon-add',
                    text:'添加',
                    handler: function(){
                        window.location.href='/Menus/add';
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
                        window.location.href='/Menus/add/'+rows[0].menuid;
                    }
                },'-',{
                    iconCls: 'icon-remove',
                    text:'删除',
                    handler: function(){
                        //调通用删除方法
                        comm.Generic_Del('/Menus/del/','tab_grid','menuid');
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
                    {field:'menuid',title:'菜单编码',width:60,hidden:true},
                    {field:'name',title:'菜单名称',width:100},
                    {field:'linkurl',title:'菜单地址',width:100},
                    {field:'remarks',title:'排序',width:60},
                    {field:'icon',title:'图标',width:80}
                ]]
            });
        })
    </script>
@endsection