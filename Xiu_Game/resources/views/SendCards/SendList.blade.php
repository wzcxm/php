@extends('Layout.layout')
@section('content')
    总送卡数量：{{$SumCards}}
    <hr>
    <div id="Alert"></div>
    <div class="ui-field-contain">
        <table id="table_list" class="bordered">
            <thead>
            <tr>
                <th width="20%">ID</th>
                <th width="15%">级别</th>
                <th width="15%">对象</th>
                <th width="15%">人数</th>
                <th width="15%">数量</th>
                <th width="20%">送卡数</th>
            </tr>
            </thead>
            <tbody>
            @foreach($List as $item)
                <tr>
                    <td>{{$item->uid}}</td>
                    <td>{{$item->r_level}}</td>
                    <td>{{$item->obj}}</td>
                    <td>{{$item->speople}}</td>
                    <td>{{$item->sbubble}}</td>
                    <td>{{$item->sumgold}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection