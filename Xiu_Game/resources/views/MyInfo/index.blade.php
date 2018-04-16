@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h2>我的信息</h2>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label"></label>
            </div>
            <div class="weui-cell__bd">
                <img src='{{empty($user->head_img_url)?asset('/img/ui-default.jpg'):$user->head_img_url}}' style='border-radius:80px;' width='50%' >
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;text-align: right;">
                <label class="weui-label">我的昵称</label>
            </div>
            <div class="weui-cell__bd" >
                {{$user->nickname}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;text-align: right;">
                <label class="weui-label">游戏&nbsp;&nbsp;&nbsp;ID</label>
            </div>
            <div class="weui-cell__bd">
                {{$user->uid}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;text-align: right;">
                <label class="weui-label">我的钻石</label>
            </div>
            <div class="weui-cell__bd">
                {{$user->roomcard}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;text-align: right;">
                <label class="weui-label">我的金豆</label>
            </div>
            <div class="weui-cell__bd">
                {{$user->gold}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;text-align: right;">
                <label class="weui-label">我的返利</label>
            </div>
            <div class="weui-cell__bd">
                {{$user->money}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;text-align: right;">
                <label class="weui-label">加入日期</label>
            </div>
            <div class="weui-cell__bd">
                {{$user->create_time}}
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection