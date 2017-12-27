@extends('Layout.layout')
@section('content')

    战绩查询
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <form id="record_where"  >
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
        </form>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="20%">ID</th>
                <th width="30%">昵称</th>
                <th >总分</th>
            </tr>
            </thead>
            <tbody>

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
                var obj = $("#record_where").serializeArray();
                $.get('/White/RecordSearch',obj,function (data) {
                    if(comm.is_null(data.List)){
                        var html = setHtml(data.List);
                        $("#table_list tbody").empty().append(html);
                    }
                })
            });
        });
        function setHtml(obj) {
            var html;
            for(var i=0;i<obj.length;i++){
                html+="<tr>";
                html+="<td>"+obj[i].player_id+"</td>";
                html+="<td>"+obj[i].player_name+"</td>";
                html+="<td>"+obj[i].count_score+"</td>";
                html+="</tr>";
            }
            return html;
        }

    </script>
@endsection