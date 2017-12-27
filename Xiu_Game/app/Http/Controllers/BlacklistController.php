<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlacklistController extends Controller
{
    //
    public function index(){
        $users=DB::table('xx_user')->where('ustate','<',0)->select('uid','head_img_url','ustate')->offset(0)->limit(10)->get();
        return view('Black.index',['List'=>$users]);
    }

    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $where = " ustate < 0 ";
        $msg_arr = CommClass::PagingData($page,$rows,"xx_user",$where);
        return response()->json($msg_arr);
    }

    public function save(Request $request){
        try{
            DB::table("xx_user")->where('uid',$request["uid"])->update(['ustate'=>-1]);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }
    public function Search($uid){
        try{
            return response()->json(['User'=>DB::table('xx_user')->where([['uid','=',$uid],['ustate','<',0]])->first()]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

    //解封
    public function  Unlock($ids){
        try{
            $id_list = explode(',',$ids);
            DB::table('xx_user')->whereIn('uid',$id_list)->update(['ustate'=>0]);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

    //获取下一页数据
    public function GetNextPageList($offset){
        try{
            $list = DB::table('xx_user')->where('ustate','<',0)->offset($offset)->limit(10)->get();
            return response()->json(['NextList'=>$list]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
}
