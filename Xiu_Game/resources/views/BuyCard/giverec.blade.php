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
                title:'赠送记录',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/BuyCard/giverec',
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
                        window.location.href='/BuyCard';
                    }
                }],
                columns:[[
                    {field:'cid',title:'ID',hidden:true},
                    {field:'buy_head',title:'头像',width:40,
                        formatter:function (value) {
                            if(comm.is_null(value)){
                                return "<img src='"+value+"' style='border-radius:6px;' width='30'  align='absmiddle' >";
                            }else{
                                return "<img src='{{asset('/img/ui-default.jpg')}}' style='border-radius:6px;' width='30' align='absmiddle' >";
                            }
                        }},
                    {field:'cbuyid',title:'玩家ID',width:50},
                    {field:'buy_nick',title:'昵称',width:70},
                    {field:'cnumber',title:'数量',width:50},
                    {field:'ctype',title:'类型',width:40,
                        formatter:function (value) {
                            if(value == 1){
                                return '钻石';
                            }else if(value == 2){
                                return "金豆";
                            }else{
                                return "";
                            }
                        }},
                    {field:'ctradedate',title:'赠送日期',width:130}
                ]]
            });
        })
    </script>
@endsection