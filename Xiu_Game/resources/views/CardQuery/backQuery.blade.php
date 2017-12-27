@extends('Layout.layout')
@section('content')
    返卡统计&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="border-radius: 4px;background-color:#c35858;color:white;font-weight: 400;">&nbsp;共计房卡：<b id="sum_num">{{$sum_num}}</b>&nbsp;</span>
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <from id="tab_where">
            <table width="100%" >
                <tr>
                    <td width="40%" align="right">开始日期：</td>
                    <td width="35%"><input type="date"  name="start_date" id="start_date"   ></td>
                    <td width="25%"></td>
                </tr>
                <tr>
                    <td  align="right">结束日期：</td>
                    <td><input type="date"  name="end_date" id="end_date"   ></td>
                    <td ></td>
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
        <ul data-role="listview" data-inset="true" id="list_view">
            <li data-role="list-divider" name="title" style="font-size: 16px;">返卡列表</li>
            @foreach($List as $item)
                <li data-icon="false">
                    <a href="#" >
                        <img src="{{empty($item->csell_head)?asset('/img/ui-default.jpg'):$item->csell_head}}" class="ui-corner-all " >
                        <table width="100%" >
                            <tr>
                                <td width="50%">ID:{{$item->csellid}}</td>
                                <td align="center">{{$item->cnumber}}</td>
                            </tr>
                            <tr >
                                <td>{{empty($item->csell_name)?"玩家":$item->csell_name}}</td>
                                <td align="center">{{$item->c_date}}</td>
                            </tr>
                        </table>
                    </a>
                </li>
            @endforeach
            @if(sizeof($List)==10)
                <li data-icon="false">
                    <a href="#" value="10" style="text-align: center;" onclick="javascript:NextPage(this);" >点击加载更多</a>
                </li>
            @endif
        </ul>

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
                $.get('/CardQuery/NextPage/4/0',obj_where,function (data) {
                    $("#list_view [name='title']").nextAll().remove();
                    $("#sum_num").html(0);
                    if(comm.is_null(data.List)){
                        var html = setHtml(data.List)
                        $("#list_view [name='title']").after(html);
                        $("#sum_num").html(data.sum_num);
                        if(data.List.length>=10){
                            var btn_html = "<li data-icon='false' class='ui-last-child'> " +
                                "<a href='#' class='ui-btn' " +
                                "value='10' style='text-align: center;' " +
                                "onclick='javascript:NextPage(this);' >" +
                                "点击加载更多</a></li>";
                            $("#list_view").append(btn_html)
                        }
                    }
                })
            });

        })
        function setHtml(obj) {
            var html="";
            for(var i = 0 ; i< obj.length;i++ ){
                var head_url=comm.is_null(obj[i]['csell_head'])?obj[i]['csell_head']:'./img/ui-default.jpg';
                var rname= comm.is_null(obj[i]['csell_name'])?obj[i]['csell_name']:'玩家';
                html+="<li data-icon='false' class='ui-li-has-thumb'>"+
                    "<a href='#'  class='ui-btn'>"+
                    "<img src='"+head_url+"' class='ui-corner-all' >"+
                    "<table width='100%' ><tr>"+
                    "<td width='50%'>ID:"+obj[i]["csellid"]+"</td><td align='center'>"+obj[i]["cnumber"]+"</td></tr>"+
                    "<tr ><td>"+rname+"</td><td align='center'>"+obj[i]["c_date"]+"</td>"+
                    "</tr></table></a> </li>";
            }
            return html;
        }
        function NextPage(obj) {
            var offset = $(obj).attr('value');
            var obj_where = $("input").serializeArray();
            $.get('/CardQuery/NextPage/4/'+offset,obj_where,function (data) {
                if(comm.is_null(data.List)){
                    var html = setHtml(data.List)
                    $("#list_view").find('li:last').before(html);
                    $(obj).attr('value',(parseInt(offset)+10));
                    if(data.List.length<10){
                        $("#list_view").find('li:last').remove();
                    }
                }
            })
        }
    </script>
@endsection