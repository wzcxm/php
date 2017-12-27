<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link rel="stylesheet" href="{{ asset('/js/sui-mobile/css/sm.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/js/sui-mobile/css/sm-extend.min.css') }}">
</head>
<body>
<div style="height:20%;background:url(/img/title.png);background-size:100% 100%;" id="title"></div>
<div align="center" style="height:10%;background:url(/img/btu.png);background-size:100% 100%;" id="btu">
    <a href="https://fir.im/ek6y">
        <img class="img-rounded " width="150" src="{{asset('/img/anniu.png')}}"  />
    </a>
</div>
<div style="height:47%;background:url(/img/body.png);background-size:100% 100%;">
    <div style="height: 15px;"></div>
    <table width="100%" height="100%"  >
        <tr height="33%">
            <td width="33%"></td>
            <td width="33%" align="center">
                <div style="width: 56px;height: 80px">
                    @if(!empty($Users[0]))
                        <img class="img-rounded" style="border:3px solid #B8860B;"  width="55" src="{{$Users[0]->head_img_url}}" />
                        <br><span style="font-weight: bold;">{{mb_strlen($Users[0]->nickname,'UTF8')>4?mb_substr($Users[0]->nickname,0,4,'UTF8'):$Users[0]->nickname}}</span>
                    @endif
                </div>
            </td>
            <td width="33%"></td>
        </tr>
        <tr height="33%">
            <td align="center">
                <div style="width: 56px;height: 80px">
                    @if(!empty($Users[1]))
                        <img class="img-rounded" style="border:3px solid #B8860B;" width="55"  src="{{$Users[1]->head_img_url}}" />
                        <br><span style="font-weight: bold;">{{mb_strlen($Users[1]->nickname,'UTF8')>4?mb_substr($Users[1]->nickname,0,4,'UTF8'):$Users[1]->nickname}}</span>
                    @endif
                </div>
            </td>
            <td align="center">
                <div style="width: 56px;height: 80px">
                    <img class="img-rounded " width="55" src="/img/fangwei.png"/>
                </div>
            </td>
            <td align="center">
                <div style="width: 56px;height: 80px">
                    @if(!empty($Users[2]))
                        <img class="img-rounded" style="border:3px solid #B8860B;" width="55" src="{{$Users[2]->head_img_url}}"/>
                        <br><span style="font-weight: bold;">{{mb_strlen($Users[2]->nickname,'UTF8')>4?mb_substr($Users[2]->nickname,0,4,'UTF8'):$Users[2]->nickname}}</span>
                    @endif
                </div>
            </td>
        </tr>
        <tr >
            <td></td>
            <td align="center">
                @if(!empty($Users[3]))
                    <img class="img-rounded" style="border:3px solid #B8860B;" width="55" src="{{$Users[3]->head_img_url}}" />
                    <br><span style="font-weight: bold;">{{mb_strlen($Users[3]->nickname,'UTF8')>4?mb_substr($Users[3]->nickname,0,4,'UTF8'):$Users[3]->nickname}}</span>
                @endif
            </td>
            <td></td>
        </tr>
    </table>
</div>
<div style="height:23%;background:url(/img/footr.png);background-size:100% 100%;" id="footr"></div>
</body>
</html>
