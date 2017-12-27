<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManageController extends Controller
{
    //加载首页数据
    public function Index(){
        $users=DB::table('view_user')->select('uid','head_img_url','rname','free')->offset(0)->limit(10)->get();
        return view('UserManage.index',['List'=>$users]);
    }
    //获取下一页数据
    public function GetNextPageList($offset){
        try{
            $list=DB::table('view_user')->select('uid','head_img_url','rname','free')->offset($offset)->limit(10)->get();
            return response()->json(['NextList'=>$list]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
    //获取下级用户
    public function GetChildList($uid){
        try{
            $list=DB::table('view_user')->where('front_uid',$uid)->select('uid','head_img_url','rname','free')->get();
            return response()->json(['ChildList'=>$list]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
    //授权下级
    public function BePower($uid){
        return view('UserManage.bepower',['user'=>DB::table('view_user')->where('uid',$uid)->first()]);
    }
    //保存授权
    public function SavePower(Request $request){
        try{
           $user=Users::find($request['uid']);
           $user->rid=$request['rname'];
            if($request['rname']==2){
                $user->front_uid = session('uid');
                $user->chief_uid = session('uid');
            }else if($request['rname']==5){
                $user->front_uid = null;
                $user->chief_uid = null;
            }else
                {
                if(!empty($request['front_uid'])) {
                    $user->front_uid = $request['front_uid'];
                    $front = Users::find($request['front_uid']);
                    if($front->rid==3){
                        $user->chief_uid = $request['front_uid'];
                    }else {
                        $user->chief_uid = $front->chief_uid;
                    }
                }
            }
           $user->save();
            //CommClass::UpdateRole($user->uid,$user->rid);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
    //冻结账号/解冻账号
    public function UpdateState($uid){
        try{
            $ids=explode(',',$uid);
            foreach ($ids as $id){
                $user = Users::find($id);
                if($user->freeze==1){
                    $user->freeze=2;
                }
                else{
                    $user->freeze=1;
                }
                $user->save();
            }
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
    //查询
    public function  Search($uid){
        try{
            return response()->json(['User'=>DB::table('view_user')->where('uid',$uid)->first()]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
    //从属查询
    public function  TheSearch($uid){
        try{
            $sql="select  t.uid,t.head_img_url, r.rname ,
                           (case when t.freeze = 1 then '冻结' else '启用' end) as free
                            from xx_user t
                            left join xx_sys_role r on r.roleid = t.rid
                            where FIND_IN_SET(t.uid, queryChildrenAreaInfoUp(:uid)) ORDER BY t.rid ASC;";
            $users=DB::select($sql,['uid'=>$uid]);
            return response()->json(['User'=>$users]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

    public function GetInfo($uid){
        try{
            return response()->json(['userInfo'=>Users::find($uid)]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }

    }


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
}
