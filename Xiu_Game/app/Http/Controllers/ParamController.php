<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;

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
}
