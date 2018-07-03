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


    public function getPlayer(Request $request){
        try{
            $uid = isset($request['uid'])?$request['uid']:0;
            $user = Users::find($uid);
            if(!empty($user)){
                return response()->json(['player'=>$user]);
            }else {
                return response()->json(['error'=>'玩家不存在！']);
            }
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);
        }

    }

    public function getAgentData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        if(!empty(session('aisle')) ){
            $where = ' rid <> 5 and super_aisle = '.session('aisle');
        }else{
            $where = '1=2';
        }

        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $player_arr = CommClass::PagingData($page,$rows,"v_user_isgift" ,$where,"create_time desc");
        return response()->json($player_arr);
    }


    public function saveGift(Request $request){
        try{
            $uid = isset($request['uid'])?$request['uid']:0;
            $type = isset($request['type'])?$request['type']:1;
            if($type==1){//开启
                DB::table('xx_sys_isgift')->insert(['uid'=>$uid]);
            }else if($type==2){ //关闭
                DB::table('xx_sys_isgift')->where('uid',$uid)->delete();
            }
            return response()->json(['error'=>""]);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
}
