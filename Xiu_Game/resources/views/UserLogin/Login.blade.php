<!DOCTYPE html>
<html lang="zh_cn">
<head>
    <meta charset="UTF-8">
    <!-- 1、如果支持Google Chrome Frame：GCF，则使用GCF渲染；2、如果系统安装ie8或以上版本，则使用最高版本ie渲染；3、否则，这个设定可以忽略。 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <!-- 对视窗缩放等级进行限制，使其适应移动端屏幕大小 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 当把这个网页添加到主屏幕时的标题（仅限IOS） -->
    <meta name="apple-mobile-web-app-title" content="休休游戏管理系统">
    <!-- 添加到主屏幕后全屏显示 -->
    <meta name="apple-touch-fullscreen" content="yes" />
    <title>休休游戏管理系统</title>
    <link rel="stylesheet" href="{{ asset('/css/LoginStyle.css')}}">
</head>
<body>
<form id="slick-login" method="POST" action="/Login" >
    <input type="hidden"  name="_token" value="{{ csrf_token() }}">
    <div style="text-align: center;font-size: 26px;color:#fff;">
        <h2>休休游戏管理系统</h2>
    </div>
    <hr>
    <div style="margin-top: 30px;">
        @if(session('idmsg'))
            <span style="color:red;">
                {{ session('idmsg') }}
            </span>
        @endif
        <p style="color:red;" id="id_msg"></p>
        <input type="text"  id="id" name='id' class="placeholder" placeholder="ID">
    </div>
    <div style="margin-top: 30px;">
        @if(session('pwdmsg'))
            <span style="color:red;">
                {{ session('pwdmsg') }}
            </span>
        @endif
        <p style="color:red;" id="pwd_msg"></p>
        <input type="password"  id="pwd" name="pwd" class="placeholder" placeholder="密码">
    </div>
    <div style="margin-top: 30px;">
        <input type="button" class="btn_submit"  id="login" value="登录">
    </div>
    <hr>
    <div style="text-align: center;font-size: 16px;color:#fff;margin-top: 30px;">
        <p>&copy; <?php echo date('Y')?> - 休休网络技术有限公司</p>
    </div>
</form>
</body>
<script src="{{ asset('/js/jquery-1.12.4.min.js') }}"></script>
<script type="text/javascript">
$(function () {
    $("#login").click(function () {
        var ret=false;
        var id=$("#id").val();
        var pwd=$("#pwd").val();
        if(id=="" || typeof (id)=="undefined"){
            $("#id_msg").html("请输入ID！");
            ret=true;
        }else{$("#id_msg").val("");}
        if(pwd=="" || typeof (pwd)=="undefined"){
            $("#pwd_msg").html("请输入密码！");
            ret=true;
        }else{$("#pwd_msg").val("");}
        if(ret){
            return;
        }else{
            $("#slick-login").submit();
        }
    })
})
</script>
</html>