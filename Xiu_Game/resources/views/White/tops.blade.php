@extends('Layout.layout')
@section('content')

    战绩排行榜
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%" >
            <tr>
                <td width="25%" align="right">游戏类型：</td>
                <td  width="50%"><select name="game_type" id="game_type" data-mini="true">
                        <option value="nxmj" >宁乡麻将</option>
                    </select>
                </td>
                <td>
                    <select name="orderby" id="orderby" data-mini="true">
                        <option value="asc" >升序</option>
                        <option value="desc" >降序</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div data-role="controlgroup" data-type="horizontal" data-theme="b">
                        <a href="#" class="ui-btn" id="btn_search">查询排名</a>
                        <a href="#" onclick="javascript:window.location.href='/White'" class="ui-btn"  >返回上级</a>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th>名次</th>
                <th>ID</th>
                <th>积分</th>
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
                var gtype = $("#game_type").val();
                var orderby = $("#orderby").val();
                $.get('/White/Tops/Search/'+gtype+'/'+orderby,function (data) {
                    if(comm.is_null(data.List)){
                        var html = setHtml(data.List);
                        $("#table_list tbody").empty().append(html);
                    }
                })
            });
        })
        function setHtml(obj) {
            var html;
            for(var i=0;i<obj.length;i++){
                html+="<tr>";
                html+="<td>"+(i+1)+"</td>";
                html+="<td>"+obj[i].player_id+"</td>";
                html+="<td>"+obj[i].sum_score+"</td>";
                html+="</tr>";
            }
            return html;
        }
    </script>
@endsection