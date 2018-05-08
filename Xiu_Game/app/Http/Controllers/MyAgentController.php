<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyAgentController extends Controller
{

    //我的代理
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        $where = ' rid = 2  and front_uid = '.session('uid');
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $orderby = ' create_time desc ';
        $menu_arr = CommClass::PagingData($page,$rows,"xx_user" ,$where,$orderby);
        return response()->json($menu_arr);
    }

    public function myPlayer(){
        $sql = ' select * from xx_user where rid = 5  and chief_uid = '.session('uid');
        $data = DB::select($sql);
        $total = collect($data)->count();
        return view('MyAgent.MyService',['total'=>$total]);
    }

    //我的玩家
    public function getPlayer(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        $where = ' rid = 5  and chief_uid = '.session('uid');
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $orderby = ' create_time desc ';
        $sql = " select * from xx_user where ".$where.' order by '.$orderby;
        $result= DB::select($sql);
        $total = collect($result)->count();
        $menu_arr = ['total'=>$total,'rows'=>$result];//CommClass::PagingData($page,$rows,"xx_user" ,$where,$orderby);
        return response()->json($menu_arr);
    }
}
