<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyCardController extends Controller
{
    //
    public function index(){
        $user=Users::find(session('uid'));
        return view('BuyCard.Index',['card'=>$user->roomcard,'gold'=>$user->gold]);
    }

    //查询玩家信息
    public function  Search($uid){
            $payer = Users::find($uid);
            if(!empty($payer)){
                return response()->json(['user'=>$payer,'msg'=>'']);
            }else{
                return response()->json(['user'=>$payer,'msg'=>"玩家ID不存在，请重新输入！"]);
            }
    }

    //拨卡
    public function  PayerBuy(Request $request){
        try{
            $data = isset($request['data'])?$request['data']:"";
            $number = isset($data['card_number'])?$data['card_number']:0;
            $uid = isset($data['payer_id'])?$data['payer_id']:0;
            $sel_type = isset($data['sel_type'])?$data['sel_type']:0;
            if(empty($uid)){
                return response()->json(['msg' => '请输入玩家ID']);
            }
            if($number <= 0){
                return response()->json(['msg' => '赠送数量不能小于0！']);
            }
            if($uid == 1) {
                if($sel_type == 1){
                    DB::table('xx_user')->increment('roomcard',$number);
                }else if($sel_type == 2) {
                    DB::table('xx_user')->increment('gold',$number);
                }
            }else {
                if($sel_type == 1){
                    DB::table('xx_user')->where('uid',$uid)->increment('roomcard',$number);
                    //更新游戏的钻石数量
                    CommClass::UpGameSer($uid,'card');//玩家的钻石
                }else if($sel_type == 2) {
                    DB::table('xx_user')->where('uid',$uid)->increment('gold',$number);
                    //更新游戏的金币数量
                    CommClass::UpGameSer($uid,'coin');//玩家的金币
                }
            }
            return response()->json(['msg' => ""]);
        }catch (\Exception $e){
            return response()->json(['msg' => $e->getMessage()]);
        }

    }

    public function GiveRec(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $where = " csellid = ".session('uid');
        $orderby = ' ctradedate desc ';
        $order_arr = CommClass::PagingData($page,$rows,"view_trade",$where,$orderby);
        return response()->json($order_arr);
    }
}
