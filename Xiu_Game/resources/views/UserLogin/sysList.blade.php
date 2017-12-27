<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>泡泡游戏管理系统</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="stylesheet" href="{{ asset('/css/theme-classic.css')}}">
    <link rel="stylesheet" href="{{ asset('/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css')}}">
    <script src="{{asset('/js/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js')}}"></script>
</head>
<body>
<div data-role="page"  data-theme="d" >
    <div data-role="main" class="ui-content"  >
        <button onclick="javascript:window.location.href='/index';">泡泡游戏</button>
        <button onclick="ret_url();">泡泡宁乡麻将</button>
        <input type="hidden" id="openid" value="{{$openid}}">
        <input type="hidden" id="unionid" value="{{$unionid}}">
    </div>
</div>
</body>
<script>
    function ret_url(){
        var openid = document.getElementById('openid').value;
        var unionid = document.getElementById('unionid').value;
        window.location.href='http://agent.csppyx.com/Wechat/'+openid+'/'+unionid;
    }
</script>
</html>