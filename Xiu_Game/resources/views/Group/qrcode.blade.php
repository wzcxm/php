@extends('Layout.layout')
@section('content')
    <div style="width: 100%;text-align: center;margin:20px auto;font-size: 16px;font-weight: bold;">微信扫描屏幕二维码，即可加入群</div>
    <hr>
    <div style="width: 100%;text-align: center;" >
        {{--{!! QrCode::encoding('UTF-8')->size(360)->generate($Url); !!}--}}
        <img  src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(360)->encoding('UTF-8')->generate($Url)) !!} ">
    </div>
@endsection