@extends('Layout.WeUiLayout')
@section('content')
    <header style="text-align: center;padding-top: 10px;">
        <h3>商品编辑</h3>
    </header>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">商品平台：</label>
            </div>
            <div class="weui-cell__bd">
                <input type="hidden" id="msgid" value="{{empty($Mall)?"":$Mall->sid}}">
                <select class="weui-select"  id="type">
                    <option {{empty($Mall)?"selected":($Mall->type==0?'selected':'')}} value="0">游戏商城</option>
                    <option {{empty($Mall)?"":($Mall->type==1?'selected':'')}} value="1">后台商城</option>
                </select>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">商品类型：</label>
            </div>
            <div class="weui-cell__bd">
                <select class="weui-select"  id="sgive">
                    <option {{empty($Mall)?"selected":($Mall->sgive==0?'selected':'')}} value="0">砖石</option>
                    <option {{empty($Mall)?"":($Mall->sgive==1?'selected':'')}} value="1">房卡</option>
                </select>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">商品名称：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text"  id="scommodity" value="{{empty($Mall)?"":$Mall->scommodity}}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">价格：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="sprice" value="{{empty($Mall)?"":$Mall->sprice}}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">数量：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="number"  id="snumber" value="{{empty($Mall)?"":$Mall->snumber}}">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">商品描述：</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text"  id="sremarks" value="{{empty($Mall)?"":$Mall->sremarks}}">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="btn_save">保存</a>
        <a href="/ShoppingMall" class="weui-btn weui-btn_default weui-btn_loading">返回</a>
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
                R.sid = $('#sid').val();
                R.type = $('#type').val();
                R.sgive = $('#sgive').val();
                R.scommodity = $('#scommodity').val();
                R.sprice = $('#sprice').val();
                R.snumber = $('#snumber').val();
                R.sremarks = $('#sremarks').val();
                $.post('/ShoppingMall/save',{data:R},function(data){
                    if(data.msg==1){
                        location.href="/ShoppingMall";
                    }else{
                        $.alert(data.msg);
                    }
                })
            })
        });
    </script>
@endsection