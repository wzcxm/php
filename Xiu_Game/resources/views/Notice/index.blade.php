@extends('Layout.WeUiLayout')
@section('content')
    <div style="text-align: center;">
        <h2>活动公告</h2>
    </div>

    <hr>
    <div style="width: 100%;text-align: right;left:-10px;bottom:5px;">
        公告日期：{{date('Y-m-t', strtotime($Message->create_date))}}
    </div>
    <div style="width: 100%;" escape="false">

        {!! $Message->mcontent !!}
    </div>

@endsection
@section('script')
@endsection