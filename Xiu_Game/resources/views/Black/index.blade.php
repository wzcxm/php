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
                title:'黑名单',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Blacklist/data',
                idField:'uid',
                toolbar: [{
                    iconCls: 'icon-lock',
                    text:'封号',
                    handler: function(){
                        window.location.href='/Blacklist/add';
                    }
                },{
                    iconCls: 'icon-ok',
                    text:'解封',
                    handler: function(){
                        var rows =  $("#tab_grid").datagrid('getChecked');
                        if(rows.length<=0){
                            $.alert('请选择要解封的玩家','提示');
                            return;
                        }
                        var ids = "";
                        for(var i=0;i<rows.length;i++){
                            if(ids==""){
                                ids += rows[i]['uid'];
                            }else{
                                ids += ','+rows[i]['uid'];
                            }
                        }
                        $.confirm("您确定要解封这些玩家吗？", function() {
                            //点击确认后的回调函数
                            $.post('/Blacklist/del/'+ids,function (result) {
                                if(result.msg==1){
                                    comm.Reload('tab_grid');
                                }
                                else{
                                    $.alert(result.msg);
                                }
                            });
                        }, function() {
                            //点击取消后的回调函数
                        });
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
                    {field:'head_img_url',title:'玩家头像',width:80,
                        formatter:function (value) {
                        if(!comm.is_null(value)){
                            return "<img src='"+value+"' style='border-radius:6px;' width='30' align='absmiddle' >";
                        }else{
                            return "<img src='{{asset('/img/ui-default.jpg')}}' style='border-radius:6px;' width='30' align='absmiddle' >";
                        }
                    }},
                    {field:'nickname',title:'玩家昵称',width:80},
                    {field:'uid',title:'玩家ID',width:80},
                    {field:'ustate',title:'状态',width:60,
                        formatter:function (value) {
                        if(value < 0){
                            return '禁止';
                        }else{
                            return "启用";
                        }
                    }}
                ]]
            });
        })
    </script>
@endsection

