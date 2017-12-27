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
        $user=DB::table("view_user")->where('uid',session('uid'))->first();
        return view('BuyCard.Index',['gold'=>$user->roomcard]);
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

    public function  PayerBuy($uid,$number){
        try{
            $user_id = session('uid');
            $retMsg = "";
            if(empty($uid) || empty($number) || empty($user_id)){
                $retMsg = "充值失败，请检查是否输入正确！";
            }else if($number < 0){
                $retMsg = "充值数量不能小于0！";
            }else {
                $user = Users::find($user_id);
                if ($user->roomcard - $number < 0) {
                    $retMsg = "您的余额不足，不能给其他玩家充值！";
                }else{
                    //保存充卡信息
                    $arr = ['cbuyid' => $uid, 'csellid' => $user_id, 'cnumber' => $number, 'ctype' => 1];
                    CommClass::InsertCard($arr);
                }
            }
            return response()->json(['msg' => $retMsg]);
        }catch (\Exception $e){
            return response()->json(['msg' => $e->getMessage()]);
        }

    }

    public function querybuy($uid,$number){
        $palyer = Users::find($uid);
        return view('BuyCard.querybuy',['palyer'=>$palyer,'roomcard'=>$number]);
    }

    public function autocompleter(){
        try{
            $sql = 'select DISTINCT cbuyid from xx_sys_cardstrade where csellid='.session('uid').' limit 10';
            $uid_list =  DB::select($sql);
            $arry_id = [];
            if(sizeof($uid_list)>0){
                foreach ($uid_list as $item){
                    array_push($arry_id,['value'=>$item->cbuyid,'label'=>$item->cbuyid]);
                }
            }
            return $arry_id;
        }catch (\Exception $e){
            return "";
        }
    }
}
