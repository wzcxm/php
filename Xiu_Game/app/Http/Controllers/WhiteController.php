<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhiteController extends Controller
{
    //
    //分数设置
    public function index(){
        //$List = Users::where('lower_limit','<>',0)->orWhere('upper_limit','<>',0)->get();
        $List = DB::table('pp_agenysys_attribute')->where('lower_limit','<>',0)->orWhere('upper_limit','<>',0)->get();
        return view('White.index',['List'=>$List]);
    }

    public function indexlist(){
        return view('White.List');
    }
    //战绩排名
    public function topsSearch($type,$orderby){
        try{
            $g_type = empty($type)?1:$type;
            $year = date('Y');
            $month =date('n');
            $sql = <<<EOT
            select player_id,sum_score from record_count_view where c_year=$year and c_month = $month and gametype=$g_type  order by sum_score $orderby  LIMIT 100
EOT;
            $List = DB::connection('mysql_game')->select($sql);
            if(!empty($List)){
                return  response()->json(['List'=>$List]);
            }
            else{return  response()->json(['List'=>""]);}
        }catch (\Exception $e){
            return  response()->json(['Error'=>$e->getMessage()]);
        }

    }
    //修改
    public function setwhite($id){
        $user = DB::table('pp_agenysys_attribute')->where('id',$id)->first();
        return view('White.setwhite',['user'=>$user]);
    }
    //新增保存
    public function save(Request $request){
        try {
            if($request['player_id']==1){//全局分数
                DB::table('pp_agenysys_attribute')->insert([
                    'uid' => $request['player_id'],
                    'g_type'=>$request['gtype'],
                    'upper_limit' => $request['upper_limit'],
                    'lower_limit' => $request['lower_limit']
                ]);
                return response()->json(['msg' => 1]);
            }else { //其他玩家分数设置
                $user = Users::find($request['player_id']);
                if (empty($user)) { //判断玩家是否存在
                    return response()->json(['msg' => "玩家的UID错误！"]);
                } else {
                    DB::table('pp_agenysys_attribute')->insert([
                        'uid' => $request['player_id'],
                        'g_type' => $request['gtype'],
                        'upper_limit' => $request['upper_limit'],
                        'lower_limit' => $request['lower_limit']
                    ]);
                }
                return response()->json(['msg' => 1]);
            }
        }catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
    //修改保存
    public function edit(Request $request){
        try {
            DB::table('pp_agenysys_attribute')
                ->where('id',$request['id'])
                ->update([
                    'g_type'=>$request['gtype'],
                    'upper_limit'=>$request['upper_limit'],
                    'lower_limit'=>$request['lower_limit']
                ]);
            return response()->json(['msg' => 1]);
        }catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
    //查询
    public function search($id){
        try {
            $user = DB::table('pp_agenysys_attribute')->where('uid', $id)->first();
            if (empty($user)) {
                return response()->json(['Error' => "该玩家还未设置分数！"]);
            } else {
                return response()->json(['user' => $user]);
            }
        }catch (\Exception $e){
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    //删除
    public function del($ids){
        try{
            $items=explode(',',$ids);
            DB::table('pp_agenysys_attribute')->whereIn('id',$items)->delete();
            return response()->json(['msg' => 1]);
        }catch (\Exception $e){
            return  response()->json(['Error'=>$e->getMessage()]);
        }
    }
    //战绩查询
    public function recordSearch(Request $request){
        try{
            $g_type = empty($request['game_type'])?1:$request['game_type'];
            $year = empty($request['year'])?date('Y'):$request['year'];
            $month = empty($request['month'])?date('n'):$request['month'];
            $orderby = empty($request['orderby'])?'asc':$request['orderby'];
            $sql = <<<EOT
            select * from record_count_view where c_year=$year and c_month in ($month) and gametype=$g_type
EOT;
            if(!empty($request['payer_id'])){
                $sql.=" and player_id=".$request['payer_id'];
            }
            $sql .= " order by sum_score ". $orderby;
            $List = DB::connection('mysql_game')->select($sql);
            if(!empty($List)){
                return  response()->json(['List'=>$List]);
            }
            else{return  response()->json(['List'=>""]);}
        }catch (\Exception $e){
            return  response()->json(['Error'=>$e->getMessage()]);
        }
    }
}
