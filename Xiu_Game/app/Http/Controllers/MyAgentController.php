<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;

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
        $menu_arr = CommClass::PagingData($page,$rows,"xx_user" ,$where);
        return response()->json($menu_arr);
    }

    //我的玩家
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
