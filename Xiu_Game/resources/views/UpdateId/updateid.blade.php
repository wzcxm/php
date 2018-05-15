@extends('Layout.EasyUiLayOut')
@section('easyui_style')
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
        .btn_css{
            background-color: #2dbba7;
            border: 0;
            border-radius:5px;
            font-size: 0.8rem;
            padding: 5px 10px 5px 10px;
            color: white;
            margin-bottom: 11px;
            margin-top: 10px;
        }
    </style>
@endsection
@section('easyui_content')
    <div  class="weui-flex head_bg" >
        <div style="margin-top: 8%;">修改ID</div>
    </div>
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    玩家原ID：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="ipt_css" type="number" id="old_uid" style="width: 50%;">
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    玩家新ID：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="ipt_css" type="number" id="new_uid" style="width: 50%;">
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    牌馆原ID：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="ipt_css" type="number" id="old_teaid" style="width: 50%;">
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    牌馆新ID：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="ipt_css" type="number" id="new_teaid" style="width: 50%;">
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <button class="btn_css" style="width: 80%;margin-left: 10%;"  id="btn_update">修改</button>
        </div>
    </div>
@endsection
@section('easyui_script')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#btn_update").click(function () {
                var R = new Object();
                R.old_uid = $("#old_uid").val();
                R.new_uid = $('#new_uid').val();
                R.old_teaid = $("#old_teaid").val();
                R.new_teaid = $('#new_teaid').val();
                if(comm.is_null(R.old_uid) && !comm.is_null(R.new_uid)){
                        return;
                }
                if(comm.is_null(R.old_teaid) && !comm.is_null(R.new_teaid)){
                        return;
                }
                $.post("/UpdateId/update",{data:R},function (reslut) {
                    if(comm.is_null(reslut.Error)){
                        $.alert(reslut.Error);
                    }
                    else {
                        $.alert('修改成功！',function () {
                            window.location.reload();
                        });
                    }
                })
            });
        });

    </script>
@endsection