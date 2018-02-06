@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table>
            <tr>
                <td><span> 购买日期：</span></td>
                <td><input id="start_date" class= "easyui-datebox" style="width: 100px;">--<input id="end_date" class= "easyui-datebox" style="width: 100px;"></td>
            </tr>
            <tr>
                <td><span> 玩家ID：</span></td>
                <td><input id="uid" class="easyui-textbox" style="width: 120px;"></td>
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
                title:'订单查询',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/BuySearch/data',
                idField:'id',
                toolbar:'#tb',
                columns:[[
                    {field:'head_img_url',title:'头像',width:40,
                        formatter:function (value) {
                            if(comm.is_null(value)){
                                if(value == "合计"){
                                    return value;
                                }else{
                                    return "<img src='"+value+"' style='border-radius:6px;' width='30'  align='absmiddle' >";
                                }
                            }else{
                                return "<img src='{{asset('/img/ui-default.jpg')}}' style='border-radius:6px;' width='30' align='absmiddle' >";
                            }
                        }},
                    {field:'userid',title:'UID',width:60},
                    {field:'nickname',title:'昵称',width:70},
                    {field:'cardnum',title:'数量',width:40},
                    {field:'total',title:'金额',width:40},
                    {field:'create_time',title:'购买日期',width:140}
                ]]
            });
            $("#btn_search").click(function () {
                $('#tab_grid').datagrid('load',{
                    uid: $('#uid').val(),
                    start_date: $('#start_date').val(),
                    end_date:$('#end_date').val()
                });
            })
        })
    </script>
@endsection