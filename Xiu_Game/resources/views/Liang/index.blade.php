@extends('Layout.layout')
@section('content')
    靓号设置
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table width="100%">
            <tr>
                <td width="80%"><input type="text" placeholder="请输入靓号" id="ling_no"   ></td>
                <td width="20%">
                    <button class="ui-btn ui-icon-search ui-btn-icon-left ui-corner-all ui-btn-b"  id="btn_search">查询</button>
                </td>
            </tr>
        </table>
        <div data-role="controlgroup" data-type="horizontal" data-theme="b">
            <a href="/Liang/create"  class="ui-btn">添加靓号</a>
            <a href="#" class="ui-btn" id="btn_bind">绑定靓号</a>
            <a href="#" onclick="javascript:window.location.reload();" class="ui-btn"  >刷新</a>
        </div>
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="10%"></th>
                <th width="30%">靓号</th>
                <th width="30%">状态</th>
                <th width="30%">绑定ID</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr>
                    <td><input data-role="none" type="checkbox" value="{{$item->id}}" /></td>
                    <td>{{$item->liang}}</td>
                    <td>{{$item->state==1?'已绑定':'未绑定'}}</td>
                    <td>{{$item->olduid}}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="4">
                        @if(sizeof($List)>=10)
                            <a href="#" value="10" onclick="javascript:nextpage(this);" data-role="button">点击加载更多</a>
                        @endif
                    </td>
                </tr>
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

            //查询
            $("#btn_search").click(function () {
                var number=$("#ling_no").val();
                if(comm.is_null(number)) {
                    $.get('/Liang/Search/'+number,function (data) {
                        if(comm.is_null(data.liang)){
                            var html='';
                            for(var i=0 ;i<data.liang.length;i++){
                                html+="<tr>";
                                html+="<td><input type='checkbox'  value='"+data.liang[i]['id']+"' /></td>";
                                html+="<td>"+data.liang[i]['liang']+"</td>";
                                html+="<td>"+(data.liang[i]['state']==1?'已绑定':'未绑定')+"</td>";
                                html+="<td>"+data.liang[i]['olduid']+"</td>";
                                html+="</tr>";
                            }
                            $("#table_list tbody").empty().append(html);
                        }
                    })
                }
            })
            //绑定靓号
            $("#btn_bind").click(function () {
                var ckd = $("#table_list input[type=checkbox]:checked");
                if(!comm.is_null(ckd) || ckd.length<=0){
                    return;
                }
                window.location.href='/Liang/'+ckd[0].value+'/edit';
            })
        })
        function nextpage(obj) {
            var offset= $(obj).attr('value');
            $.get('/Liang/GetNextPage/'+offset,function (data) {
                if(comm.is_null(data.NextList)){
                    var html='';
                    for(var i=0 ;i<data.NextList.length;i++){
                        html+="<tr>";
                        html+="<td><input type='checkbox' value='"+data.NextList[i]['id']+"' /></td>";
                        html+="<td>"+data.NextList[i]['liang']+"</td>";
                        html+="<td>"+(data.NextList[i]['state']==1?'已绑定':'未绑定')+"</td>";
                        html+="<td>"+data.NextList[i]['olduid']+"</td>";
                        html+="</tr>";
                    }
                    $("#table_list").find('tr:last').before(html);
                    $(obj).attr('value',(parseInt(offset)+10));
                    if(data.NextList.length<10){
                        $(obj).remove();
                    }
                }
            })
        }
    </script>
@endsection