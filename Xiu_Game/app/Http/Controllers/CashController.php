<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\BuyCard;
use App\Models\ShoppingMall;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Wechat\example\JsApiPay;
use App\Wechat\lib\WxPayUnifiedOrder;
use App\Wechat\lib\WxPayConfig;
use App\Wechat\lib\WxPayApi;
use App\Wechat\example\log;
use App\Wechat\example\CLogFileHandler;
use App\Wechat\lib\WxPayNotify;
use App\Wechat\lib\WxPayOrderQuery;

class CashController extends Controller
{
    ///////充值页面///////////
    public function  index($at_id = 0 ){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            return "<h1>请在微信客户端打开链接</h1>";
        } else {
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            Session::put('wx_openid', $openid);
            $unionid = $tools->data['unionid']; //'o0xnJw7NVU-WtMPt6y9WW6PzwIlo';//
            $player = Users::where('unionid', $unionid)->first();
            //如果公众号的openid不同，修改wxopenid
            if(!empty($player) && $player->wxopenid != $openid){
                DB::table('xx_user')->where('unionid', $unionid)->update(['wxopenid'=>$openid]);
            }
            $mallList = ShoppingMall::where([['type',1],['sgive',0]])->get();
            return view('BuyCard.palyerbuy',['mallList'=>$mallList,'player'=>$player,'at_id'=>$at_id]);
        }
    }
    /*
     * 购卡记录
     */
    public function getRechargeData(Request $request)
    {
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $start_date = isset($request['start_date']) ? $request['start_date']:"";
        $end_date = isset($request['end_date']) ? $request['end_date']:"";
        $where = ' status = 1 and userid = '.session('uid');
        if (!empty($start_date) || !empty($end_date)) {
            $where .= " and create_time between '";
            if (!empty($start_date)) {
                $where .= $start_date . "' and '";
            } else {
                $where .= date('Y-m-01', strtotime($end_date)) . "' and '";
            }
            if (!empty($end_date)) {
                $where .= $end_date . " 23:59:59'";
            } else {
                $where .= date('Y-m-t', strtotime($start_date)) . " 23:59:59'";
            }
        }
        $orderby = ' create_time desc ';
        $order_arr = CommClass::PagingData($page,$rows,"xx_wx_buycard",$where , $orderby);
        //增加合计行
        $sql = 'select * from xx_wx_buycard where '.$where;
        $data = DB::select($sql);
        $total = collect($data)->sum('total');
        $cardnum = collect($data)->sum('cardnum');
        $footer =[['create_time'=>'合计','total'=>$total,'cardnum'=>$cardnum]];
        $order_arr['footer'] = $footer;
        return response()->json($order_arr);
    }

    //获取昵称
    public function getnick($uid){
        try{
            return response()->json(['user' => Users::find($uid)]);
        }catch (\Exception $e){
            return "";
        }
    }

    /*
     * 获取订单内容
     */
    public function buycard(Request $request)
    {
        try {
            $data = isset($request['data'])?$request['data']:"";
            $gameid = isset($data['playerid'])?$data['playerid']:0;//玩家ID
            $sid = isset($data['sid'])?$data['sid']:0;    //商品ID
            $front = isset($data['front'])?$data['front']:0; //推荐人ID
            $player = Users::find($gameid);
            if(empty($player)){
                return response()->json(['Error' => "游戏ID错误！",'orderno'=>0]);
            }
            if(!empty($front)){
                $front_mode = Users::find($front);
                if(empty($front_mode) || $front_mode->rid != 2 || $front == $gameid){
                    return response()->json(['Error' => "推荐人ID无效，请重新填写！",'orderno'=>0]);
                }
            }

            $product = ShoppingMall::find($sid);
            //购卡数量
            $toltal_number = $product->snumber;
            //总金额
            $toltal_fee = $product->sprice*100;
            //订单号
            $orderno = WxPayConfig::MCHID . date("YmdHis");
            //生成订单
            $Parameters = $this->setPay($toltal_fee,$orderno);
            //保存订单号到数据库
            DB::table('xx_wx_buycard')->insert([
                'userid' => $gameid,
                'cardnum' => $toltal_number,
                'total' => $toltal_fee/100,
                'nonce' => $orderno,
                'front' => $front,
                'isfirst'=>$product->isfirst
            ]);
            //返回订单json
            return response()->json(['Param' => $Parameters,'orderno'=>$orderno]);

        }catch (\Exception $e){
            return response()->json(['Error' => $e->getMessage(),'orderno'=>0]);
        }
    }
    /*
     * 生成订单
     */
    private function setPay($total_fee,$orderno)
    {
        //$logHandler = new CLogFileHandler($_SERVER['DOCUMENT_ROOT'] . "/logs/" . date('Y-m-d') . '.log');
        //$log = Log::Init($logHandler, 15);
        try {
            //①、获取用户openid
            $tools = new JsApiPay();
            //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody("休休游戏-充值");
            $input->SetAttach("休休科技");
            $input->SetOut_trade_no($orderno);
            $input->SetTotal_fee($total_fee);
            $input->SetTime_start(date("YmdHis"));
            //$input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("WXG");
            $input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/api/player/notify");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid(session('wx_openid'));
            $order = WxPayApi::unifiedOrder($input);
            $jsApiParameters = $tools->GetJsApiParameters($order);
            return $jsApiParameters;
        }catch (\Exception $e){
            //$log->ERROR($e->getMessage());
        }
    }

    //回调方法
    public function notify(){
        try{
            $notifyback = new WxPayNotify();
            $notifyback->Handle(false);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*
     * 支付失败，删除订单
     */
    public function delNo(Request $request){

        try {
            $no = isset($request['no'])?$request['no']:0;
            if (!empty($no)) {
                BuyCard::where('nonce', $no)->delete();
            }
            return response()->json(['Error' => ""]);
        }catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }
    ///////---END--///////////


    ///////购卡查询///////////
    /*
     * 查询列表
     */
    public function search(){
        try{
            return view('CashBuy.buysearch');
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? $request['uid']:"";
        $start_date = isset($request['start_date']) ? $request['start_date']:"";
        $end_date = isset($request['end_date']) ? $request['end_date']:"";
        $where = ' status = 1 ';
        if(!empty($uid)){
            $where .= ' and userid ='.$uid;
        }
        if (!empty($start_date) || !empty($end_date)) {
            $where .= " and create_time between '";
            if (!empty($start_date)) {
                $where .= $start_date . "' and '";
            } else {
                $where .= date('Y-m-01', strtotime($end_date)) . "' and '";
            }
            if (!empty($end_date)) {
                $where .= $end_date . " 23:59:59'";
            } else {
                $where .= date('Y-m-t', strtotime($start_date)) . " 23:59:59'";
            }
        }
        $orderby = ' create_time desc ';
        $order_arr = CommClass::PagingData($page,$rows,"view_buycard",$where , $orderby);
        //增加合计行
        $sql = 'select * from view_buycard where '.$where;
        $data = DB::select($sql);
        $card = collect($data)->sum('cardnum');
        $total = collect($data)->sum('total');
        $footer =[['head_img_url'=>'合计','cardnum'=>$card,'total'=>$total]];
        $order_arr['footer'] = $footer;
        return response()->json($order_arr);
    }


    /*
     * 查询购卡记录
     */
    public function NextPage(Request $request, $offset){
        try{
            $start = $request['start_date'];
            $end = $request['end_date'];
            if (empty($start) && empty($end)) {
                $start = date('Y-m-01');
                $end =  date('Y-m-t', strtotime($start)) . " 23:59:59";
            } else {
                if (empty($start)) {
                    $start = date('Y-m-01', strtotime($end));
                }
                if (empty($end)) {
                    $end = date('Y-m-t', strtotime($start)) . " 23:59:59";
                }else{
                    $end = $end.' 23:59:59';
                }
            }
            $arr = [["status","=",1],["btype","=",0]];
            if(empty($request['userid'])){
                Array_push($arr,["userid","=",session('uid')]);
            }else{
                if(session('roleid')==1){
                    Array_push($arr,["userid","=",$request['userid']]);
                }
            }
            $List = BuyCard::where($arr)
                            ->whereBetween('create_time', [$start, $end])
                            ->offset($offset)
                            ->limit(10)
                            ->get();
            $gold = collect($List)->sum("cardnum");
            return response()->json(["gold"=>$gold,"List"=>$List]);
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    ///////---END--///////////


    ///////微信订单查询///////////
    public function WeChatOrder(Request $request){
        try{
            $list = DB::table('view_buycard')->where('status',1)->whereBetween('create_time',[date('Y-m-01'),date('Y-m-t 23:59:59')])->get();
            $card_sum = collect($list)->sum('cardnum');
            $total_sum = collect($list)->sum('total');
            return view('CashBuy.wechatorder',['card_sum'=>$card_sum,'total_sum'=>$total_sum,'List'=>$list]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    public function WcoSarch(Request $request){
        try{
            $start_date = isset($request['start_date']) ? $request['start_date']:"";
            $end_date = isset($request['end_date']) ? $request['end_date']:"";
            $userid = isset($request['userid']) ? $request['userid']:"";
            $type = isset($request['type']) ? $request['type']:"";
            $where = [['status',1]];
            if(isset($type)){
                Array_push($where,['btype',$type]);
            }
            if(!empty($userid)){
                Array_push($where,['userid',$userid]);
            }
            if(empty($start_date) && empty($end_date)){
                $list = DB::table('view_buycard')->where($where)->get();
            }else{
                $list = DB::table('view_buycard')->where($where)->whereBetween('create_time',
                    [empty($start_date)?date('Y-m-01', strtotime($end_date)):$start_date,
                     empty($end_date)?date('Y-m-t', strtotime($start_date)) . " 23:59:59":$end_date])->get();
            }
            $card_sum = collect($list)->sum('cardnum');
            $total_sum = collect($list)->sum('total');
            return response()->json(['card_sum'=>$card_sum,'total_sum'=>$total_sum,'List'=>$list]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    /// ---END--///////////
}
