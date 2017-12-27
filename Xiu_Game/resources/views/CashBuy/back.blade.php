@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table>
            <tr>
                <td><span> 日期：</span></td>
                <td><input id="start_date" class= "easyui-datebox" style="width: 100px;">--<input id="end_date" class= "easyui-datebox" style="width: 100px;"></td>
            </tr>
            @if(!empty($role) && $role==1)
                <tr>
                    <td><span> 玩家ID：</span></td>
                    <td><input id="uid" class="easyui-textbox" style="width: 120px;"></td>
                </tr>
            @endif
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
                title:'返利统计（返利比例：下级20%；下下级5%；玩家50%；）',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/BackCash/data',
                idField:'id',
                toolbar:'#tb',
                columns:[[
                    {field:'id',title:'id',hidden:true},
                    {field:'b_head',title:'头像',width:50,
                        formatter:function (value) {
                            if(comm.is_null(value)){
                                if(value!="合计")
                                    return "<img src='"+value+"' style='border-radius:6px;' width='30'  align='absmiddle' >";
                                else
                                    return value
                            }else{
                                return "<img src='{{asset('/img/ui-default.jpg')}}' style='border-radius:6px;' width='30' align='absmiddle' >";
                            }
                        }},
                    {field:'back_id',title:'ID',width:60},
                    {field:'b_nick',title:'昵称',width:90},
                    {field:'level',title:'类型',width:50,
                        formatter:function (value) {
                            if(value==1){
                                return "下级";
                            }else if(value==2){
                                return "下下级";
                            }else if(value==3){
                                return "玩家";
                            }else{return "";}
                        }},
                    {field:'gold',title:'购买金额',width:60},
                    {field:'backgold',title:'返现金额',width:60},
                    {field:'create_time',title:'日期',width:120}
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