@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table>
            <tr>
                <td><span> ID：</span></td>
                <td><input id="uid" class="easyui-textbox" style="width: 120px;"></td>
                <td><a href="#" id="btn_search" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-search'">查询</a></td>
                <td><div class="datagrid-btn-separator"></div></td>
                <td><a href="javascript:window.location.reload();"  class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-reload'">刷新</a></td>
            </tr>
            <tr>
                <td ><span> 上级ID：</span></td>
                <td><input id="front" class="easyui-textbox" style="width: 120px;"></td>
                <td><a href="#" id="btn_black" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-lock'">拉黑</a></td>
                <td><div class="datagrid-btn-separator"></div></td>
                <td><a href="#" id="btn_white" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-ok'">取消</a></td>
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
                title:'玩家列表',
                singleSelect:false,
                border:false,
                fit:true,
                //               fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/Players/data',
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
                    {field:'nickname',title:'昵称',width:100},
                    {field:'ustate',title:'状态',width:60,
                        formatter:function (value) {
                            if(value == 0){
                                return '正常';
                            } else{
                                return '冻结';
                            }
                        }},
                    {field:'rid',title:'类型',width:60,
                        formatter:function (value) {
                            if(value==1){
                                return '公司';
                            }else if(value==2){
                                return '代理';
                            }else if(value==7){
                                return 'System';
                            }else{
                                return '玩家';
                            }
                        }},
                    {field:'roomcard',title:'钻石',width:60},
                    {field:'gold',title:'金币',width:60},
                    {field:'uphone',title:'电话',width:90},
                    {field:'front_uid',title:'上级ID',width:60},
                    {field:'front_nick',title:'上级昵称',width:100},
                    {field:'front_hone',title:'上级电话',width:90},
                    {field:'create_time',title:'加入日期',width:120}
                ]]
            });
            $("#btn_search").click(function () {
                $('#tab_grid').datagrid('load',{
                    uid: $('#uid').val(),
                    front_uid:$('#front').val()
                });
            });
            //拉黑
            $("#btn_black").click(function () {
                var rows =  $("#tab_grid").datagrid('getChecked');
                if(rows.length<=0){
                    $.alert('请选择拉黑的玩家','提示');
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
                $.confirm("您确定要拉黑这些玩家吗？", function() {
                    //点击确认后的回调函数
                    $.post('/Players/lock',{data:ids},function (result) {
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
            });
            //取消拉黑
            $("#btn_white").click(function () {
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
                    $.post('/Players/unlock',{data:ids},function (result) {
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
            });
        })
    </script>
@endsection