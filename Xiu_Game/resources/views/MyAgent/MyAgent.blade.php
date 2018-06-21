@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <input type="hidden" id ='total' value="{{$total}}" />
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table><tr>
                <td><span> 代理ID：</span><input id="uid" class="easyui-textbox" style="width: 60px;"></td>
                <td><a href="#" id="btn_search" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-search'">查询</a></td>
                <td><div class="datagrid-btn-separator"></div></td>
                <td><a href="javascript:window.location.reload();"  class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-reload'">刷新</a></td>
                @if(!empty($role))
                    <td><div class="datagrid-btn-separator"></div></td>
                    @if($role == 4)
                        <td><a href="#" id="btn_role" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-man'">设为总代</a></td>
                    @elseif($role == 3)
                        <td><a href="#" id="btn_qd" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-man'">设为渠道</a></td>
                    @else
                        <td></td>
                    @endif
                @endif
                <td>
                    <a href="#" id="btn_return" class="easyui-linkbutton" plain="true" style="display:none;"  data-options="iconCls:'icon-undo'">返回</a>
                    <input type="hidden" id="return_item" value="{{$role}}&{{$role==2?$uid:$aisle}}">
                </td>
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
                title:'<p style="font-size: 0.8rem;">旗下代理：'+$("#total").val()+'</p>',
                singleSelect:true,
                border:false,
                fit:true,
//                fitColumns:true,
                scrollbarSize:0,
                pagination:true,
                rownumbers:true,
                showFooter: true,
                url:'/MyAgent/data',
                idField:'uid',
                toolbar:'#tb',
                onDblClickRow :function(rowIndex,rowData){
                    var cx_id = rowData.uid;
                    if(rowData.rid != 2){
                        cx_id = rowData.aisle;
                    }
                    $("#return_item").val($("#return_item").val()+'|'+rowData.rid+'&'+cx_id);
                    $('#tab_grid').datagrid('load',{
                        cxid: cx_id,
                        cxrid:rowData.rid
                    });
                    $("#btn_return").show();
                    if($("#btn_role")){
                        $("#btn_role").hide();
                    }
                    if($("#btn_qd")){
                        $("#btn_qd").hide();
                    }
                },
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
                    {field:'nickname',title:'昵称',width:70},
                    {field:'rid',title:'级别',width:50,
                        formatter:function (value) {
                            if(value == 3){
                                return "总代";
                            }else if(value == 4){
                                return "特级代理";
                            }else{
                                return "代理";
                            }
                        }},
                    {field:'roomcard',title:'钻石',width:60},
                    {field:'gold',title:'金豆',width:60},
                    {field:'create_time',title:'加入日期',width:120}
                ]]
            });
            $("#btn_search").click(function () {
                $('#tab_grid').datagrid('load',{
                    uid: $('#uid').val()
                });
            });
            $("#btn_return").click(function () {
                var return_items = $("#return_item").val();
                var values;
                var items;
                if(return_items.indexOf('|')==-1){
                    values =  return_items.split('&');
                }else{
                    items = return_items.substring(0,return_items.lastIndexOf('|'));
                    $("#return_item").val(items);
                    values = items.substring(items.lastIndexOf('|')+1).split('&');
                }
                if(items.indexOf('|')==-1){
                    $("#btn_return").hide();
                    if($("#btn_role")){
                        $("#btn_role").show();
                    }
                    if($("#btn_qd")){
                        $("#btn_qd").show();
                    }
                }
                $('#tab_grid').datagrid('load',{
                    retrid: values[0],
                    retid:values[1]
                });

            });
            $("#btn_qd").click(function () {

                var rows =  $("#tab_grid").datagrid('getChecked');
                if(rows.length<=0){
                    $.toptip('请选择一条记录！', 'warning');
                    return;
                }
                $.confirm({
                    title: '<p style=\'color: #3cc51f;\'>设为渠道代理</p>',
                    text: "<img  src='"+rows[0].head_img_url+"' style=\"border-radius:6px;\" width=\"30\" align=\"absmiddle\" >\n" +
                    "<span style=\"color: #545454\">"+rows[0].nickname+"</span><br><span style=\"color: red\">提成比例为25%</span>",
                    onOK: function (input) {
                        var obj = new Object();
                        obj.uid = rows[0].uid;
                        obj.rate = 25;
                        $.post('/MyAgent/setrole',{data:obj},function(data){
                            if(comm.is_null(data.error)){
                                $.toptip(data.error,3000, 'warning');
                            }else{
                                $.toast("设置成功！",function () {
                                    comm.Reload('tab_grid');
                                });
                            }
                        });
                    },
                    onCancel: function () {
                        //点击取消
                    }
                });
            });
            $("#btn_role").click(function () {

                var rows =  $("#tab_grid").datagrid('getChecked');
                if(rows.length<=0){
                    $.toptip('请选择一条记录！', 'warning');
                    return;
                }
                $.prompt({
                    title: '<p style=\'color: #3cc51f;\'>设提成比例</p>',
                    text: "<img  src='"+rows[0].head_img_url+"' style=\"border-radius:6px;\" width=\"30\" align=\"absmiddle\" >\n" +
                    "<span style=\"color: #545454\">"+rows[0].nickname+"</span>",
                    input: '',
                    empty: false, // 是否允许为空
                    onOK: function (input) {
                        //点击确认
                        if(isNaN(input)){
                            $.toptip('提成比例必须填写数字！',4000, 'warning');
                            return ;
                        }
                        var obj = new Object();
                        obj.uid = rows[0].uid;
                        obj.rate = input;
                        $.post('/MyAgent/setrole',{data:obj},function(data){
                            if(comm.is_null(data.error)){
                                $.toptip(data.error,3000, 'warning');
                            }else{
                                $.toast("设置成功！",function () {
                                    comm.Reload('tab_grid');
                                });
                            }
                        });
                    },
                    onCancel: function () {
                        //点击取消
                    }
                });
            });
        })
    </script>
@endsection