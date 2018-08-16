<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParamController extends Controller
{
    //获取参数
    public function index(){
        $param=CommClass::GetXml();
        return view('Parameter.index',['param'=>$param]);
    }
    //保存参数
    public function store(Request $request){
        try{
            $data = isset($request['data'])?$request['data']:'';
            if(empty($data)) {
                return response()->json(['msg'=>'保存失败！']);
            }
            $param =  Array(
                'upper_one'=>$data['upper_one'],
                'upper_two'=>$data['upper_two'],
                'invitation'=>$data['invitation'],
                'share'=>$data['share'],
            );
            CommClass::SaveXml($param);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }


    //获取参数
    public function activity(){
        $activity = DB::table('xx_sys_activity')->where('id',1)->first();
        $arr = [];
        if(!empty($activity)){
            $arr['proportion']=$activity->proportion;
            $arr['isopen']=$activity->isopen;
        }
        return view('activity.index',$arr);
    }

    public function save_activity(Request $request){
        try{
            $data = isset($request['data'])?$request['data']:'';
            $proportion = isset($data['proportion'])?$data['proportion']:0;
            $isopen = isset($data['isopen'])?$data['isopen']:0;
            DB::table('xx_sys_activity')->where('id',1)->update(['proportion'=>$proportion,'isopen'=>$isopen]);
            return response()->json(['message'=>'']);
        }catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()]);
        }
    }
}
