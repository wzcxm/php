<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\BuyCard;
use App\Models\ShoppingMall;
use App\Models\Users;
use App\Wechat\lib\WxPayRedPack;
use Illuminate\Http\Request;
use App\Wechat\example\JsApiPay;
use App\Wechat\lib\WxPayUnifiedOrder;
use App\Wechat\lib\WxPayConfig;
use App\Wechat\lib\WxPayApi;
use App\Wechat\example\log;
use App\Wechat\example\CLogFileHandler;
use App\Wechat\lib\WxPayNotify;
use App\Wechat\lib\WxPayOrderQuery;
use Illuminate\Support\Facades\DB;

class BuyBubbleController extends Controller
{
    public $number;

    //
    public function  index(){
        $List = ShoppingMall::where('type',1)->get();
        return view('BuyCard.buycards',['List'=>$List]);
    }

    public function buycard($sid){
        try {
            $product = ShoppingMall::find($sid);
            //购卡数量
            $toltal_number = $product->snumber;
            //总金额
            $toltal_fee = $product->sprice*100;
            //订单号
            $orderno = WxPayConfig::MCHID . date("YmdHis");
            //生成订单
            $Parameters = $this->setPay($toltal_fee,session('openid'),$orderno);
            //保存订单号到数据库
            DB::table('xx_wx_buycard')->insert([
                'userid' => session('uid'),
                'cardnum' => $toltal_number,
                'Total' => $toltal_fee/100,
                'Nonce' => $orderno
            ]);
            //返回订单json
            return response()->json(['Param' => $Parameters,'orderno'=>$orderno]);
        }catch (\Exception $e){
            return response()->json(['Error' => $e->getMessage(),'orderno'=>0]);
        }
    }

    public function setCard($orderno){
        try {
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
                        //返现
                        CommClass::BackCash($buycard->userid, $buycard->total);
                        //更新订单状态
                        $buycard->status = 1;
                        $buycard->save();
                        return response()->json(['msg' => 1]);
                    }else{
                        return response()->json(['msg' => 1]);
                        //return response()->json(['Error' => 'The order has been completed']);
                    }
                }else{ return response()->json([ 'Error' => $result["trade_state"]]);}
            }else{return response()->json([ 'Error' => $result["err_code_des"]]);}
        }catch (\Exception $e) {
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    private function setPay($total_fee,$openid,$orderno)
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
            $input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/api/notify");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openid);
            $order = WxPayApi::unifiedOrder($input);
            $jsApiParameters = $tools->GetJsApiParameters($order);
            return $jsApiParameters;
        }catch (\Exception $e){
            //$log->ERROR($e->getMessage());
        }
    }

    public function notify(){
        $notifyback = new WxPayNotify();
        $notifyback->Handle(false);
    }

    //用户取消或支付失败，删除订单信息
    public function delOrderNo(Request $request){
        try {
            $no = isset($request["NO"]) ? $request["NO"] : "";
            if (!empty($no)) {
                BuyCard::where('nonce', $no)->delete();
            }
            return response()->json(['Error' => ""]);
        }catch (\Exception $e){
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    //获取opendid
    public function GetRedPack(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            //return view('UserLogin.Login');
        } else {
            $ret_msg="";
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            $unionid = $tools->data['unionid'];
            $game_user = Users::where('unionid', $unionid)->first();
            if(empty($game_user)){
                $ret_msg = "您还未下载游戏，请下载并登陆游戏后，再领取红包！";
            }else{
                if($game_user->isred==0){
                    $retData =  $this->RedPack($openid);
                    if($retData["state"]==0){
                        $ret_msg = $retData["msg"];
                    }else{
                        DB::table("pp_user")->where('unionid', $unionid)->update(["isred"=>1]);
                    }
                }else{
                    $ret_msg = "您已领取红包，不能重复领取！";
                }
            }
            return view('UserLogin.redpack',['msg'=>$ret_msg]);
        }
    }

    //发红包
    public function RedPack($openid){
        try{
            $total = rand(108,168);
            $input = new WxPayRedPack();
            $input->SetMch_Billno(WxPayConfig::MCHID . date("YmdHis"));
            $input->SetSend_name("休休科技有限公司");
            $input->SetRe_openid($openid);
            $input->SetTotal_amount($total);
            $input->SetTotal_num(1);
            $input->SetWishing("休休游戏推广红包！");
            $input->SetAct_name("游戏推广活动");
            $input->SetRemark("休休游戏推广红包 ");

            $result = WxPayApi::redPack($input);
            $result = $input->FromXml($result);
            if($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
                return ["state"=>1,"msg"=>""];
            }else{
                return ["state"=>0,"msg"=>$result["err_code"]."|".$result["err_code_des"]];
            }
        }catch (\Exception $e){
            return ["state"=>0,"msg"=>$e->getMessage()];
        }
    }

}
