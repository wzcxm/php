@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <input type="hidden" id ='total' value="{{$total}}" />
    <table id="tab_grid" ></table>
    <div id="tb" style="padding:3px">
        <table><tr>
                <td><span> 代理ID：</span><input id="uid" class="easyui-textbox" style="width: 80px;"></td>
                <td><a href="#" id="btn_search" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-search'">查询</a></td>
                <td><div class="datagrid-btn-separator"></div></td>
                <td><a href="javascript:window.location.reload();"  class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-reload'">刷新</a></td>
                @if(!empty($role) && $role == 4)
                    <td><div class="datagrid-btn-separator"></div></td>
                    <td><a href="#" id="btn_role" class="easyui-linkbutton" plain="true"  data-options="iconCls:'icon-man'">设为总代</a></td>
                @endif
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
                title:'<p style="font-size: 0.8rem;">我的代理：'+$("#total").val()+'</p>',
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
                            if(value == 2){
                                return "代理";
                            }else if(value == 3){
                                return "总代";
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
            $("#btn_role").click(function () {

                var rows =  $("#tab_grid").datagrid('getChecked');
                if(rows.length<=0){
                    $.toptip('请选择一条记录！', 'warning');
                    return;
                }
                $.prompt({
                    title: '<p style=\'color: #3cc51f;\'>设置总代提成比例</p>',
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