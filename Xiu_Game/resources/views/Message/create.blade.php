@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>编辑信息</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">信息类型：</label>
            </div>
            <div class="weui-cell__bd">
                <input type="hidden" id="msgid" value="{{empty($msg)?"":$msg->msgid}}">
                <select class="weui-select"  id="mtype">
                    <option {{empty($msg)?"selected":($msg->mtype==2?'selected':'')}} value="2">活动公告</option>
                    <option {{empty($msg)?"":($msg->mtype==1?'selected':'')}} value="1">游戏公告</option>
                    <option {{empty($msg)?"":($msg->mtype==3?'selected':'')}} value="3">紧急通知</option>
                </select>
            </div>
        </div>
        <div class="weui-cells__title">信息内容：</div>
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <textarea class="weui-textarea" placeholder="请输入信息内容" id="mcontent" rows="5">
                    {{empty($msg)?"":$msg->mcontent}}
                </textarea>
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">保存</a>
        <a href="/Message" class="weui-btn weui-btn_default weui-btn_loading">返回</a>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#btn_save").click(function () {
                var R = new Object();
                R.msgid = $('#msgid').val();
                R.mtype = $('#mtype').val();
                R.mcontent = $('#mcontent').val();
                $.post('/Message/save',{data:R},function(data){
                    if(data.msg==1){
                        location.href="/Message";
                    }else{
                        $.alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection