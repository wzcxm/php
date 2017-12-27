<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpAndDownController extends Controller
{
    //
    public function index(){
        $List = Users::where('recharge_uid',session('uid'))->select('uid','head_img_url','scores','recharge_uid')->get();
        return view('UpAndDown.index',['List'=>$List]);
    }

    public function up($id){
        return view('UpAndDown.up',['user'=>Users::find($id)]);
    }
    public function down($id){
        return view('UpAndDown.down',['user'=>Users::find($id)]);
    }
    public function  save(Request $request){
        try {
            $user = Users::find($request['uid']);
            if($request['type']==1){
                $user->scores += $request['scores'];
            }else{
                $user->scores -= $request['scores'];
            }
            if(empty($user->scores) || $user->scores==0)
            {
                $user->recharge_uid = null;
            }else {
                $user->recharge_uid = session('uid');
            }
            $user->save();
            return response()->json(['msg' => 1]);
        }catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }
    public function search($id){
        $user = Users::find($id);
        if(empty($user)){
            return  response()->json(['Error'=>"玩家ID错误！"]);
        }else {
            if (empty($user->recharge_uid) || $user->recharge_uid == 0 || $user->recharge_uid == session('uid')) {
                return response()->json(['user' => $user]);
            } else {
                return response()->json(['Error' => "该玩家在其他代理已上分！"]);
            }
        }
    }
}
