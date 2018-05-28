<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateIdController extends Controller
{
    //
    public function update(Request $request){
        try{
            $data = isset($request['data'])?$request['data']:"";
            $old_uid = isset($data['old_uid'])?$data['old_uid']:0;
            $new_uid = isset($data['new_uid'])?$data['new_uid']:0;
            $old_teaid = isset($data['old_teaid'])?$data['old_teaid']:0;
            $new_teaid = isset($data['new_teaid'])?$data['new_teaid']:0;
            $new_user = DB::table('xx_user')->where('uid',$new_uid)->get();
            if(count($new_user)>0){
                return response()->json(['Error'=>'新id已存在！']);
            }
            if(!empty($old_uid) && !empty($new_uid)){
                DB::select('CALL update_uid('.$old_uid.','.$new_uid .')');
                //新增的靓号加入靓号列表
                $pretty = DB::table('xx_sys_liang')->where('liang_uid',$new_uid)->get();
                if(empty($pretty) || count($pretty) <= 0 ){
                    DB::table('xx_sys_liang')->insert(['liang_uid'=>$new_uid]);
                }
            }
            if(!empty($old_teaid) && !empty($new_teaid)){
                DB::select('CALL update_teaid('.$old_teaid.','.$new_teaid .')');
            }
            return response()->json(['Error'=>'']);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }


    //查询玩家信息
    public function  Search(Request $request){
        $uid = isset($request["uid"])?$request["uid"]:0;
        $payer = Users::find($uid);
        if(!empty($payer)){
            return response()->json(['user'=>$payer,'msg'=>'']);
        }else{
            return response()->json(['user'=>$payer,'msg'=>"玩家ID不存在，请重新输入！"]);
        }
    }
}
