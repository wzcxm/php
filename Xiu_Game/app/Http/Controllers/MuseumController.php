<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuseumController extends Controller
{
    //
    /*
     * 获取我的牌馆
     */
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $where = " uid =".session('uid');
        $museum_arr = CommClass::PagingData($page,$rows,"xx_sys_tea" ,$where);
        return response()->json($museum_arr);
    }

    /*
     * 设置页面
     */
    public function setting($tea_id){
        $tea = DB::table('xx_sys_tea')->where('tea_id',$tea_id)->first();
        return view('Museum.setting',['tea'=>$tea]);
    }


    /*
     * 保存设置
     */
    public function save_set(Request $request){
        try{
            $data = isset($request['data'])?$request['data']:"";
            if(empty($data)){
                return response()->json(['status'=>0,'message'=>'数据错误！']);
            }
            //id
            $teaid = isset($data['tea_id'])?$data['tea_id']:0;
            //最低积分
            $score1 = isset($data['score1'])?$data['score1']:0;
            $score2 = isset($data['score2'])?$data['score2']:0;
            $score3 = isset($data['score3'])?$data['score3']:0;
            //是否开启最低积分校验
            $off1 = isset($data['off1'])?$data['off1']:0;
            $off2 = isset($data['off2'])?$data['off2']:0;
            $off3 = isset($data['off3'])?$data['off3']:0;
            //基础积分
            $jifen1 = isset($data['jifen1'])?$data['jifen1']:0;
            $jifen2 = isset($data['jifen2'])?$data['jifen2']:0;
            $jifen3 = isset($data['jifen3'])?$data['jifen3']:0;
            //茶水费
            $huilv1 = isset($data['huilv1'])?$data['huilv1']:0;
            $huilv2 = isset($data['huilv2'])?$data['huilv2']:0;
            $huilv3 = isset($data['huilv3'])?$data['huilv3']:0;
            if(empty($teaid)) {
                return response()->json(['status'=>0,'message'=>'牌馆ID错误！']);
            }
            DB::table('xx_sys_tea')->where('tea_id',$teaid)
                ->update([
                    'score1'=>$score1,'score2'=>$score2,'score3'=>$score3,
                    'off1'=>$off1,'off2'=>$off2,'off3'=>$off3,
                    'jifen1'=>$jifen1,'jifen2'=>$jifen2,'jifen3'=>$jifen3,
                    'huilv1'=>$huilv1,'huilv2'=>$huilv2,'huilv3'=>$huilv3
                ]);
            return response()->json(['status'=>1,'message'=>'']);
        }catch (\Exception $e){
            return response()->json(['status'=>0,'message'=>$e->getMessage()]);
        }
    }
}
