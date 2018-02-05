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
                title:'提现记录',
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
                    iconCls: 'icon-reload',
                    text:'刷新',
                    handler: function(){
                        comm.Reload('tab_grid');
                    }
                },'-',{
                    iconCls: 'icon-back',
                    text:'返回',
                    handler: function(){
                        window.location.href='/Extract/index';
                    }
                }],
                columns:[[
                    {field:'id',title:'ID',width:60,hidden:true},
                    {field:'playerid',title:'申请ID',width:100},
                    {field:'gold',title:'申请金额',width:100},
                    // {field:'status',title:'类型',width:60,
                    //     formatter:function (value) {
                    //         if(value == 0){
                    //             return '提现';
                    //         }else if(value == 1){
                    //             return "红包";
                    //         }else{
                    //             return "";
                    //         }
                    // }},
                    {field:'ctradedate',title:'申请日期',width:150}
                ]]
            });
        })
    </script>
@endsection