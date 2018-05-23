@extends('Layout.WeUiLayout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/common.css')}}?v=201805231">
@endsection
@section('content')
    <div  class="weui-flex head_css" >
        <div style="margin-top: 8%;">更新微信</div>
    </div>
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 30%;text-align: right;vertical-align:middle;">
                    原微信：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <img  id="old_head_url" src="{{empty($old_head)?"":$old_head}}" style="border-radius:15px;" width="35" align="absmiddle" >
                    <span style="color: #545454" id="old_nick">{{empty($old_nick)?"":$old_nick}}</span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 30%;text-align: right;vertical-align:middle;">
                    当前微信：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <img  id="new_head_url" src="{{empty($new_head)?"":$new_head}}" style="border-radius:15px;" width="35" align="absmiddle" >
                    <span style="color: #545454" id="new_nick">{{empty($new_nick)?"":$new_nick}}</span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <button class="btn_css" style="width: 80%;margin-left: 10%;"  id="btn_query">确认更新</button>
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

            $("#btn_query").click(function () {
                $.confirm("<a style='color:red;'>您是否确定绑定当前微信,如果当前微信已有游戏数据，将会被覆盖，且不可恢复，请慎重操作！</a>", function() {
                    //点击确认后的回调函数

                    $.post("/UpdateWx/replace",function (reslut) {
                        if(comm.is_null(reslut.error)){
                            $.toptip(reslut.error,4000,'error');
                        }
                        else {
                            $.toast('更新成功！',function () {
                                window.location.reload();
                            });
                        }
                    })
                }, function() {

                });
            });
        });

    </script>
@endsection