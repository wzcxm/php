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
                title:'商品设置',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/ShoppingMall/data',
                idField:'sid',
                toolbar: [{
                    iconCls: 'icon-add',
                    text:'添加',
                    handler: function(){
                        window.location.href='/ShoppingMall/add';
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
                        window.location.href='/ShoppingMall/add/'+rows[0].sid;
                    }
                },'-',{
                    iconCls: 'icon-remove',
                    text:'删除',
                    handler: function(){
                        //调通用删除方法
                        comm.Generic_Del('/ShoppingMall/del/','tab_grid','sid');
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
                    {field:'sid',title:'编码',width:60,hidden:true},
                    {field:'type',title:'商城平台',width:80,
                        formatter:function (value) {
                            if(value == 0){
                                return "游戏商城";
                            }else if(value == 1){
                                return "公众号商城";
                            }else{return "其他";}
                        }},
                    {field:'sgive',title:'商品类型',width:80,
                        formatter:function (value) {
                            if(value == 0){
                                return '钻石';
                            }else if(value == 1){
                                return "金币";
                            }else{
                                return "其他";
                            }
                        }},
                    {field:'scommodity',title:'商品名称',width:80},
                    {field:'sprice',title:'价格',width:60},
                    {field:'snumber',title:'数量',width:60},
                    {field:'sremarks',title:'商品描述',width:200}
                ]]
            });
        })
    </script>
@endsection