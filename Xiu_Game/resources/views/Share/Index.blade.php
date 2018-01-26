<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>休休游戏--分享</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}">
</head>
<body>
<div class="bg">
    <div class="height-div"></div>
    <div class="title" align="center">宁乡麻将&nbsp;&nbsp;房号:{{$room}}</div>
    <div class="height-div"></div>
    <div class="title-div" >
        {{--<span>{{$strList}}</span>--}}
        {{--<span>金童玉女</span>--}}
        {{--<span>步步高</span>--}}
        {{--<span>三同</span>--}}
        {{--<span>一枝花</span>--}}
        {{--<span>一点红</span>--}}
        @foreach($strList as $item)
            <span>{{$item}}</span>
        @endforeach
    </div>
    <div align="center" >
        {{--<img class="img-rounded " style="opacity:0.2" width="150"  src="{{asset('/img/logo.png')}}"/>--}}
    </div>
    <div align="center">
        <div style="width: 56px;height: 80px">
        @if(!empty($Users[0]))
        <img class="img-rounded div-img"  width="55" src="{{$Users[0]->head_img_url}}" />

        <br><p>{{mb_strlen($Users[0]->nickname,'UTF8')>4?mb_substr($Users[0]->nickname,0,4,'UTF8'):$Users[0]->nickname}}</p>
        @endif
        </div>
    </div>
    <div class="height-div"></div>
    <div align="center">
        <div align="center" class="div-flat">
            <div style="width: 56px;height: 80px">
            @if(!empty($Users[1]))
            <img class="img-rounded div-img" width="55"  src="{{$Users[1]->head_img_url}}" />
            <br><p>{{$Users[1]->nickname}}</p>
            @endif
            </div>
        </div>
        <div align="center" class="div-flat">
            <div style="width: 56px;height: 80px">
            <img class="img-rounded " width="55" src="{{asset('/img/fangwei.png')}}"/>
            </div>
        </div>
        <div align="center" class="div-flat">
            <div style="width: 56px;height: 80px">
            @if(!empty($Users[2]))
            <img class="img-rounded div-img" width="55" src="{{$Users[2]->head_img_url}}"/>
            <br><p>{{$Users[2]->nickname}}</p>
            @endif
            </div>
        </div>
    </div>
    <div class="height-div"></div>
    <div align="center">
        <div style="width: 56px;height: 80px">
        @if(!empty($Users[3]))
        <img class="img-rounded div-img" width="55" src="{{$Users[3]->head_img_url}}" />
        <br><p>{{$Users[3]->nickname}}</p>
        @endif
        </div>
    </div>
    <div style="height:15px;"></div>
    {{--<div align="center">--}}
        {{--<a href="https://a.mlinks.cc/A0ZF?roomid={{$room}}">--}}
            {{--<img class="img-rounded " width="150" src="{{asset('/img/jiaru.png')}}"  />--}}
        {{--</a>--}}
    {{--</div>--}}
    <div align="center">
        <a href="http://fir.im/4bnf">
        <img class="img-rounded " width="150" src="{{asset('/img/pinggxiaz.png')}}"  />
        </a>
    </div>
    <div style="height:15px;"></div>
    <div align="center">
        <a href="http://fir.im/6svl">
        <img class="img-rounded " width="150" src="{{asset('/img/anzhuoxiaz.png')}}" />
        </a>
    </div>
    <div style="height:15px;"></div>
</div>
</body>
</html>
