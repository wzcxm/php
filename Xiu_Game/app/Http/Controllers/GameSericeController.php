<?php

namespace App\Http\Controllers;
use App\Common\CommClass;
use App\Models\ShoppingMall;
use App\Models\Users;
use App\Wechat\example\JsApiPay;
use App\Wechat\lib\WxAppPayUnifiedOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Wechat\lib\WxPayRedPack;
use App\Wechat\lib\WxPayConfig;
use App\Wechat\lib\WxPayApi;
use Nwidart\DbExporter\DbExporter;
use Xxgame\RedisTableInfo;
use App\Wechat\example\log;
use App\Wechat\example\CLogFileHandler;
use App\Wechat\lib\WxPayNotify;


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

                    //$message = "<color='red'>恭喜玩家：【".Users::find($uid)->nickname."】,在每日分享抽奖中，抽中了：【".$item['name']."】！</color>";
                    //CommClass::UpGameSer(1,'msg',$message,3);
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

    /**
     * @param $uid
     * @return int
     */
    public function RedPack($uid){
        try{
            if(empty($uid)) return "UID_ERROR";//["state"=>0,"Error"=>"uid or total error "];
            //查询并扣除用户红包金额
            $data = DB::select('CALL query_update('.$uid.')');
            $orderno = WxPayConfig::MCHID . date("YmdHis");
            $openid = $data[0]->wxopenid;
            $total = $data[0]->redbag;
            if($total < 1) {
                return "AMOUNT_ERROR";
            }
            $logHandler = new CLogFileHandler($_SERVER['DOCUMENT_ROOT'] . "/logs/" . date('Y-m-d') . '.log');
            $log = Log::Init($logHandler, 15);
            $ret_msg = $this->sendRed($orderno,$openid,$total);
            if( $ret_msg == "OK"){
                //将发送红包记录保存
                DB::table('xx_sys_extract')->insert(['uid'=>$uid,'gold'=>$total,'orderno'=>$orderno,'status'=>1]);
                //修改用户的红包记录
                DB::table('xx_sys_prize')->where([['uid',$uid],['jptype',3]])->update(['isreceive'=>1]);
                return 1;
            }else{
                $log->INFO($uid.':'.$ret_msg);
                //发放失败，玩家红包加回来
                DB::table('xx_user')->where('uid',$uid)->update(['redbag'=>$total]);
                return $ret_msg;
            }
        }catch (\Exception $e){
            return "OTHER_ERROR";
        }
    }


    private  function sendRed($orderno,$openid,$total){
        try{
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
                return "OK";
            }else{
                return $result["err_code"];
            }
        }catch (\Exception $e){
            return $e->getMessage();
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


    ///短信发送
    /// $uid:玩家ID
    /// $type:发送类型
    public function sendCodeSms($tel){
        try{
            $code = rand(1000,9999 );
            //存入缓存
            $expiresAt = Carbon::now()->addMinutes(5);
            \Cache::put($tel,$code,$expiresAt);
            $param = array('code'=>$code);
            $content = CommClass::send_sms($tel,'SMS_123797991',$param);
            if($content->Code == "OK"){
                return response()->json(['Error'=>""]);
            }else{
                return response()->json(['Error'=>"发送失败，请检查手机号是否正确！"]);
            }
        }catch (\Exception $e){
             return response()->json(['Error'=>"发送失败，请重试！"]);
        }
    }


    /*
     * App支付统一下单
     * $uid:玩家ID
     * $goodsid:商品id
     */
    public function getAppOrder($uid,$goodsid){
        $player = Users::find($uid);
        if(empty($player)){
            $ret_json =  ['status'=>1,'message'=>'玩家ID错误！'];
        }else{
            $product = ShoppingMall::find($goodsid);
            //购卡数量
            $toltal_number = $product->snumber;
            //总金额
            $total_fee = $product->sprice*100;
            //订单号
            $orderno = WxPayConfig::MCHID . date("YmdHis");

            //生成订单
            $param = $this->generateAppOrder($total_fee,$orderno);
            if(empty($param)){
                $ret_json = ['status'=>1,'message'=>'下单失败！'];
            }else{
                if ($param['return_code'] == "SUCCESS" && $param['result_code'] == "SUCCESS") {
                    $arr = ['appid'=>$param["appid"], 'mch_id'=>$param["mch_id"],
                        'prepay_id'=>$param["prepay_id"], 'nonce_str'=>$param["nonce_str"],
                        'package'=>'Sign=WXPay', 'timestamp'=>time()];
                    $ret_json =['status'=>0,
                        'message'=>'',
                        'order_no'=>$orderno,
                        'appid'=>$param["appid"], 'mch_id'=>$param["mch_id"],
                        'prepay_id'=>$param["prepay_id"], 'nonce_str'=>$param["nonce_str"],
                        'package'=>'Sign=WXPay', 'timestamp'=>time(),
                        'sign'=>$this->setsgin($arr)
                        ];
                    //保存订单号到数据库
                    $remp = DB::table('xx_wx_buycard')->insert([
                        'userid' => $uid,
                        'cardnum' => $toltal_number,
                        'total' => $total_fee/100,
                        'nonce' => $orderno,
                        'btype' => 1
                    ]);
                }else{
                    $ret_json =['status'=>1,'message'=>$param["return_code"].':'.$param["return_msg"]];
                }
            }
        }
        return $ret_json;
    }

    private function generateAppOrder($total_fee,$orderno){
        $logHandler = new CLogFileHandler($_SERVER['DOCUMENT_ROOT'] . "/logs/" . date('Y-m-d') . '.log');
        $log = Log::Init($logHandler, 15);
        try {
            $input = new WxAppPayUnifiedOrder();
            $input->SetBody("休休游戏-充值");
            $input->SetAttach("休休科技");
            $input->SetOut_trade_no($orderno);
            $input->SetTotal_fee($total_fee);
            $input->SetTime_start(date("YmdHis"));
            $input->SetGoods_tag("WXG");
            $input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/api/app/notify");
            $input->SetTrade_type("APP");
            $order = WxPayApi::AppUnifiedOrder($input);
            return $order;
        }catch (\Exception $e){
            $log->ERROR($e->getMessage());
            return null;
        }
    }
    //回调方法
    public function appnotify(){
        try{
            $notifyback = new WxPayNotify();
            $notifyback->appHandle(false);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    //删除订单
    public function delAppOrder($order_no){
        if(!empty($order_no)){
            DB::table('xx_wx_buycard')->where([['nonce', $order_no],['status',0]])->delete();
            return 1;
        }else{
            return 0 ;
        }


    }


    private function setsgin($param){
        //签名步骤一：按字典序排序参数
        ksort($param);
        $string = $this->ToUrlParams($param);
        //签名步骤二：在string后加入KEY
        //$string = $string . "&key=".WxPayConfig::GAMEKEY;
        //签名步骤三：MD5加密
        $string = md5($string);
        //hash_hmac加密
        //$string = hash_hmac('sha256',$string,WxPayConfig::GAMEKEY);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    private function ToUrlParams($param)
    {
        $buff = "";
        foreach ($param as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

//    private  function test(){
//        //开始抽奖
//        $data = collect(config("conf.Prize"));
//        if(empty($data)) return ""; //奖品个数不能为空
//        $poll  = $data->sum('level');
//        $rand = rand(0,$poll);
//        $nownum = 0;
//        foreach ($data as $item){
//            $nownum += $item['level'];
//            if($rand <= $nownum){
//                return $item['id'];
//            }
//        }
//    }
//
//
//    public function testLottery(){
//        $ret1 = 0;
//        $ret2 = 0;
//        $ret3 = 0;
//        $ret4 = 0;
//        $ret5 = 0;
//        $ret6 = 0;
//        for ($i=1 ;$i<1000;$i++){
//           $ret = $this->test();
//            if($ret == 1){
//                $ret1++;
//            }
//            if($ret == 2){
//                $ret2++;
//            }
//            if($ret == 3){
//                $ret3++;
//            }
//            if($ret == 4){
//                $ret4++;
//            }
//            if($ret == 5){
//                $ret5++;
//            }
//            if($ret == 6){
//                $ret6++;
//            }
//        }
//        $ret1 = $ret1*0;
//        $ret2 = $ret2*0.88;
//        $ret3 = $ret3*1.88;
//        $ret4 = $ret4*2.88;
//        $ret5 = $ret5*3.88;
//        $ret6 = $ret6*8.88;
//        $ret7 = $ret1+$ret2+$ret3+$ret4+$ret5+$ret6;
////        return '谢谢：'.$ret1.'%\n0.88:'.$ret2.'%\n1.88:'.$ret3.'%\n2.88:'.$ret4.'%\n3.88:'.$ret5.'%\n8.88:'.$ret6;
//        var_dump('谢谢：'.$ret1.'$') ;
//        var_dump( '0.88:'.$ret2.'$');
//        var_dump( '1.88:'.$ret3.'$');
//        var_dump( '2.88：'.$ret4.'$');
//        var_dump( '3.88：'.$ret5.'$');
//        var_dump( '8.88：'.$ret6.'$');
//        var_dump( 'sum：'.$ret7.'$');
//    }
}
