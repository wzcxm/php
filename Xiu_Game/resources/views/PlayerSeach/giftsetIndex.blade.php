@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table><tr>
                <td><span> 代理ID：</span><input id="uid" class="easyui-textbox" style="width: 60px;"></td>
                <td><a href="#" id="btn_search" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-search'">查询</a></td>
                <td><div class="datagrid-btn-separator"></div></td>
                <td><a href="javascript:window.location.reload();"  class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-reload'">刷新</a></td>
            </tr></table>
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
                title:'赠钻设置',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/CardSet/data',
                idField:'uid',
                toolbar:'#tb',
                columns:[[
                    {field:'head_img_url',title:'头像',width:50,
                        formatter:function (value) {
                            if(comm.is_null(value)){
                                return "<img src='"+value+"' style='border-radius:6px;' width='30'  align='absmiddle' >";
                            }else{
                                return "<img src='{{asset('/img/ui-default.jpg')}}' style='border-radius:6px;' width='30' align='absmiddle' >";
                            }
                        }},
                    {field:'uid',title:'ID',width:50},
                    {field:'nickname',title:'昵称',width:90},
                    {field:'rid',title:'级别',width:60,
                        formatter:function (value) {
                            if(value == 3){
                                return "总代";
                            }else if(value == 4){
                                return "特级代理";
                            }else{
                                return "代理";
                            }
                        }},
                    {field:'gift_uid',title:'操作',width:80,
                        formatter:function (value, row, index) {
                            if(comm.is_null(value)){
                                return "<button onclick='javascript:giftsave(2,"+row.uid+")'>取消赠送</button>";
                            }else{
                                return "<button onclick='javascript:giftsave(1,"+row.uid+")'>开启赠送</button>";
                            }
                        }}
                ]]
            });
            $("#btn_search").click(function () {
                $('#tab_grid').datagrid('load',{
                    uid: $('#uid').val()
                });
            });
        });
        function giftsave(type,uid) {
            $.post("/CardSet/save",{uid:uid,type:type},function (reslut) {
                if(comm.is_null(reslut.error)){
                    $.toptip(reslut.error,3000, 'warning');
                }else{
                    $.toast("操作成功！",function () {
                        comm.Reload('tab_grid');
                    });
                }
            });
        }
    </script>
@endsection