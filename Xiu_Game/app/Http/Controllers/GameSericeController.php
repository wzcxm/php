<?php

namespace App\Http\Controllers;
use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class GameSericeController extends Controller
{

    ///抽奖
    /// $uid:玩家ID
    public function getLottery($uid){
        try{

            if(empty($uid)) return ""; //uid错误
            $user = Users::find($uid);
            if(empty($user)) return ""; //玩家不存在
            if($user->lottery != 1) return ""; //玩家未分享或以抽奖
            if($user->online_state != 1) return ""; //玩家不在线
            //开始抽奖
            $data = collect(config("conf.Prize"));
            if(empty($data)) return ""; //奖品个数不能为空
            $poll  = $data->sum('level');
            $rand = rand(0,$poll);
            $nownum = 0;
            foreach ($data as $item){
                $nownum += $item['level'];
                if($rand <= $nownum){
                    $this->setUserInfo($uid,$item['id']);
                    return $item['id'];
                }
            }
        }catch (\Exception $e){
            return "";
        }
    }

    private function setUserInfo($uid,$type){
        try{
            $name = "";
            $upstype = "";
            $num = 0;
            switch ($type){
                case 1:
                    $name = '100金币';
                    $upstype = 'coin';
                    $num = 100;
                    break;
                case 2:
                    $name = '300金币';
                    $upstype = 'coin';
                    $num = 300;
                    break;
                case 3:
                    $name = '500金币';
                    $upstype = 'coin';
                    $num = 500;
                    break;
                case 4:
                    $name = '1000金币';
                    $upstype = 'coin';
                    $num = 1000;
                    break;
                case 5:
                    $name = '2钻石';
                    $upstype = 'card';
                    $num = 2;
                    break;
                case 6:
                    $name = '8钻石';
                    $upstype = 'card';
                    $num = 8;
                    break;
                case 7:
                    $name = '1元红包';
                    $upstype = 'red';
                    $num = 1;
                    break;
                case 8:
                    $name = '2元红包';
                    $upstype = 'red';
                    $num = 2;
                    break;
                case 9:
                    $name = '5元红包';
                    $upstype = 'red';
                    $num = 5;
                    break;
                case 10:
                    $name = '1000元红包';
                    $upstype = 'red';
                    $num = 1000;
                    break;
            }
            if($upstype == 'coin'){
                DB::table('xx_user')->where('uid',$uid)->increment('gold', $num,['lottery' => 2]);
                DB::table('xx_sys_prize')->insert(['name'=>$name,'uid'=>$uid,'code'=>$type]);
                CommClass::UpGameSer($uid,'coin');//更新玩家金币
            }else if($upstype == 'card'){
                DB::table('xx_user')->where('uid',$uid)->increment('roomcard', $num,['lottery' => 2]);
                DB::table('xx_sys_prize')->insert(['name'=>$name,'uid'=>$uid,'code'=>$type]);
                CommClass::UpGameSer($uid,'card');//更新玩家金币
            }else if($upstype == 'red'){
                DB::table('xx_user')->where('uid',$uid)->increment('redbag', $num,['lottery' => 2]);
                DB::table('xx_sys_prize')->insert(['name'=>$name,'uid'=>$uid,'code'=>$type]);
                $user = Users::find($uid);
                $message = "恭喜玩家：【".$user->nickname."】,在每日分享抽奖中，抽中【".$name."】奖品！";
                CommClass::UpGameSer(1,'urgent',$message);
            }
        }catch (\Exception $e){
            return "";
        }
    }

    //分享
    public function index($roomNo=0,$msg='')
    {
        try{
            //var_dump('roomno:'.$roomNo);

            $room =  Redis::get('room_'.$roomNo);
            //var_dump('room:'.$room);
            $uids = collect(explode('|', $room))->filter(function ($value,$key){ return $value>0; });
            //var_dump('uids:'.$uids);
            if(!empty($room))
            {
                //获取玩家的信息
                $Users = DB::table('pp_user')->whereIn('uid', $uids)->get();
                //var_dump($Users);
                return view('Share.Index', ['Users' => $Users]);
            }else {
                return  view('Share.Undefined');
            }
        }catch (\Exception $e){
            //var_dump('error:'.$e->getMessage());
            //return  view('404');
            return  view('Share.Undefined');
        }

    }

}
