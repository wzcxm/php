<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Users;
use Illuminate\Http\Request;

class MyInfoController extends Controller
{
    //我的信息
    public function index(){
        return view('MyInfo.index',['user'=>Users::find(session('uid'))]);
    }

    //活动公告
    public function notice(){
        $Message = Message::where('mtype',2)->orderBy('msgid','desc')->first();
        return view('Notice.index',['Message'=>$Message]);
    }

    //我的推广码
    public function getQrCode(){
        try{
            $user = Users::find(session('uid'));
            $url = 'http://'.$_SERVER['HTTP_HOST'].'/download/'.session('uid');
            return view('MyInfo.qrcode',['user'=>$user,'url'=>$url]);
        }catch (\Exception $e){
            return "Error:".$e->getMessage();
        }
    }

    //返利统计
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $gid = isset($request['gid']) ? $request['gid']:"";
        $bid = isset($request['bid']) ? $request['bid']:"";
        $start_date = isset($request['start_date']) ? $request['start_date']:"";
        $end_date = isset($request['end_date']) ? $request['end_date']:"";
        $where = ' 1=1 ';
        if(!empty($gid)){
            $where .= ' and get_id ='.$gid;
        }
        if(!empty($bid)){
            $where .= ' and back_id ='.$bid;
        }
        if (!empty($start_date) || !empty($end_date)) {
            $where .= " and create_time between '";
            if (!empty($start_date)) {
                $where .= $start_date . "' and '";
            } else {
                $where .= date('Y-m-01', strtotime($end_date)) . "' and '";
            }
            if (!empty($end_date)) {
                $where .= $end_date . " 23:59:59'";
            } else {
                $where .= date('Y-m-t', strtotime($start_date)) . " 23:59:59'";
            }
        }
        $orderby = ' create_time desc ';
        $order_arr = CommClass::PagingData($page,$rows,"view_backcash",$where , $orderby);
        //增加合计行
        $sql = 'select * from view_backcash where '.$where;
        $data = DB::select($sql);
        $total = collect($data)->sum('backgold');
        $total_gold = collect($data)->sum('gold');
        $footer =[['g_head'=>'合计','gold'=>$total_gold,'backgold'=>$total]];
        $order_arr['footer'] = $footer;
        return response()->json($order_arr);
    }

}
