@extends('Layout.layout')
@section('content')
    分数设置
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%" >
            <tr>
                <td width="70%"><input type="text" placeholder="ID"  name="payer_id" id="payer_id"   ></td>
                <td width="30%">
                    <button class="ui-btn ui-icon-search ui-btn-icon-left ui-corner-all ui-btn-b"  id="btn_search">查询</button>
                </td>
            </tr>
        </table>
        <div data-role="controlgroup" data-type="horizontal" data-theme="b">
            <a href="#" class="ui-btn" onclick="javascript:window.location.href='/White/Set/Add'">添加</a>
            <a href="#" class="ui-btn" id="btn_edit">修改</a>
            <a href="#" class="ui-btn" id="btn_del">删除</a>
            <a href="#" onclick="javascript:window.location.reload();" class="ui-btn"  >刷新</a>
            <a href="#" onclick="javascript:window.location.href='/White'" class="ui-btn"  >返回上级</a>
        </div>
        <span id="error" style="color:red;"></span>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="10%"></th>
                <th width="25%">玩家ID</th>
                <th width="30%">游戏类型</th>
                <th width="18%">上限</th>
                <th width="17%">下限</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr>
                    <td><input data-role="none" type="checkbox" value="{{$item->id}}" /></td>
                    <td>
                        @if($item->uid==1)
                            全局
                        @else
                            <a href="#PaylerInfo" data-rel="popup" style="text-decoration:none;"  data-transition="slidedown" onclick="javascript:GetInfo('{{$item->uid}}');">
                                ID:{{$item->uid}}
                            </a>
                        @endif
                    </td>
                    <td>
                        @if($item->g_type==1)
                            跑得快
                        @elseif($item->g_type==2)
                            宁乡麻将
                        @elseif($item->g_type==3)
                            红中麻将
                        @else
                            其他
                        @endif
                    </td>
                    <td>{{empty($item->upper_limit)?0:$item->upper_limit}}</td>
                    <td>{{empty($item->lower_limit)?0:$item->lower_limit}}</td>
                </tr>
            @endforeach
            </tbody>
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
            $("#btn_search").click(function () {
                var id = $("#payer_id").val();
                $.get('/White/Search/'+id,function (data) {
                    if(!comm.is_null(data.Error)){
                        $("#error").html("");
                        if(comm.is_null(data.user)) {
                            var html = setHtml(data.user)
                            $("#table_list tbody").empty().append(html);
                        }
                    }else{
                        $("#error").html(data.Error);
                    }
                })
            });

            $("#btn_del").click(function () {
                var arr=Array();
                $("#table_list input[type=checkbox]:checked").each(function () {
                    arr.push(this.value);
                })
                if(!comm.is_null(arr) || arr.length<=0){
                    return;
                }
                $.get('/White/Set/Del/'+arr,function (data) {
                    if(data.msg==1){
                        window.location.reload();
                    }
                    else{
                        alert(data.msg);
                    }
                });
            })

            $("#btn_edit").click(function () {
                var rows = $("#table_list input[type=checkbox]:checked");
                if(!comm.is_null(rows) || rows.length<=0){
                    return;
                }
                window.location.href='/White/SetEdit/'+rows[0].value;
            })
        })
        function setHtml(obj) {
            var type="其他";
            if(obj['g_type']==1){
                type="宁乡麻将";
            }
            var uid_html="<a href='#PaylerInfo' data-rel='popup' style='text-decoration:none;'  data-transition='slidedown' onclick='javascript:GetInfo(\""+obj['uid']+"\");'>ID:"+obj['uid']+"</a>";
            if(obj['uid']==1) {
                uid_html = "全局";
            }
            var html = "<td><input data-role='none' type='checkbox' value="+obj['id']+" /></td>" +
                "<td>" + uid_html+"</td>"+
                "<td>"+type+"</td>"+
                "<td>"+(comm.is_null(obj['upper_limit'])?obj['upper_limit']:0)+"</td>"+
                "<td>"+(comm.is_null(obj['lower_limit'])?obj['lower_limit']:0)+"</td>";
            return html;
        }
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