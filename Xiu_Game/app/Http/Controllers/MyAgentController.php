<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MyAgentController extends Controller
{
    ///我的客服
    public function  IndexService(){
        $front=session('uid');
        $users=DB::table('view_user')->where('front_uid',$front)->select('uid','head_img_url','rname','uphone','create_time')->get();
        return view('MyAgent.MyService',['List'=>$users]);
    }
    //获取下级用户
    public function GetNextInfo($uid){
        try{
            $list=DB::table('view_user')->where('front_uid',$uid)->select('uid','head_img_url','rname','uphone','create_time')->get();
            return response()->json(['List'=>$list]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }
    //返回上级用户
    public function GetFrontInfo($uid){
        try{
            $front = Users::find($uid);
            $list=DB::table('view_user')->where('front_uid',$front->front_uid)->select('uid','head_img_url','rname','uphone','create_time')->get();
            return response()->json(['List'=>$list,'front_id'=>$front->front_uid]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    //查询
    public function Search($uid){
        try{

            $id=session('uid');
            $sql = "select uid,rname,uphone,create_time,head_img_url from (select * from view_user where FIND_IN_SET(uid, queryChildrenAreaInfoUp(:uid))) t
                    where FIND_IN_SET(t.uid, queryChildrenAreaInfo(:id)) and t.uid<>:tid ORDER BY rid ASC";
            $list = DB::select($sql,['uid'=>$uid,'id'=>$id,'tid'=>$id]);
            return response()->json(['List'=>$list]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    ///我的代理
    public function IndexAgent(){
        $front = session('uid');
        $users = DB::table('view_user')->where('front_uid',$front)->select('uid','head_img_url','rname','uphone','create_time')->get();
        return view('MyAgent.MyAgent',['List'=>$users]);
    }
    //代替授权
    public function create(){
        //
        return view('MyAgent.CreateAgent');
    }
    //保存授权的代理
    public function save($param){
            try{
                parse_str($param, $params);
                $msg = "1";
                $role = session('roleid');
                $user = Users::find($params['uid']);
                if(empty($user)){
                    $msg="游戏ID不正确，请重新输入！";
                }else{
                    if($user->rid!=null && $user->rid!=5){
                        $msg = "该玩家已经是代理，不能重复添加！";
                    }else{
                        $user->uphone=$params['uphone'];
                        $user->wechat=empty($params['wechat'])?"":$params['wechat'];
                        $user->front_uid=session('uid');
                        if($role==3){//总代
                            $user->chief_uid = session('uid');
                        }else{
                            $user->chief_uid = session('chief_uid');
                        }
                        if($role==2){//客服
                            $user->rid = 3;
                        }else{
                            $user->rid =4;
//                            $agentfirst = CommClass::GetParameter("agentfirst");
//                            if($user->gold >= $agentfirst){
//                                $user->roleid =4;
//                            }else{
//                                $user->roleid = 6;//代理(未充值)状态，达到最低消费后自动变为代理
//                            }
                        }
                        $user->save();

                        //CommClass::UpdateRole($user->uid,$user->roleid);
                    }
                }
                return response()->json(['msg' => $msg]);
            }catch (\Exception $e){
                return response()->json(['msg'=>$e->getMessage()]);
            }
    }

    //代替授权，保存
    public function repsave(Request $request){
        try{
            $msg = "1";
            if(CommClass::Is_Next($request['front'],session('uid'))){
                $user = Users::find($request['uid']);
                if(empty($user)){
                    $msg="游戏ID不正确，请重新输入！";
                }else{
                    if($user->rid!=null && $user->rid!=5){
                        $msg = "该玩家已经是代理，不能重复添加！";
                    }else{
                        $user->uphone=$request['uphone'];
                        $user->wechat=empty($request['wechat'])?"":$request['wechat'];
                        $user->front_uid=$request['front'];
                        $user->chief_uid = session('uid');
                        $user->rid =4;
                        $user->save();
                        //CommClass::UpdateRole($user->uid,$user->roleid);
                    }
                }
            }else{$msg="推荐人不是您的下级代理，不能代替授权！";}
            return response()->json(['msg' => $msg]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }



    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        $where = ' rid = 2  and front_uid = '.session('uid');
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $menu_arr = CommClass::PagingData($page,$rows,"xx_user" ,$where);
        return response()->json($menu_arr);
    }

    public function getPlayer(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        $where = ' rid = 5  and front_uid = '.session('uid');
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $menu_arr = CommClass::PagingData($page,$rows,"xx_user" ,$where);
        return response()->json($menu_arr);
    }
}
