@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table>
            <tr>
                <td><span> ID：</span><input id="uid" class="easyui-textbox" style="width: 120px;"></td>
                <td><a href="#" id="btn_search" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-search'">查询</a></td>
                <td><div class="datagrid-btn-separator"></div></td>
                <td><a href="#" id="btn_del" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-remove'">删除</a></td>
                <td><div class="datagrid-btn-separator"></div></td>
                <td><a href="javascript:window.location.reload();"  class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-reload'">刷新</a></td>
            </tr>
        </table>
    </div>
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
                title:'代理列表',
                singleSelect:true,
                border:false,
                fit:true,
                //               fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/AgentDel/data',
                idField:'uid',
                toolbar:'#tb',
                columns:[[
                    {field:'ck',checkbox:true},
                    {field:'head_img_url',title:'头像',width:60,
                        formatter:function (value) {
                            if(comm.is_null(value)){
                                return "<img src='"+value+"' style='border-radius:6px;' width='30'  align='absmiddle' >";
                            }else{
                                return "<img src='{{asset('/img/ui-default.jpg')}}' style='border-radius:6px;' width='30' align='absmiddle' >";
                            }
                        }},
                    {field:'uid',title:'ID',width:60},
                    {field:'nickname',title:'昵称',width:120},
                    {field:'roomcard',title:'钻石',width:60},
                    {field:'uphone',title:'联系方式',width:100},
                    {field:'create_time',title:'加入日期',width:150}
                ]]
            });
            $("#btn_search").click(function () {
                $('#tab_grid').datagrid('load',{
                    uid: $('#uid').val()
                });
            });
            $("#btn_del").click(function () {
                var rows =  $("#tab_grid").datagrid('getChecked');
                if(rows.length<=0){
                    $.alert('请选择要删除的代理','提示');
                    return;
                }
                $.post('/AgentDel/delete',{uid:rows[0].uid},function(data){
                    if(!comm.is_null(data.error)){
                        $.alert('删除成功！',function () {
                            comm.Reload('tab_grid');
                        });
                    }
                    else{
                        $.alert(data.error);
                    }
                });
            });
        })
    </script>
@endsection