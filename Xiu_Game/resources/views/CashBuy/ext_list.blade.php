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
                        window.location.href='/Extract';
                    }
                }],
                columns:[[
                    {field:'id',title:'ID',width:60,hidden:true},
                    {field:'uid',title:'提现人ID',width:100},
                    {field:'gold',title:'提现金额',width:100,formatter:function (value,row,index) {
                            return parseInt(value);
                        }},
                    {field:'ctradedate',title:'提现日期',width:150}
                ]]
            });
        })
    </script>
@endsection