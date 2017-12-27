@extends('Layout.EasyUiLayOut')
@section('easyui_style')
@endsection
@section('easyui_content')
    <div class="button_sp_area" style="padding-left: 20px;">
        <a href="javascript:;" id="btn_add" class="weui-btn weui-btn_mini weui-btn_primary">保存</a>
        <a href="javascript:window.location.reload();" class="weui-btn weui-btn_mini weui-btn_default">刷新</a>
    </div>
    <div style="width: 50%;float: left;">
        <div  class="easyui-panel" title="角色列表"
              data-options="collapsible:false,
                            minimizable:false,
                            maximizable:false,
                            closable:false,
                            height:$(window).height()-100">
            <ul id="role_tree"></ul>
        </div>
    </div>
    <div style="width: 50%;float: right;">
        <div  class="easyui-panel"  title="菜单列表"
              data-options="collapsible:false,
                          minimizable:false,
                          maximizable:false,
                          closable:false,
                          height:$(window).height()-100">
            <ul id="menus_tree"></ul>
        </div>
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
            //角色
            $("#role_tree").tree({
                url:'/Power/role',
                cascadeCheck:true,
                loadFilter: function(data){
                    return data;
                },
                onClick:function (node) {
                    var role_id = node.id;
                    $.get('/Power/getPower/'+role_id,function (data) {
                        if(!comm.is_null(data.Error)){
                            //取消所有选中的节点
                            var nodes = $("#menus_tree").tree('getChecked');
                            if(nodes.length>0){
                                for(var j=0;j<nodes.length;j++){
                                    $("#menus_tree").tree('uncheck',nodes[j].target);
                                }
                            }
                            var rows = data;
                            if(rows.length<=0) return;
                            //设置选中节点
                            for(var i=0;i<rows.length;i++){
                                var node = $("#menus_tree").tree('find',rows[i]['menuid']);
                                if(node){
                                    $("#menus_tree").tree('check',node.target);
                                }
                            }
                        }else{
                            $.alert(data.Error,'错误')
                        }
                    })
                }
            });
            //菜单
            $("#menus_tree").tree({
                url:'/Power/menu',
                checkbox:true,
                cascadeCheck:true,
                onlyLeafCheck:true,
                lines:true,
                loadFilter: function(data) {
                    return data;
                }
            });
            //保存设置
            $('#btn_add').click(function () {
                var role_node = $("#role_tree").tree('getSelected');
                var menu_nodes = $("#menus_tree").tree('getChecked');
                var role_id = "";
                var menu_ids = new Array();
                if(!comm.is_null(role_node)){
                    $.alert('请选择角色！','提示');
                    return;
                }else{
                    role_id = role_node.id;
                }
                if(!comm.is_null(menu_nodes)){
                    $.alert('请选择菜单！','提示');
                    return;
                }else{
                    for(var i=0;i<menu_nodes.length;i++){
                        menu_ids.push(menu_nodes[i].id);
                    }
                }
                Power.Save(role_id,menu_ids);
            });
            //刷新
            $('#btn_refresh').click(function () {
                window.location.reload();
            });
        });
        var Power={
            Save:function (role_id,menu_ids) {
                $.post('/Power/save',{role:role_id,menus:menu_ids},function (data) {
                    if(!comm.is_null(data.Error)){
                        $.alert('保存成功！');
                    }else{
                        $.alert(data.Error,'错误');
                    }
                });
            }
        }
    </script>
@endsection