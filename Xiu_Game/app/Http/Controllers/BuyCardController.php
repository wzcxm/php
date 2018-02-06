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
            $user_id = session('uid');
            $retMsg = "";
            if(empty($uid) || empty($number) || empty($user_id)){
                $retMsg = "失败，请检查是否输入正确！";
            }else if($number < 0){
                $retMsg = "数量不能小于0！";
            }else {
                $user = Users::find($user_id);
                if($sel_type == 1){
                    if ($user->roomcard - $number < 0) {
                        $retMsg = "您的钻石不足！";
                    }else{
                        //保存充卡信息
                        $arr = ['cbuyid' => $uid, 'csellid' => $user_id, 'cnumber' => $number,'buytype'=>2];
                         CommClass::InsertCard($arr);
                        //更新游戏的房卡数量
                        CommClass::UpGameSer($uid,'card');//玩家的卡
                        CommClass::UpGameSer($user_id,'card');//我的卡
                    }
                }else if($sel_type == 2) {
                    if ($user->gold - $number < 0) {
                        $retMsg = "您的金豆不足！";
                    }else{
                        //保存充卡信息
                        $arr = ['cbuyid' => $uid, 'csellid' => $user_id, 'cnumber' => $number, 'ctype' => 2,'buytype'=>2];
                        CommClass::InsertCard($arr);
                        //更新游戏的金币数量
                        CommClass::UpGameSer($uid,'coin');//玩家的卡
                        CommClass::UpGameSer($user_id,'coin');//我的卡
                    }

                }
            }
            return response()->json(['msg' => $retMsg]);
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
