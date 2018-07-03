@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table>
            <tr>
                <td><span>充值日期：</span></td>
                <td><input id="start_date" class= "easyui-datebox" style="width: 100px;">--<input id="end_date" class= "easyui-datebox" style="width: 100px;"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td><a href="#" id="btn_search" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-search'">查询</a></td>
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
                title:'充值记录',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Recharge/data',
                idField:'id',
                toolbar:'#tb',
                columns:[[
                    {field:'id',title:'id',hidden:true},
                    {field:'create_time',title:'充值日期',width:130},
                    {field:'total',title:'充值金额',width:100},
                    {field:'cardnum',title:'充值数量',width:100}
                ]]
            });
            $("#btn_search").click(function () {
                $('#tab_grid').datagrid('load',{
                    start_date: $('#start_date').val(),
                    end_date:$('#end_date').val()
                });
            })
        })
    </script>
@endsection