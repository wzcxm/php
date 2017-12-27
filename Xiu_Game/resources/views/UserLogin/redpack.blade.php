<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>领取红包</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
</head>
<body onload="closeWin();">
<div>
    <input type="hidden" id="msg" value="{{$msg}}">
</div>
</body>
<script>
    function closeWin() {
        var msg = document.getElementById("msg").value;
        if(msg == null || msg == ""){
            document.addEventListener('WeixinJSBridgeReady', function(){ WeixinJSBridge.call('closeWindow'); }, false);
        }else {
            alert(msg);
            document.addEventListener('WeixinJSBridgeReady', function(){ WeixinJSBridge.call('closeWindow'); }, false);
        }
    }
</script>
</html>