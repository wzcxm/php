@extends('Layout.layout')
@section('content')

    战绩查询
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
            <table width="100%" >
                <tr>
                    <td width="30%" align="right">开始日期：</td>
                    <td  width="60%"><input type="date" name="start_date" id="start_date"></td>
                    <td></td>
                </tr>
                <tr>
                    <td align="right">结束日期：</td>
                    <td><input type="date" name="end_date" id="end_date"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div data-role="controlgroup" data-type="horizontal" data-theme="b">
                            <a href="#" class="ui-btn" id="btn_search">查询</a>
                            <a href="#" onclick="javascript:window.location.href='/Group'" class="ui-btn"  >返回</a>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="13%">房号</th>
                <th width="14%">类型</th>
                <th width="13%">房主</th>
                <th width="20%">日期</th>
                <th width="40%">战绩</th>
            </tr>
            </thead>
            <tbody>
                @if(!empty($List))
                    @foreach($List as $item)
                        <tr>
                            <td>{{$item->roomid}}</td>
                            <td>{{$item->g_type}}</td>
                            <td>{{$item->player_id}}</td>
                            <td>{{$item->r_time}}</td>
                            <td>{{$item->record}}</td>
                        </tr>
                    @endforeach
                    @if(sizeof($List)==10)
                        <tr>
                            <td colspan="5">
                                <a href="#" value="10" onclick="javascript:NextPage(this);" data-role="button">点击加载更多</a>
                            </td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>
        <input type="hidden" id="g_id" value="{{$g_id}}">
    </div>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_search").click(function () {
                var start = $("#start_date").val();
                var end = $("#end_date").val();
                var g_id= $("#g_id").val();
                if(!comm.is_null(g_id))  return;
                if(!comm.is_null(start) && !comm.is_null(end))  return;
                $.get('/Group/RecordSearch/'+g_id+'/'+start+'/'+end,function (data) {
                    if(comm.is_null(data.List)){
                        var html = setHtml(data.List);
                        $("#table_list tbody").empty().append(html);
                        if(data.List.length>=10){
                            var btn_html = "<tr> <td colspan='5'> <a href='#' value='10' onclick='javascript:NextPage(this);'" +
                                " class=\"ui-link ui-btn ui-shadow ui-corner-all\" data-role='button'>点击加载更多</a> </td> </tr>";
                            $("#table_list tbody").append(btn_html)
                        }
                    }
                })
            });
        })
        function setHtml(obj) {
            var html;
            for(var i=0;i<obj.length;i++){
                html+="<tr><td>"+obj[i]["roomid"]+"</td>"+
                    "<td>"+obj[i]["g_type"]+"</td>"+
                    "<td>"+obj[i]["player_id"]+"</td>"+
                    "<td>"+obj[i]["r_time"]+"</td>"+
                    "<td>"+obj[i]["record"]+"</td></tr>";
            }
            return html;
        }

        function NextPage(obj) {
            var data = new Object();
            data.offset = $(obj).attr('value');
            data.start = $("#start_date").val();
            data.end = $("#end_date").val();
            data.g_id= $("#g_id").val();
            $.get('/Group/NextPage',{data:data},function (data) {
                if(comm.is_null(data.List)){
                    var html = setHtml(data.List);
                    $("#table_list").find('tr:last').before(html);
                    var offset = $(obj).attr('value');
                    $(obj).attr('value',(parseInt(offset)+10));
                    if(data.List.length<10){
                        $(obj).remove();
                    }
                }
            })
        }

    </script>
@endsection