<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentManageController extends Controller
{
    //
    public function delete(Request $request){
        try{
            $uid = isset($request['uid'])?$request['uid']:0;
            $user = Users::find($uid);
            if(empty($user)){
                return response()->json(['error'=>'ID错误，该代理不存在！']);
            }else{
                    $user->rid = 0;
                    $user->save();
                    DB::table('xx_user')->where('front_uid',$uid)->update(['front_uid'=>0]);
                    //CommClass::UpdateRole($uid,5);
                    return response()->json(['error'=>'']);
            }
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }

    }
    public function transfer($target,$source){
        try{
            $target_user = Users::find($target);
            $source_user = Users::find($source);
            if(empty($target_user) || empty($source_user)){
                return response()->json(['error'=>'ID错误，请仔细核对代理ID！']);
            }
            if($target_user->rid==5 || empty($target_user->rid) || $source_user->rid==5 || empty($source_user->rid)){
                return response()->json(['error'=>'目标ID或转移的ID是玩家，不能转移！']);
            }
            $source_user->front_uid = $target;
            $chief_up = 0 ;
            switch ($target_user->rid){
                case 1:
                    $source_user->chief_uid = $target;
                    $source_user->rid = 2;
                    break;
                case 2:
                    $source_user->chief_uid = $target_user->chief_uid;
                    $chief_up = $source;
                    $source_user->rid = 3;
                    break;
                case 3:
                    $source_user->chief_uid = $target;
                    $chief_up = $target;
                    $source_user->rid = 4;
                    break;
                case 4:
                    $source_user->chief_uid = $target_user->chief_uid;
                    $chief_up = $target_user->chief_uid;
                    $source_user->rid = 4;
                    break;
                default:
                    break;
            }
            $source_user->save();
            $this->Update_next($source,$chief_up);
            //CommClass::UpdateRole($source_user->uid,$source_user->rid);
            return response()->json(['error'=>'']);
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    private function Update_next($userid,$chief)
    {
        $select_uid_sql = <<<EOT
        select uid from xx_user where FIND_IN_SET(uid, queryChildrenAreaInfo($userid))  and uid <> $userid
EOT;
        $uid_list = DB::select($select_uid_sql);
        if(empty($uid_list)) return;
        $update_sql = <<<EOT
             update xx_user set chief_uid =$chief   where uid in (
EOT;
        foreach ($uid_list as $item) {
            $update_sql .= $item->uid . ",";
        }
        $update_sql = rtrim($update_sql, ',');
        $update_sql .= ')';
        DB::select($update_sql);
    }

    //代理申请
    public function save(Request $request){
        try{
            $uid = isset($request['uid'])?$request['uid']:"";
            $tel = isset($request['tel'])?$request['tel']:"";
            if(empty($uid))
                return response()->json(['error'=>'请输入游戏ID！']);
            if(empty($tel))
                return response()->json(['error'=>'请输入联系方式！']);
            $user = Users::find($uid);
            if(empty($user))
                return response()->json(['error'=>'您输入的游戏ID错误，请重新输入！']);
            DB::table('pp_agentsys_apply')->insert(['game_uid'=>$uid,'tel'=>$tel]);
            return response()->json(['error'=>'']);
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    //审核列表
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $menu_arr = CommClass::PagingData($page,$rows,"pp_agentsys_apply",""," create_date desc ");
        return response()->json($menu_arr);
    }
    //审核通过
    public function adopt(Request $request){
        try{
            $id = isset($request['id'])?$request['id']:"";
            $game_uid = isset($request['game_uid'])?$request['game_uid']:"";
            $tel = isset($request['tel'])?$request['tel']:"";
            DB::table('pp_agentsys_apply')->where('id',$id)->update(['state'=>1]);
            DB::table('xx_user')->where('uid',$game_uid)->update(['rid'=>2,'uphone'=>$tel]);
            return response()->json(['error'=>'']);
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }


    //代理列表
    public function getAgent(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        $where = ' rid = 2 ';
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $menu_arr = CommClass::PagingData($page,$rows,"xx_user" ,$where);
        return response()->json($menu_arr);
    }
}
