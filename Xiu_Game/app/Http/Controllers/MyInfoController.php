<?php

namespace App\Http\Controllers;

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
}
