@extends('Layout.WeUiLayout')
@section('style')
@endsection
@section('content')
<div  class="weui-flex" style="background-color:#ffa500;">
    @if(!empty($User))
        <div class="weui-flex__item" style="text-align: center;padding-top: 25px;">
            <img src="{{$User->head_img_url}}" style="border-radius:50px;" width="100">
        </div>
        <div class="weui-flex__item" >
            <div style="padding-top: 5px;font-size: 1rem;font-weight: bold;">
                {{$User->nickname}}
            </div>
            <div style="padding-top: 5px;font-size: 0.8rem;font-weight: 400;">
                ID：{{$User->uid}}
            </div>
            <div style="padding-top: 5px;font-size: 0.8rem;font-weight: 400;">
                我的钻石：{{$User->roomcard}}
            </div>
            <div style="padding-top: 5px;font-size: 0.8rem;font-weight: 400;">
                我的金豆：{{$User->gold}}
            </div>
            <div style="padding-top: 5px;padding-bottom: 5px;font-size: 0.9rem;font-weight: 400;color:white;">
                <span style="border-radius:3px;background-color: #f36666;">{{$User->rname}}</span>
            </div>
        </div>
    @endif
</div>
<div  class="weui-flex" style="background-color: #f7f7fa;">
    @if($roleid==1)
        <div class="weui-flex__item" style="text-align: center;">
            <span style="font-size:  0.7rem;font-weight: bold;">下载人数：</span>
            <span style="font-size:  0.65rem;font-weight: bold;color:red;">{{$count_person}}</span>
        </div>
        <div class="weui-flex__item" style="text-align: center;">
            <span style="font-size: 0.7rem;font-weight: bold;">在线人数：</span>

            <span style="font-size:  0.65rem;font-weight: bold;color:red;">{{$today_person}}</span>
        </div>
    @else
        <div class="weui-flex__item" style="text-align: center;">
            <span style="font-size:  0.8rem;font-weight: bold;">我的返利：</span>

            <span style="font-size:  0.7rem;font-weight: bold;color:red;">{{$total_num}}</span>
        </div>
        <div class="weui-flex__item" style="text-align: center;">
            <span style="font-size: 0.8rem;font-weight: bold;">当月返利：</span>

            <span style="font-size:  0.7rem;font-weight: bold;color:red;">{{$month}}</span>
        </div>
    @endif

</div>
<div class="weui-grids">
    @if(!empty($Menus))
        @foreach($Menus as $menu)
            @if($menu->linkurl!='/Home' && $menu->linkurl!='/MyInfo')
                <a href="{{$menu->linkurl=="/BuyBubble"?$menu->linkurl."/index":$menu->linkurl}}" class="weui-grid js_grid">
                    <div class="weui-grid__icon">
                        <i class="fa {{$menu->icon}}" style="color: #04BE02;"></i>
                    </div>
                    <p class="weui-grid__label">
                        {{$menu->name}}
                    </p>
                </a>
            @endif
        @endforeach
    @endif
</div>
@endsection
@section('script')
@endsection

