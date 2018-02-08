<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
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
            $user_head = $this->getWxHeadForBase64($user->head_img_url);
            $url = 'http://'.$_SERVER['HTTP_HOST'].'/download/'.session('uid');
            return view('MyInfo.qrcode',['user'=>$user,'url'=>$url,'head'=>$user_head]);
        }catch (\Exception $e){
            return "Error:".$e->getMessage();
        }
    }

    //获取微信头像base64
    private function getWxHeadForBase64($url) {
        // 设置运行时间为无限制
        set_time_limit ( 0 );
        $url = trim ( $url );


        $curl = curl_init ();
        // 设置你需要抓取的URL
        curl_setopt ( $curl, CURLOPT_URL, $url );
        // 设置header
        curl_setopt ( $curl, CURLOPT_HEADER, 0 );
        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        // 运行cURL，请求网页
        $file = curl_exec ( $curl );
        // 关闭URL请求
        curl_close ( $curl );
        // 将文件写入获得的数据
        $base64 = chunk_split(base64_encode($file));
        return $base64;
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


    //系统详情
    public function getSysInfo(){
        $system = array();
        //上月消耗钻石
        $system['front_zs'] = 100;
        //当月消耗钻石
        $system['now_zs'] = 100;
        //上月消耗金豆
        $system['front_jd'] = 200;
        //当月消耗金豆
        $system['now_jd'] = 300;
        //总消耗钻石
        $system['total_zs'] = 300;
        //总消耗金豆
        $system['total_jd'] = 300;
        //注册玩家
        $system['count_person'] = CommClass::GetPerosn();;
        //在线人数
        $system['online_person'] = CommClass::GetOnlinePerosn();
        return view('MyInfo.system',['sys'=>$system]);
    }

}
