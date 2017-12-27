@extends('Layout.layout')
@section('content')
    微信订单查询&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <hr>
    <div style="width: 100%;font-size: 14px;" >
        <div style="width: 100%;padding:3px">
            <span> 订单日期： </span>
            <input type="date" name="start_date" id="start_date" data-role="none" style="width: 100px;">
            --
            <input type="date" name="end_date" id="end_date" data-role="none" style="width: 100px;">
        </div>
        <div style="width: 100%;padding:3px">
            <span> 购买类型： </span>
            <select name="type" id="type" data-role="none" style="width: 105px;">
                <option value="" >--请选择--</option>
                <option value="0" >代理购买</option>
                <option value="1" >玩家购买</option>
            </select>
        </div>
        <div style="width: 100%;padding:3px">
                <span> 玩 家  I D： </span>
                <input type="text"  placeholder="玩家ID"  name="userid" id="userid" data-role="none" style="width: 100px;">
                <button data-role="none" id="btn_search">查询订单</button>
            </div>
    </div>
    <hr>
    <div>共计房卡：<span id="card_sum">{{$card_sum}}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;共计金额：<span id="total_sum">{{$total_sum}}</span></div>
    <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="15%">玩家ID</th>
                <th width="19%">昵称</th>
                <th width="13%">金额</th>
                <th width="13%">数量</th>
                <th width="40%">日期</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr {{$item->type==0?"style=color:red;":""}}>
                    <td>{{$item->userid}}</td>
                    <td>
                       <img src="{{empty($item->head_img_url)?asset('/img/ui-default.jpg'):$item->head_img_url}}" style="border-radius:6px;" width="40" align="absmiddle" >{{$item->nickname}}
                    </td>
                    <td>{{$item->total}}</td>
                    <td>{{$item->cardnum}}</td>
                    <td>{{$item->create_time}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_search").click(function () {
                var obj_where = $("input").serializeArray();
                var type = new Object();
                type.name = 'type';
                type.value = $('#type').val();
                obj_where.push(type);
                $.get('/OrderSearch/select',obj_where,function (data) {
                    if(comm.is_null(data.List)){
                        var html = setHtml(data.List)
                        $("#table_list tbody").empty().append(html);
                        $("#card_sum").html(data.card_sum);
                        $("#total_sum").html(data.total_sum);
                    }else{
                        $("#table_list tbody").empty();
                        $("#card_sum").html(0);
                        $("#total_sum").html(0);
                    }
                })
            });
        })

        function setHtml(obj) {
            var html="其他";
            for(var i = 0 ; i< obj.length;i++ ){
                var head = comm.is_null(obj[i]["head_img_url"])?obj[i]["head_img_url"]:'/img/ui-default.jpg';
                var style = "";
                if(obj[i]['type']==0){
                    style="style='color:red;'";
                }
                html+="<tr "+style+">" +
                    "<td>"+obj[i]["userid"]+"</td>" +
                    "<td><img src='"+head+"' style='border-radius:6px;' width='40' align='absmiddle' >"+
                    obj[i]["nickname"]+ "</td>" +
                    "<td>" + obj[i]["total"] + "</td>" +
                    "<td>" + obj[i]['cardnum'] + "</td>" +
                    "<td>" + obj[i]["create_time"] + "</td></tr>";
            }
            return html;
        }
    </script>
@endsection