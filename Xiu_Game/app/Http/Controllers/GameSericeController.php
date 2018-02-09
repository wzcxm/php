<?php

namespace App\Http\Controllers;
use App\Common\CommClass;
use App\Models\Users;
use App\Wechat\example\JsApiPay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Wechat\lib\WxPayRedPack;
use App\Wechat\lib\WxPayConfig;
use App\Wechat\lib\WxPayApi;
use Xxgame\RedisTableInfo;


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
            return $e->getMessage();
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
                DB::table('xx_sys_prize')->insert(['name'=>$name,'uid'=>$uid,'code'=>$type,'type'=>1,'jptype'=>1]);
                CommClass::UpGameSer($uid,'coin');//更新玩家金币
            }else if($upstype == 'card'){
                DB::table('xx_user')->where('uid',$uid)->increment('roomcard', $num,['lottery' => 2]);
                DB::table('xx_sys_prize')->insert(['name'=>$name,'uid'=>$uid,'code'=>$type,'type'=>1,'jptype'=>2]);
                CommClass::UpGameSer($uid,'card');//更新玩家金币
            }else if($upstype == 'red'){
                DB::table('xx_user')->where('uid',$uid)->increment('redbag', $num,['lottery' => 2]);
                DB::table('xx_sys_prize')->insert(['name'=>$name,'uid'=>$uid,'code'=>$type,'type'=>1,'jptype'=>3]);
                $user = Users::find($uid);
                $message = "恭喜玩家：【".$user->nickname."】,在每日分享抽奖中，抽中【".$name."】奖品！";
                CommClass::UpGameSer(1,'urgent',$message);
            }
        }catch (\Exception $e){
            return "";
        }
    }

    //游戏分享
    public function share($roomNo=0,$msg='')
    {
        try{
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            $unionid = $tools->data['unionid'];
            if(!empty($unionid)) {
                //下载人没有记录的保存记录
                $temp_user = DB::table('xx_user_temp')->where('unionid', $unionid)->first();
                if (empty($temp_user)) {
                    DB::table('xx_user_temp')->insert(['wxopenid' => $openid, 'unionid' => $unionid]);
                }
            }
            $room =  Redis::get('table_'.$roomNo);
            var_dump($room);
            if(!empty($room))
            {
                $ret_arr = [];
                $RedisTableInfo = new RedisTableInfo();
                $RedisTableInfo->decode($room);
                //牌馆ID
                $ret_arr['teaid'] = $RedisTableInfo->getTeaId();
                //游戏类型
                $ret_arr['gametype'] = $RedisTableInfo->getServerType();
                //局数
                $ret_arr['number'] = $RedisTableInfo->getMaxNumber();
                //玩法
                $ret_arr['play'] = $msg ;
                //桌号
                $desk = substr($roomNo,strlen($ret_arr['teaid']));
                $ret_arr['desk'] =$desk;
                //厅号
                if($desk > 8 && $desk < 17){
                    $hallid = 2;
                }else if($desk > 16){
                    $hallid = 3;
                }else{
                    $hallid = 1;
                }
                $ret_arr['hallid'] = $hallid ;
                //玩家arr
                $uid_arr = $RedisTableInfo->getUid();
                $nick_arr = $RedisTableInfo->getNickname();
                $head_arr = $RedisTableInfo->getHeadImgUrl();
                $ready_arr = $RedisTableInfo->getReady();
                $user_arr = [];
                for($i = 0;$i <count($uid_arr) ;$i++){
                    array_push($user_arr,['head'=>$head_arr[$i],'nick'=>$nick_arr[$i],'ready'=>$ready_arr[$i]]);
                }

                return view('Share.Index', ['item' => $ret_arr,'user'=>$user_arr]);
            }else {
                return  view('Share.Index');
            }
        }catch (\Exception $e){
            return view('Share.Index');
        }

    }

    //发红包
    public function RedPack($uid){
        try{
            if(empty($uid)) return 0;//["state"=>0,"Error"=>"uid or total error "];
            $player = Users::find($uid);
            $orderno = WxPayConfig::MCHID . date("YmdHis");
            $openid = $player->wxopenid;
            $total = $player->redbag;
            //发送红包
            $input = new WxPayRedPack();
            $input->SetMch_Billno($orderno);
            $input->SetSend_name("休休科技有限公司");
            $input->SetRe_openid($openid);
            $input->SetTotal_amount($total*100);
            $input->SetTotal_num(1);
            $input->SetWishing("休休游戏推广红包！");
            $input->SetAct_name("游戏推广活动");
            $input->SetRemark("休休游戏推广红包 ");
            $input->SetScene_id('PRODUCT_1');
            $result = WxPayApi::redPack($input);
            $result = $input->FromXml($result);
            if($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
                //发送成功，扣除用户红包金额
                DB::table('xx_user')->where('uid',$uid)->update(['redbag'=>0]);
                //将发送红包记录保存
                DB::table('xx_sys_extract')->insert(['playerid'=>$uid,'gold'=>$total,'orderno'=>$orderno,'status'=>1]);
                return 1;
            }else{
                return 0;//["state"=>0,"Error"=>$result["err_code"]."|".$result["err_code_des"]];
            }
        }catch (\Exception $e){
            return 0;//["state"=>0,"Error"=>$e->getMessage()];
        }
    }

    //下载页面
    public function  Download($uid = 0){
        try{
            //推荐人，不为空，保存记录
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            $unionid = $tools->data['unionid'];
            if(!empty($unionid)){
                //下载人没有记录的保存记录
                $temp_user = DB::table('xx_user_temp')->where('unionid',$unionid)->first();
                if(empty($temp_user)){
                    DB::table('xx_user_temp')->insert(['front'=>$uid,'wxopenid'=>$openid,'unionid'=>$unionid]);
                }
            }
            return view('MyInfo.download');
        }catch (\Exception $e){
            return view('MyInfo.download');
        }
    }
}
