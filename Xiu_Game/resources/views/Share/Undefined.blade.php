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
    <div align="center" style="padding-top: 15px;">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide" ><img src="/img/1.png" style="width:50%;height:100%;"></div>
                <div class="swiper-slide"><img src="/img/2.png" style="width:50%;height:100%;"></div>
                <div class="swiper-slide"><img src="/img/3.png" style="width:50%;height:100%;"></div>
                <div class="swiper-slide"><img src="/img/4.png" style="width:50%;height:100%;"></div>
                <div class="swiper-slide"><img src="/img/5.png" style="width:50%;height:100%;"></div>
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>
            <!-- 如果需要导航按钮 -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</div>
<div style="height:23%;background:url(/img/footr.png);background-size:100% 100%;" id="footr"></div>
<script type='text/javascript' src='{{ asset('/js/sui-mobile/js/zepto.min.js') }}' charset='utf-8'></script>
<script type='text/javascript' src='{{ asset('/js/sui-mobile/js/sm.min.js') }}' charset='utf-8'></script>
<script type='text/javascript' src='{{ asset('/js/sui-mobile/js/sm-extend.min.js') }}' charset='utf-8'></script>
<script type="text/javascript">
    var config = {
        autoplay: 4000,//可选选项，自动滑动
        // 如果需要分页器
        pagination: '.swiper-pagination',
        // 如果需要前进后退按钮
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev'
    };
    $(function() {
        $(".swiper-container").swiper(config);
        var h = $(window).height() - $("#title").height() - $("#btu").height()- $("#footr").height()
        $(".swiper-container").css('height',(h-15).toString()+"px");
    });
</script>
</body>
</html>
