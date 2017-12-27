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
                title:'代理审核',
                singleSelect:true,
                border:false,
                fit:true,
                //               fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Examine/data',
                idField:'id',
                toolbar: [{
                    iconCls: 'icon-ok',
                    text:'通过',
                    handler: function(){
                        var rows =  $("#tab_grid").datagrid('getChecked');
                        if(rows.length<=0){
                            $.alert('请选择要审核的记录','提示');
                            return;
                        }
                        $.post('/Examine/adopt',{id:rows[0].id,game_uid:rows[0].game_uid,tel:rows[0].tel},function (data) {
                            if(!comm.is_null(data.error)){
                                comm.Reload('tab_grid');
                            }
                            else{
                                $.alert(data.error);
                            }
                        })
                    }
                },{
                    iconCls: 'icon-reload',
                    text:'刷新',
                    handler: function(){
                        comm.Reload('tab_grid');
                    }
                }],
                columns:[[
                    {field:'ck',checkbox:true},
                    {field:'id',title:'编码',width:60,hidden:true},
                    {field:'game_uid',title:'游戏ID',width:70},
                    {field:'tel',title:'联系方式',width:100},
                    {field:'state',title:'状态',width:50,
                        formatter:function (value) {
                        if(value == 0){
                            return '未通过';
                        }else if(value == 1){
                            return "通过";
                        }else{
                            return "其他";
                        }
                    }},
                    {field:'create_date',title:'申请日期',width:150}
                ]]
            });
        })
    </script>
@endsection