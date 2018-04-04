<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManageController extends Controller
{
    //玩家列表
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        $front = isset($request['front_uid']) ? intval($request['front_uid']) : 0;
        $where = ' 1 = 1 ';
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        if(!empty($front)){
            $where .= " and front_uid = ".$front;
        }
        $player_arr = CommClass::PagingData($page,$rows,"v_user_list" ,$where,"create_time desc");
        return response()->json($player_arr);
    }

    //拉黑玩家
    public function Lock(Request $request){
        try{
            $ids = isset($request['data'])?$request['data']:"";
            $id_list = explode(',',$ids);
            DB::table('xx_user')->whereIn('uid',$id_list)->update(['ustate'=>-1]);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
    //解封
    public function  Unlock(Request $request){
        try{
            $ids = isset($request['data'])?$request['data']:"";
            $id_list = explode(',',$ids);
            DB::table('xx_user')->whereIn('uid',$id_list)->update(['ustate'=>0]);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

    //设置代理
    public function setRole(Request $request){
        try{
            $ids = isset($request['data'])?$request['data']:"";
            $rid = isset($request['rid'])?$request['rid']:5;
            $id_list = explode(',',$ids);
            DB::table('xx_user')->whereIn('uid',$id_list)->update(['rid'=>$rid]);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

}
