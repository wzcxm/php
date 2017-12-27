var comm={
    is_null:function (obj) {
        if(typeof (obj)=='undefined' || obj=="" || obj==null)
            return false;
        else
            return true;
    },
    Alert: function (type, msg) {
        var str="";
        if (type == "Info") {
            str = "<div  class='alert alert-success'><strong>OK！</strong>" + msg + "</div>";
        } else {
            str = "<div  class='alert alert-warning'><strong>Error！</strong>" + msg + "</div>";
        }
        $("#Alert").empty();
        $("#Alert").html(str)
        $("#Alert").fadeIn('slow');
        setTimeout("$('#Alert').fadeOut('slow');",3000);
    },
    btn_status:function (obj,status) {
        if(status == 'Disabled'){
            $('#'+obj).prop('disabled',true).addClass("weui-btn_disabled");
        }else if(status == 'Enabled'){
            $('#'+obj).prop('disabled',false).removeClass("weui-btn_disabled");
        }else{

        }
    },
    //DataGrid通用删除数据方法
    Generic_Del:function (url,datagrid,key) {
        var rows =  $("#"+datagrid).datagrid('getChecked');
        if(rows.length<=0){
            $.alert('请选择要删除的记录','提示');
            return;
        }
        var ids = "";
        for(var i=0;i<rows.length;i++){
            if(ids==""){
                ids += rows[i][key];
            }else{
                ids += ','+rows[i][key];
            }
        }
        $.confirm("您确定要删除该数据吗？", function() {
            //点击确认后的回调函数
            $.post(url+ids,function (result) {
                if(result.msg==1){
                    comm.Reload(datagrid);
                }
                else{
                    $.alert(result.msg);
                }
            });
        }, function() {
            //点击取消后的回调函数
        });
    },
    //DataGrid通用刷新方法
    Reload:function (datagrid) {
        $("#"+datagrid).datagrid('reload');
        $("#"+datagrid).datagrid('clearChecked');
        $("#"+datagrid).datagrid('clearSelections');
    }
};