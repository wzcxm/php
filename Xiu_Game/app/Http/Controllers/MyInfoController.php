<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Users;
use Illuminate\Http\Request;

class MyInfoController extends Controller
{
    //
    public function index(){
        return view('MyInfo.index',['user'=>Users::find(session('uid'))]);
    }

    public function save(Request $request){
        try {
            $user = Users::find($request['uid']);
            if(!empty($request['nickname'])){
                $user->nickname = $request['nickname'];
            }
            if(!empty($request['uphone'])){
                $user->uphone = $request['uphone'];
            }
            if(!empty($request['idnum'])){
                $user->idnum = $request['idnum'];
            }
            if(!empty($request['address'])){
                $user->address = $request['address'];
            }
            $user->save();
            return response()->json(['msg' => 1]);
        }catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    //活动公告
    public function notice(){
        $Message = Message::where('mtype',2)->orderBy('msgid','desc')->first();
        return view('Notice.index',['Message'=>$Message]);
    }
}
