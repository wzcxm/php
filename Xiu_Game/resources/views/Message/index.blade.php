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
                title:'信息发布',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Message/data',
                idField:'msgid',
                toolbar: [{
                    iconCls: 'icon-add',
                    text:'添加',
                    handler: function(){
                        window.location.href='/Message/add';
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
                        window.location.href='/Message/add/'+rows[0].msgid;
                    }
                },'-',{
                    iconCls: 'icon-remove',
                    text:'删除',
                    handler: function(){
                        //调通用删除方法
                        comm.Generic_Del('/Message/del/','tab_grid','msgid');
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
                    {field:'msgid',title:'编码',width:60,hidden:true},
                    {field:'mtype',title:'信息类型',width:80,
                        formatter:function (value) {
                            if(value == 1){
                                return '游戏公告';
                            }else if(value == 2){
                                return "活动公告";
                            }else if(value == 3){
                                return "紧急通知";
                            }else{
                                return "其他信息";
                            }
                    }},
                    {field:'mcontent',title:'内容',width:250,
                        formatter:function (value) {
                        if(value.length > 20){
                            return value.substring(0,20)+'...';
                        }else{
                            return value;
                        }
                    }}
                ]]
            });
        })
    </script>
@endsection