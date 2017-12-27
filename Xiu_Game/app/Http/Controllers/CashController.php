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
    ///////购买房卡///////////
    public function  index(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {

        } else {
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            Session::put('popenid', $openid);
        }
        return redirect('/PlayerBuy/list');
    }

    public function buylist(){
        $List = ShoppingMall::where('type',0)->get();
        return view('BuyCard.palyerbuy',['List'=>$List]);
    }

    /*
     * 获取订单内容
     */
    public function buycard($sid,$gameid)
    {
        try {
            $player = Users::find($gameid);
            if(empty($player)){
                return response()->json(['Error' => "玩家不存在，游戏ID错误！",'orderno'=>0]);
            }else{
                $product = ShoppingMall::find($sid);
                //购卡数量
                $toltal_number = $product->snumber+$product->sgive;
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
                    'btype'=>1
                ]);
                //返回订单json
                return response()->json(['Param' => $Parameters,'orderno'=>$orderno]);
            }
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
            $input->SetOpenid(session('popenid'));
            $order = WxPayApi::unifiedOrder($input);
            $jsApiParameters = $tools->GetJsApiParameters($order);
            return $jsApiParameters;
        }catch (\Exception $e){
            //$log->ERROR($e->getMessage());
        }
    }

    public function notify(){
        try{
            $notifyback = new WxPayNotify();
            $notifyback->Handle(false);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /*
     * 付款成功后，更新玩家房卡，并返现
     */
    public function setCard($orderno){
        $logHandler = new CLogFileHandler($_SERVER['DOCUMENT_ROOT'] . "/logs/" . date('Y-m-d') . '.log');
        $log = Log::Init($logHandler, 15);
        try {
            $log->Info('wjgm:'.$orderno);
            //防止微信延迟到账，延迟10秒再查订单
            //sleep(10);
            $input = new WxPayOrderQuery();
            $input->SetOut_trade_no($orderno);
            $result = WxPayApi::orderQuery($input);
            if($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
                if($result["trade_state"] == "SUCCESS") {
                    $buycard = BuyCard::where('nonce', $orderno)->first();
                    if($buycard->status == 0) {
                        //保存充卡信息
                        $arr = ['cbuyid' => $buycard->userid, 'csellid' => 999, 'cnumber' => $buycard->cardnum, 'ctype' => 1];
                        CommClass::InsertCard($arr);
                        //更新订单状态
                        $buycard->status = 1;
                        $buycard->save();
                        return response()->json(['msg' => 1]);
                    }else{
                        return response()->json(['msg' => 1]);
                        //return response()->json(['msg' => 'The order has been completed']);
                    }
                }else{
                    $log->Error($result["trade_state"]);
                    return response()->json([ 'Error' => $result["trade_state"]]);}
            }else{
                $log->Error($result["err_code_des"]);
                return response()->json([ 'Error' => $result["err_code_des"]]);}
        }catch (\Exception $e) {
            $log->Error($e->getMessage());
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
            return view('CashBuy.buysearch',["role"=>session('roleid')]);
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
        if(session('roleid')==1){
            if(!empty($uid)){
                $where .= ' and userid ='.$uid;
            }
        }else{
            $where .= ' and userid ='.session('uid');
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
