@extends('Layout.layout')
@section('content')
    上下分
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
        <span id="error" style="color:red;"></span>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="40%">玩家ID</th>
                <th width="20%">分数</th>
                <th width="40%">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr>
                    <td>
                        <a href="#PaylerInfo" data-rel="popup" style="text-decoration:none;"  data-transition="slidedown" onclick="javascript:GetInfo('{{$item->uid}}');">
                            <img src="{{empty($item->head_img_url)?asset('/img/ui-default.jpg'):$item->head_img_url}}" style="border-radius:6px;" width="40" align="absmiddle" >
                            ID:{{$item->uid}}
                        </a>
                    </td>
                    <td>{{$item->scores}}</td>
                    <td>
                        <div data-role="controlgroup" data-type="horizontal" data-theme="b" class="ui-mini">
                            <a href="/UpAndDown/Up/{{$item->uid}}"  class="ui-btn">上分</a>
                            <a href="/UpAndDown/Down/{{$item->uid}}" class="ui-btn" >下分</a>
                        </div>
                    </td>
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
                $.get('/UpAndDown/Search/'+id,function (data) {
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

        })
        function setHtml(obj) {
            var head_url=comm.is_null(obj['head_img_url'])?obj['head_img_url']:'./img/ui-default.jpg';
            var html = "<td>" +
                        "<a href='#PaylerInfo' data-rel='popup' style='text-decoration:none;'  data-transition='slidedown' " +
                        "onclick='javascript:GetInfo(\""+obj['uid']+"\");'>" +
                        "<img src='"+head_url+"' style='border-radius:6px;' width='40' align='absmiddle'>" +
                       "ID:"+obj['uid']+"</a></td>"+
                        "<td>"+obj['scores']+"</td>"+
                        "<td><div data-role='controlgroup' data-type='horizontal' data-theme='b' class='ui-mini ui-controlgroup ui-controlgroup-horizontal ui-group-theme-b ui-corner-all'>"+
                                "<div class='ui-controlgroup-controls '>"+
                                "<a href='/UpAndDown/Up/"+obj['uid']+"\'  class='ui-btn ui-first-child'>上分</a>"+
                                 "<a href='/UpAndDown/Down/"+obj['uid']+"\' class='ui-btn ui-last-child' >下分</a></div>" +
                        "</div></td></tr>";
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