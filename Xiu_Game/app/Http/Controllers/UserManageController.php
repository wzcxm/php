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
                if(session("roleid")==4 && $user->super_aisle != session("aisle")){
                    return response()->json(['error'=>'该玩家不在您名下！']);
                }else if(session("roleid")==3 && $user->chief_aisle != session("aisle")){
                    return response()->json(['error'=>'该玩家不在您名下！']);
                }else{
                    $ret_arr = [];
                    $ret_arr["head_url"] = $user->head_img_url;
                    $ret_arr["nick"] = $user->nickname;
                    if($user->rid==4){
                        $ret_arr["rname"] = "特级代理";
                    }else if($user->rid==4){
                        $ret_arr["rname"] = "总代";
                    }else if($user->rid==6){
                        $ret_arr["rname"] = "渠道代理";
                    }else if($user->rid==2){
                        $ret_arr["rname"] = "代理";
                    }else{
                        $ret_arr["rname"] = "玩家";
                    }
                    $ret_arr["front_uid"] = $user->front_uid;
                    $ret_arr["chief_uid"] = $user->chief_uid;
                    $ret_arr["create_time"] = $user->create_time;
                    $tea_list = DB::select("select GROUP_CONCAT(tea_id) as tea_list from xx_sys_tea where uid=".$uid);
                    $ret_arr["teaids"] =$tea_list[0]->tea_list;
                    $start = date('Y-m-01');
                    $end =  date('Y-m-t 23:59:59', strtotime($start));
                    $all = DB::select('call search_grade_all('.$uid.')');
                    $month = DB::select("call search_grade_month(".$uid.",'".$start."','".$end."')");
                    $ret_arr["all"] =empty($all[0]->all_grade)?0:$all[0]->all_grade;
                    $ret_arr["month"] = empty($month[0]->all_grade)?0:$month[0]->all_grade;
                    if($user->rid==4){
                        $count_play = DB::table('xx_user')->where('super_aisle',$user->aisle)->get();
                        $ret_arr["count_agent"] = $count_play->where('rid','<>',5)->count();
                        $ret_arr["count_player"] =$count_play->where('rid',5)->count();
                    }else if($user->rid==3){
                        $count_play = DB::table('xx_user')->where('chief_aisle',$user->aisle)->get();
                        $ret_arr["count_agent"] = $count_play->where('rid','<>',5)->count();
                        $ret_arr["count_player"] =$count_play->where('rid',5)->count();
                    }else if($user->rid==6){
                        $count_play = DB::table('xx_user')->where('vip_aisle',$user->aisle)->get();
                        $ret_arr["count_agent"] = $count_play->where('rid','<>',5)->count();
                        $ret_arr["count_player"] =$count_play->where('rid',5)->count();
                    }else if($user->rid==2){
                        $count_play = DB::table('xx_user')->where('front_uid',$uid)->orWhere('chief_uid',$uid)->get();
                        $ret_arr["count_agent"] = $count_play->where('front_uid',$uid)->where('rid',2)->count();
                        $ret_arr["count_player"] =$count_play->where('chief_uid',$uid)->where('rid',5)->count();
                    }else{
                        $ret_arr["count_agent"] = 0;
                        $ret_arr["count_player"] =0;
                    }
                    return response()->json($ret_arr);
                }
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
