<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardQueryController extends Controller
{
    //拨入统计
    public function buy(){
        $start = date('Y-m-01');
        $end = date('Y-m-t 23:59:59');
        $where = [['ctype',1],['cbuyid',session('uid')]];
        $List = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])
            ->offset(0)->limit(10)->get();
        $sum_num = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])->sum('cnumber');
        return view('CardQuery.buyQuery',['List'=>$List,'sum_num'=>$sum_num]);
    }
    //拨出统计
    public function sell(){
        $start = date('Y-m-01');
        $end = date('Y-m-t 23:59:59');
        $where = [['ctype',1],['csellid',session('uid')]];
        $List = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])
            ->offset(0)->limit(10)->get();
        $sum_num = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])->sum('cnumber');
        return view('CardQuery.sellQuery',['List'=>$List,'sum_num'=>$sum_num]);
    }
    //返卡统计
    public function back(){
        $start = date('Y-m-01');
        $end = date('Y-m-t 23:59:59');
        $where = [['ctype',4],['cbuyid',session('uid')]];
        $List = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])
            ->offset(0)->limit(10)->get();
        $sum_num = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])->sum('cnumber');
        return view('CardQuery.backQuery',['List'=>$List,'sum_num'=>$sum_num]);
    }
    //奖励统计
    public function reward(){
        $start = date('Y-m-01');
        $end = date('Y-m-t 23:59:59');
        $where = [['ctype',3],['cbuyid',session('uid')]];
        $List = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])
            ->offset(0)->limit(10)->get();
        $sum_num = DB::table('view_trade')->where($where)
            ->whereBetween('ctradedate',[$start,$end])->sum('cnumber');
        return view('CardQuery.rewardQuery',['List'=>$List,'sum_num'=>$sum_num]);
    }
    //查询
    public function Search(Request $request){

    }

    //获取售统计
    private function GetCsell($strWhere,$offset){

        $sql="select u.rname,
                       u.head_img_url,
                       date_format(t.ctradedate,'%Y-%m-%d %h:%m:%s') as c_date,
                       t.* 
                from xx_sys_cardstrade t
                left join view_user u on u.uid = t.cbuyid
                where ".$strWhere." ORDER BY ctradedate DESC limit ".$offset.",10";
        return DB::select($sql);
    }


    //获取销售情况
    private function GetCardInfo($strWhere,$offset){

        $sql="select u.rname,
                       u.head_img_url,
                       date_format(t.ctradedate,'%Y-%m-%d %h:%m:%s') as c_date,
                       t.* 
                from xx_sys_cardstrade t
                left join view_user u on u.uid = t.csellid
                where ".$strWhere." ORDER BY ctradedate DESC limit ".$offset.",10";
         return DB::select($sql);
    }

    ///下一页

    /**
     * @param Request $request
     * @param $type
     * @param $offset
     * @return \Illuminate\Http\JsonResponse
     */
    public function NextPage(Request $request, $type, $offset){
        try {
            $start = $request['start_date'];
            $end = $request['end_date'];
            $where = [];
            switch ($type) {
                case 1://购卡统计
                    $where = [['ctype',1]];
                    $user_id = session('uid');
                    $role_id = session('roleid');
                    $payer_id = $request['payer_id'];
                    if ($role_id == 1) { //总公司，可以查询其他用户的购卡记录
                        if (!empty($payer_id)) {//如果输入了ID，则查询该用户的售卡记录
                            Array_push($where,['cbuyid',$payer_id]);
                        } else {//否则查询当前登陆用户的售卡记录
                            Array_push($where,['cbuyid',$user_id]);
                        }
                    } else {//其他用户只能查询自己的售卡记录
                        Array_push($where,['cbuyid',$user_id]);
                        if (!empty($payer_id)) { //如果输入ID，则查询该玩家的记录
                            Array_push($where,['csellid',$payer_id]);
                        }
                    }
                    break;
                case 2://售卡统计
                    $where = [['ctype',1]];
                    $user_id = session('uid');
                    $role_id = session('roleid');
                    $payer_id = $request['payer_id'];
                    if ($role_id == 1) { //总公司，可以查询其他用户的售卡记录
                        if (!empty($payer_id)) {//如果输入了ID，则查询该用户的售卡记录
                            Array_push($where,['csellid',$payer_id]);
                        } else {//否则查询当前登陆用户的售卡记录
                            Array_push($where,['csellid',$user_id]);
                        }
                    } else {//其他用户只能查询自己的售卡记录
                        Array_push($where,['csellid',$user_id]);
                        if (!empty($payer_id)) { //如果输入ID，则查询出售给该玩家的记录
                            Array_push($where,['cbuyid',$payer_id]);
                        }
                    }
                    break;
                case 3://奖励
                    $where = [['ctype',3],['cbuyid',session('uid')]];
                    break;
                case 4://返卡
                    $where = [['ctype',4]];
                    $user_id = session('uid');
                    $role_id = session('roleid');
                    $payer_id = $request['payer_id'];
                    if ($role_id == 1) { //总公司，可以查询其他用户的返卡记录
                        if (!empty($payer_id)) {//如果输入了ID，则查询该用户的返卡记录
                            Array_push($where,['cbuyid',$payer_id]);
                        } else {//否则查询当前登陆用户的售卡记录
                            Array_push($where,['cbuyid',$user_id]);
                        }
                    } else {//其他用户只能查询自己的售卡记录
                        Array_push($where,['cbuyid',$user_id]);
                        if (!empty($payer_id)) { //如果输入ID，则查询该玩家给我的返卡记录
                            Array_push($where,['csellid',$payer_id]);
                        }
                    }
                    break;
                default:
                    break;
            }
            if (empty($start) && empty($end)) {
                $start = date('Y-m-01');
                $end = date('Y-m-t 23:59:59');
            } else {
                if (empty($start)) {
                    $start = date('Y-m-01', strtotime($end));
                }
                if (empty($end)) {
                    $end = date('Y-m-t 23:59:59', strtotime($start)) ;
                }else{
                    $end = date('Y-m-d 23:59:59', strtotime($end)) ;
                }
            }
            $List = DB::table('view_trade')->where($where)
                    ->whereBetween('ctradedate',[$start,$end])
                    ->offset($offset)->limit(10)->get();

            $sum_num = DB::table('view_trade')->where($where)
                ->whereBetween('ctradedate',[$start,$end])->sum('cnumber');
            return response()->json(['List' => $List,'sum_num'=>$sum_num]);
        }catch (\Exception $e){
            return response()->json(['Error' => $e->getMessage()]);
        }
    }
}
