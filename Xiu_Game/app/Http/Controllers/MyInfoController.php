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

    //我的推广码
    public function getQrCode(){
        try{
            $user = Users::find(session('uid'));
            $url = 'http://'.$_SERVER['HTTP_HOST'].'/download/'.session('uid');
            return view('MyInfo.qrcode',['user'=>$user,'url'=>$url]);
        }catch (\Exception $e){
            return "Error:".$e->getMessage();
        }
    }
}
