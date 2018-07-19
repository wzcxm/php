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
        .search_css{
            background-color: #2dbba7;
            border: 0;
            border-radius:5px;
            font-size: 0.8rem;
            padding: 5px 10px 5px 10px;
            color: white;
        }
    </style>
@endsection
@section('easyui_content')
    <div  class="weui-flex head_bg" >
        <div style="margin-top: 8%;">资料查询</div>
    </div>
    <div class="weui-cells_form">
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    ID：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <input class="ipt_css" type="number" id="old_uid" style="width: 50%;">&nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="search_css" id="btn_search">查询</button>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    昵称：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <img  id="head_url" src="" style="border-radius:6px;" width="30" align="absmiddle" >
                    <span style="color: #545454" id="nick"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    级别：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="rname"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    填写上级ID：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="front_uid"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    邀请人ID：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="chief_uid"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    注册日期：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="create_time"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    总业绩：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="all"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    当月业绩：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="month"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    旗下代理：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="count_agent"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    旗下玩家：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="count_player"></span>
                </div>
            </div>
        </div>
        <div class="weui-cell">
            <div style="width:100%;text-align: center;font-size: 0.8rem;">
                <div style="float: left;width: 35%;text-align: right;">
                    名下牌馆：
                </div>
                <div style="float: right;width: 60%;text-align: left;">
                    <span style="color: #545454" id="teaids"></span>
                </div>
            </div>
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
            $("#btn_search").click(function () {
                var payer_id = $("#old_uid").val();
                if(!comm.is_null(payer_id)){
                    $.alert("请输入玩家ID");
                    return;
                }
                $.post('/SearchPlayer/getPlayer',{uid:payer_id},function (data) {
                    if(comm.is_null(data.error)){
                        $.alert(data.error);
                        $("#head_url").attr('src',"");
                        $("#nick").html("");
                        $("#front_uid").html("");
                        $("#chief_uid").html("");
                        $("#create_time").html("");
                        $("#teaids").html("");
                        $("#rname").html("");
                        $("#all").html("");
                        $("#month").html("");
                        $("#count_agent").html("");
                        $("#count_player").html("");
                    } else {
                        var player = data;
                        if(comm.is_null(player['head_url'])){
                            $("#head_url").attr('src',player['head_url']);
                        }
                        $("#nick").html(player['nick']);
                        $("#front_uid").html(player['front_uid']);
                        $("#chief_uid").html(player['chief_uid']);
                        $("#create_time").html(player['create_time']);
                        $("#teaids").html(player['teaids']);
                        $("#rname").html(player['rname']);
                        $("#all").html(player['all']);
                        $("#month").html(player['month']);
                        $("#count_agent").html(player['count_agent']);
                        $("#count_player").html(player['count_player']);
                    }
                });
            });
        });

    </script>
@endsection