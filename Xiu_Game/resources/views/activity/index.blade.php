@extends('Layout.WeUiLayout')
@section('style')
    <style>
        .head_bg{
            width:100%;
            height:20%;
            display:-webkit-flex;
            -webkit-flex-direction:column;
            background:url(/img/gift/head.png);
            background-size:100% 100%;
            font-size: 1.5rem;
            color:white;
            text-align: center;
        }
        .ipt_css{
            font-weight: lighter;
            font-size: 0.8rem;
            border:2px solid #c1c1c1;
            outline:none;
            cursor: pointer;
            padding: 5px 10px 5px 10px;
            border-radius:5px;
            width: 30%;
            color: #ff7200;
            vertical-align: unset;
        }
        .close_btn{
            background-color: #a4a19f;
            border: 0;
            border-radius:5px;
            font-size: 0.8rem;
            padding: 5px 10px 5px 10px;
            color: white;
        }
        .open_btn{
            background-color: #2dbba7;
            border: 0;
            border-radius:5px;
            font-size: 0.8rem;
            padding: 5px 10px 5px 10px;
            color: white;
        }
    </style>
@endsection
@section('content')
    <div  class="weui-flex head_bg" >
        <div style="margin-top: 8%;">活动设置</div>
    </div>
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    充值赠送：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="ipt_css" type="number" id="proportion" value="{{empty($proportion)?"0":$proportion}}" style="width: 50%;">%
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <input type="hidden" id="isopen" value="{{$isopen}}">
            <button  class="{{$isopen==0?'open_btn':'close_btn'}}" style="width: 80%;margin-left: 10%;"
                     onclick="open_close(this,'isopen')">{{$isopen==0?'开启':'关闭'}}
            </button>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function open_close(btn,control) {
            if($("#"+control).val() == 1){
                $("#"+control).val(0);
                $(btn).html('开启');
                $(btn).removeClass().addClass('open_btn');
                save_set();
            }else{
                $("#"+control).val(1);
                $(btn).html('关闭');
                $(btn).removeClass().addClass('close_btn');
                save_set();
            }
        }
        function save_set() {
            var obj = new Object();
            obj.proportion = $("#proportion").val();
            obj.isopen = $("#isopen").val();
            $.post('/Activity/save',{data:obj},function(data){
                if(data.message==''){
                    console.log('ok')
                }else{
                    console.log(data.message);
                }
            });
        }
    </script>
@endsection