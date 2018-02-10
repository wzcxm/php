@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>游戏运营情况</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">上月消耗钻石：</div>
            <div class="weui-cell__bd">
                {{empty($sys['front_zs'])?0:$sys['front_zs']}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">当月消耗钻石：</div>
            <div class="weui-cell__bd">
                {{empty($sys['now_zs'])?0:$sys['now_zs']}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">上月消耗金豆：</div>
            <div class="weui-cell__bd">
                {{empty($sys['front_jd'])?0:$sys['front_jd']}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">当月消耗金豆：</div>
            <div class="weui-cell__bd">
                {{empty($sys['now_jd'])?0:$sys['now_jd']}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">总消耗钻石：</div>
            <div class="weui-cell__bd">
                {{empty($sys['total_zs'])?0:$sys['total_zs']}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">总消耗金豆：</div>
            <div class="weui-cell__bd">
                {{empty($sys['total_jd'])?0:$sys['total_jd']}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">注册玩家数量：</div>
            <div class="weui-cell__bd">
                {{empty($sys['count_person'])?0:$sys['count_person']}}
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd" style="width: 40%;">实时在线人数：</div>
            <div class="weui-cell__bd">
                {{empty($sys['online_person'])?0:$sys['online_person']}}
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection