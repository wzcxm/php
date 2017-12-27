@extends('Layout.layout')
@section('content')
    拨入统计&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="border-radius: 4px;background-color:#c35858;color:white;font-weight: 400;">&nbsp;共计房卡：<b id="sum_num">{{$sum_num}}</b>&nbsp;</span>
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <from id="tab_where">
        <table width="100%" >
            <tr>
                <td width="30%" align="right">开始日期：</td>
                <td width="40%"><input type="date"  name="start_date" id="start_date"   ></td>
                <td width="30%">
                </td>
            </tr>
            <tr>
                <td width="30%" align="right">结束日期：</td>
                <td width="40%"><input type="date"  name="end_date" id="end_date"   ></td>
                <td width="30%">

                </td>
            </tr>
            <tr>
                <td align="right">玩家ID：</td>
                <td><input type="text"  name="payer_id" id="payer_id"  ></td>
                <td>
                    <button class="ui-btn ui-corner-all ui-btn-b"  id="btn_search">查询</button>
                </td>
            </tr>
        </table>
        </from>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="50%">拨入数量</th>
                <th width="50%">拨入日期</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr>
                    <td>{{$item->cnumber}}</td>
                    <td>{{$item->c_date}}</td>
                </tr>
            @endforeach
            @if(sizeof($List)==10)
                <tr>
                    <td colspan="2">
                        <a href="#" value="10" onclick="javascript:NextPage(this);" data-role="button">点击加载更多</a>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_search").click(function () {
                var obj_where = $("input").serializeArray();
                $.get('/CardQuery/NextPage/1/0',obj_where,function (data) {
                    $("#sum_num").html(0);
                    if(comm.is_null(data.List)){
                        var html = setHtml(data.List)
                        $("#table_list tbody").empty().append(html);
                        $("#sum_num").html(data.sum_num);
                        if(data.List.length>=10){
                            var btn_html = "<tr> <td colspan='2'> <a href='#' value='10' onclick='javascript:NextPage(this);' data-role='button'>点击加载更多</a> </td> </tr>";
                            $("#table_list tbody").append(btn_html)
                        }
                    }
                })
            });

        })
        function setHtml(obj) {
            var html="";
            for(var i = 0 ; i< obj.length;i++ ){
                html+="<tr><td>"+obj[i]["cnumber"]+"</td><td>" + obj[i]["c_date"] + "</td></tr>";
            }
            return html;
        }
        function NextPage(obj) {
            var offset = $(obj).attr('value');
            var obj_where = $("input").serializeArray();
            $.get('/CardQuery/NextPage/1/'+offset,obj_where,function (data) {
                if(comm.is_null(data.List)){
                    var html = setHtml(data.List)
                    $("#table_list").find('tr:last').before(html);
                    $(obj).attr('value',(parseInt(offset)+10));
                    if(data.List.length<10){
                        $(obj).remove();
                    }
                }
            })
        }
    </script>
@endsection