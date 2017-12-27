@extends('Layout.layout')
@section('content')
    <div class="ui-field-contain">
        <button onclick="javascript:window.location.href='/White/Set'">分数设置</button>
        <button onclick="javascript:window.location.href='/White/Record'">战绩统计</button>
        {{--<button onclick="javascript:window.location.href='/White/Tops'">战绩排行</button>--}}
    </div>
@endsection