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
                title:'我的牌馆',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Museum/data',
                idField:'tea_id',
                toolbar: [{
                    iconCls: 'icon-edit',
                    text:'设置规则',
                    handler: function(){
                        var rows =  $("#tab_grid").datagrid('getChecked');
                        if(rows.length<=0){
                            $.alert('请选择要设置的牌馆','提示');
                            return;
                        }
                        window.location.href='/Museum/setting/'+rows[0].tea_id;
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
                    {field:'tea_id',title:'牌馆编号',width:100},
                    {field:'tea_name',title:'牌馆名称',width:200}
                ]]
            });
        })
    </script>
@endsection