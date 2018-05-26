@extends('Layout.WeUiLayout')
@section('style')
    <style>
        .head_bg{
            background:url(/img/other/myinfo.png);
            background-size:100% 100%;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div  class = "head_bg" >
        <img src='{{empty($user->head_img_url)?asset('/img/ui-default.jpg'):$user->head_img_url}}'
             style='border-radius:80px;margin-top:15%;border:4px solid #9ccabf;' width='30%' >
    </div>
    <div class="weui-cells_form">
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
                <label class="weui-label">我的提成</label>
            </div>
            <div class="weui-cell__bd">
                {{$user->money}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;text-align: right;">
                <label class="weui-label">提成比例</label>
            </div>
            <div class="weui-cell__bd">
                @if($user->rid == 3 || $user->rid == 4)
                    旗下充值：{{$back_agent}}%；玩家充值：{{$back_play}}%；
                @else
                    下级充值：{{$back_agent}}%；下下级充值：{{$back_agent_front}}%；玩家充值：{{$back_play}}%；
                @endif
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