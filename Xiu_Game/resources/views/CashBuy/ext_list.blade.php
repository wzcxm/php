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
                title:'提现申请记录',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/BackCash/getlist',
                idField:'id',
                toolbar:[{
                    iconCls: 'icon-back',
                    text:'返回',
                    handler: function(){
                        //调通用删除方法
                        window.location.href='/Extract/index';
                    }
                },'-',{
                    iconCls: 'icon-reload',
                    text:'刷新',
                    handler: function(){
                        comm.Reload('tab_grid');
                    }
                }],
                columns:[[
                    {field:'id',title:'ID',width:60,hidden:true},
                    {field:'playerid',title:'申请人ID',width:80},
                    {field:'gold',title:'申请金额',width:80},
                    {field:'status',title:'状态',width:60,
                        formatter:function (value) {
                            if(value == 0){
                                return '未完成';
                            }else if(value == 1){
                                return "已完成";
                            }else{
                                return "";
                            }
                    }},
                    {field:'create_time',title:'申请日期',width:150}
                ]]
            });
        })
    </script>
@endsection