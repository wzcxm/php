<?php

namespace App\Http\Controllers;
use App\Common\CommClass;
use App\Models\ShoppingMall;
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
                    $this->setUserInfo($uid,$item);
                    return $item['id'];
                }
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    private function setUserInfo($uid,$item){
        try{
            if($item['id'] !=1){
                if($item['id'] == 9 || $item['id'] == 10){
                    DB::table('xx_user')->where('uid',$uid)->update(['lottery' => 2]);
                    DB::table('xx_sys_prize')->insert(['name'=>$item['name'],'uid'=>$uid,'code'=>$item['id'],'type'=>1,'jptype'=>1]);
                }else{
                    DB::table('xx_user')->where('uid',$uid)->increment('redbag', $item['value'],['lottery' => 2]);
                    DB::table('xx_sys_prize')->insert(['name'=>$item['name'],'uid'=>$uid,'code'=>$item['id'],'type'=>1,'jptype'=>3]);

                    $message = "<color='red'>恭喜玩家：【".Users::find($uid)->nickname."】,在每日分享抽奖中，抽中了：【".$item['name']."】！</color>";
                    CommClass::UpGameSer(1,'msg',$message);
                }
            }else{
                DB::table('xx_user')->where('uid',$uid)->update(['lottery' => 2]);
            }
        }catch (\Exception $e){
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
                if($ret_arr['number']>8){
                    $ret_arr['number'] .= '分';
                }else{
                    $ret_arr['number'] .= '局';
                }
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



    ///购买金豆
    /// $uid:玩家ID
    /// $bid:商品id
    public function buyBeans($uid,$bid){
        try{
            if(empty($uid) || empty($bid)) return 0;
            $maill = ShoppingMall::find($bid);
            if(empty($maill)) return 0;
            $user = Users::find($uid);
            $user->roomcard -= $maill->sprice;
            $user->gold += $maill->snumber;
            $user->save();
            //保存购买记录
            DB::table('xx_sys_buybeans')->insert(['uid'=>$uid,'card'=>$maill->sprice,'gold'=>$maill->snumber]);
            //更新游戏端数据
            CommClass::UpGameSer($uid,'card');
            CommClass::UpGameSer($uid,'coin');
            return 1;
        }catch (\Exception $e){
            return 0;
        }
    }
}
