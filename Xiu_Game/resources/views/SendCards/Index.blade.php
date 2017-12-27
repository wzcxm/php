@extends('Layout.layout')
@section('content')
    充值送卡
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%">
            <tr>
                <td style="text-align:right;" width="20%">全服赠送：</td>
                <td width="60%" colspan="2">
                    <input type="checkbox" data-role="none" id="qf">
                    <input id="perosn" type="text" data-role="none" value="{{$person}}"
                           style="width:100px;border-left:none; border-right:none; border-top:none; border-bottom:1px solid #0F2543;"
                           readonly="readonly"  >&nbsp;人
                    <img  data-role="none" style="cursor: pointer;height:25px;width:25px;" id="Refresh" src="{{asset('/img/res.jpg')}}"/>
                </td>

            </tr>
            <tr>
                <td style="text-align:right;">赠送数量：</td>
                <td><input type="number" id="sbnum"></td>
                <td width="20%"></td>
            </tr>
            <tr>
                <td style="text-align:right;">送卡ID：</td>
                <td><input type="number" id="uid"></td>
                <td><button class="ui-btn ui-icon-search ui-btn-icon-left ui-corner-all ui-btn-b"  id="btn_search" >查询</button></td>
            </tr>
        </table>
        <table id="table_list" class="bordered">
            <thead>
            <tr>

                <th width="40%">ID</th>
                <th width="20%">级别</th>
                <th width="20%">个人</th>
                <th width="20%">全部</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="13%"></td>
                <td width="30%">
                    <button class="ui-btn ui-btn-icon-left ui-corner-all ui-btn-b"  id="btn_query" >确认送卡</button>
                </td>
                <td width="13%"></td>
                <td width="30%">
                    <button class="ui-btn ui-btn-icon-left ui-corner-all ui-btn-b"  id="btn_list" >送卡记录</button>
                </td>
                <td width="14%"></td>
            </tr>
        </table>
    </div>
    <div data-role="popup" id="PaylerInfo" class="ui-content">
        玩家信息
        <hr>
        <div class="ui-field-contain">
            <table  style="width:100%;border-collapse:separate; border-spacing:5px;">
                <tr>
                    <td width="40%"  align="right">昵称：</td>
                    <td><span id="nickname"></span></td>
                </tr>
                <tr>
                    <td align="right">手机号码：</td>
                    <td><span id="uphone"></span></td>
                </tr>
            </table>
        </div>
    </div>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //查询
            $("#btn_search").click(function () {
                var uid=$("#uid").val();
                if(!comm.is_null(uid)) {
                    return;
                }
                $.get('/SendCards/GetUserInfo/' + uid, function (data) {
                    if(comm.is_null(data.user))
                    {
                        var head_url=comm.is_null(data.user['head_img_url'])?data.user['head_img_url']:'./img/ui-default.jpg';
                        var html = "<tr><td>" +
                            "<a href='#PaylerInfo' data-rel='popup' style='text-decoration:none;'  data-transition='slidedown' " +
                            "onclick='javascript:GetInfo(\""+data.user['uid']+"\");'>" +
                            "<img src=" + head_url + " style='border-radius:6px;' width='40' align='absmiddle'>ID:" + data.user['uid'] + "</a></td>" +
                            "<td>" + data.user['rname'] + "</td>" +
                            "<td><input type='checkbox'  name='one' value='" + data.user['uid']+ "' /></td>" +
                            "<td><input type='checkbox'  name='all' value='" + data.user['uid'] + "' /></td>" +
                            "</tr>";
                        $("#table_list tbody").empty().append(html);
                    }
                })

            })
            //确认送卡
            $("#btn_query").click(function () {
                var userid = "";
                var addtype = "";
                if ($("#qf").prop("checked"))
                {
                    addtype = "3";//全服赠送
                }
                else {
                    if ($("#table_list input[name=all]").prop("checked")) {
                        userid = $("#table_list input[name=all]").val();
                        addtype = "2"; //全选，个人及下属
                    }
                    else {
                        if ($("#table_list input[name=one]").prop("checked")) {
                            userid = $("#table_list input[name=one]").val();
                            addtype = "1";//个人
                        }
                        else {
                            comm.Alert('Error','请选择赠送方式！');
                            return;
                        }
                    }
                }
                var number= $("#sbnum").val()
                if (!comm.is_null(number))
                {
                    comm.Alert("Error", "请输入赠送数量！");
                    return;
                }
                $.post('/SendCards/Send/'+addtype+'/'+number+'/'+userid,function(data){
                    if(data.msg==1)
                    {
                        comm.Alert("Info", "赠送成功！");
                    }else{comm.Alert("Error",'赠送失败，请检查是否输入正确！');}
                })
            })
            //送卡记录
            $("#btn_list").click(function () {
                location.href = "/SendCards/GetList";
            })
            $("#Refresh").click(function () {
                $.get('/SendCards/GetPerosn',function (data) {
                    if(comm.is_null(data.person)){
                        $("#perosn").val(data.person);
                    }
                })
            })
        })
        function GetInfo(uid) {
            $.get('/UserManage/GetInfo/'+uid,function (data) {
                if(comm.is_null(data.userInfo)){
                    $("#nickname").html(data.userInfo['nickname']);
                    $("#uphone").html(data.userInfo['uphone']);
                }
            })
        }
    </script>
@endsection