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
                return response()->json(['message'=>'数据错误！']);
            }
            //id
            $teaid = isset($data['tea_id'])?$data['tea_id']:0;
            if(empty($teaid)) {
                return response()->json(['message'=>'牌馆ID错误！']);
            }
            $arr_tea = [];
            //最低积分
            if(isset($data['score1'])){
                $arr_tea['score1']='-'.$data['score1'];
            }
            if(isset($data['score2'])){
                $arr_tea['score2']='-'.$data['score2'];
            }
            if(isset($data['score3'])){
                $arr_tea['score3']='-'.$data['score3'];
            }
            //是否开启最低积分校验
            if(isset($data['off1'])){
                $arr_tea['off1']=$data['off1'];
            }
            if(isset($data['off2'])){
                $arr_tea['off2']=$data['off2'];
            }
            if(isset($data['off3'])){
                $arr_tea['off3']=$data['off3'];
            }
            //基础积分
            if(isset($data['jifen1'])){
                $arr_tea['jifen1']=$data['jifen1'];
            }
            if(isset($data['jifen2'])){
                $arr_tea['jifen2']=$data['jifen2'];
            }
            if(isset($data['jifen3'])){
                $arr_tea['jifen3']=$data['jifen3'];
            }
            //大于等于中间值茶水费
            if(isset($data['huilv1'])){
                $arr_tea['huilv1']=$data['huilv1'];
            }
            if(isset($data['huilv2'])){
                $arr_tea['huilv2']=$data['huilv2'];
            }
            if(isset($data['huilv3'])){
                $arr_tea['huilv3']=$data['huilv3'];
            }
            //茶水费中间值
            if(isset($data['bzfen1'])){
                $arr_tea['bzfen1']=$data['bzfen1'];
            }
            if(isset($data['bzfen2'])){
                $arr_tea['bzfen2']=$data['bzfen2'];
            }
            if(isset($data['bzfen3'])){
                $arr_tea['bzfen3']=$data['bzfen3'];
            }
            //小于标准值茶水费
            if(isset($data['mincf1'])){
                $arr_tea['mincf1']=$data['mincf1'];
            }
            if(isset($data['mincf2'])){
                $arr_tea['mincf2']=$data['mincf2'];
            }
            if(isset($data['mincf3'])){
                $arr_tea['mincf3']=$data['mincf3'];
            }
            //积分开关
            if(isset($data['jfoff1'])){
                $arr_tea['jfoff1']=$data['jfoff1'];
            }
            if(isset($data['jfoff2'])){
                $arr_tea['jfoff2']=$data['jfoff2'];
            }
            if(isset($data['jfoff3'])){
                $arr_tea['jfoff3']=$data['jfoff3'];
            }
            //推荐人获得体力
            if(isset($data['share1'])){
                $arr_tea['share1']=$data['share1'];
            }
            if(isset($data['share2'])){
                $arr_tea['share2']=$data['share2'];
            }
            if(isset($data['share3'])){
                $arr_tea['share3']=$data['share3'];
            }
            //成为大赢家的积分标准
            if(isset($data['winscore1'])){
                $arr_tea['winscore1']= $data['winscore1'];
            }
            if(isset($data['winscore2'])){
                $arr_tea['winscore2']= $data['winscore2'];
            }
            if(isset($data['winscore3'])){
                $arr_tea['winscore3']= $data['winscore3'];
            }
            if(!empty($arr_tea)){
                DB::table('xx_sys_tea')->where('tea_id',$teaid)
                    ->update($arr_tea);
            }
            return response()->json(['message'=>'']);
        }catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()]);
        }
    }
}
