<?php
namespace App\Common;

use App\Models\Users;
use App\Wechat\lib\WxPayException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Xxgame\ServerUserBase;
use Aliyun\DySDKLite\SignatureHelper;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/17
 * Time: 18:11
 */
 class CommClass
{
    //分页通用方法
    public static function PagingData($page,$rows,$table,$where=null,$orderby=null){
        $offset = ($page-1)*$rows;
        $sql = "select * from ".$table;

        if(!empty($where)){
            $sql .= " where ".$where;
        }
        if(!empty($orderby)){
            $sql .= " order by ".$orderby;
        }
        $count= DB::select($sql);
        $total = collect($count)->count();
        $sql .= " limit  ".$offset.",".$rows;
        $data = DB::select($sql);
        $result = collect($data);
        return ['total'=>$total,'rows'=>$result];
    }

    //生成tree格式的Json
    public static function DataToTreeJson($data,$key,$name,$fkey=null,$fvalue=null){
        try{
            $data = collect($data);
            $ret_arr = array();
            if(!empty($data)){
                if(empty($fkey)){
                    foreach ($data as $item){
                        $item_arr = array();
                        $item_arr['id'] = $item[$key];
                        $item_arr['text'] = $item[$name];
                        array_push($ret_arr,$item_arr);
                    }
                }else{
                    $children = $data->where($fkey,$fvalue);
                    if(!empty($children)){
                        foreach ($children as $child){
                            $child_arr = array();
                            $child_arr['id'] = $child[$key];
                            $child_arr['text'] = $child[$name];
                            $node = $data->where($fkey,$child[$key]);
                            if(empty($node)){
                                array_push($ret_arr,$child_arr);
                            }else{
                                $child_arr['children'] = CommClass::DataToTreeJson($data,$key,$name,$fkey,$child[$key]);
                                array_push($ret_arr,$child_arr);
                            }
                        }
                    }
                }
            }
            return $ret_arr;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
    ///执行一个Http的Get请求
    public static function HttpGet($url){
        // 初始化一个 cURL 对象
        $curl = curl_init();
        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置header 响应头是否输出
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        // 1如果成功只将结果返回，不自动输出任何内容。如果失败返回FALSE
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 运行cURL，请求网页
        $data = curl_exec($curl);
        // 关闭URL请求
        curl_close($curl);
        return $data;
    }


    /*
     * 执行一个Http的Post请求
     */
     /**
      * @param $url
      * @param null $param
      * @return mixed
      * @throws WxPayException
      */
     public static function HttpPost($url, $param = null){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);


        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//严格校验2
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return json_decode($data, true);
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
    //获取用户人数
    public static  function GetPerosn(){
        return DB::table("xx_user")->count();
    }
    //获取在线人数
    public static function GetOnlinePerosn(){
        try{
            return DB::table("xx_user")->where('online_state',1)->count();
        }catch (\Exception $e) {
            return 0;
        }
    }
     //异或加密
     public static function message_xor(&$strInput)
     {
         $key = [27, 11, 180, 197, 13, 43, 119, 7];
         for ($i = 0; $i < strlen($strInput); $i++)
             //$strInput[$i] = chr(ord($strInput[$i]) ^ $key);
             $strInput[$i] = chr(ord($strInput[$i]) ^ $key[$i%8]);
     }

    //连接服务器
    public static function connServer($message,$port)
    {
        try{
            //字符串长度
            $msg_len = strlen($message);
            //字符串长度不能为0；
            if($msg_len<=0) return "ERROR:NULL";
            //地址
            $ip = "172.18.141.83";
            //$ip = "127.0.0.1";
            //$ip = "login.wangqianhong.com";
            //创建一个socket套接流
            $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
            //连接服务端的套接流，这一步就是使客户端与服务器端的套接流建立联系
            //if (socket_set_nonblock($socket)){
                if(socket_connect($socket,$ip,$port) == false){
                    return "ERROR:".socket_strerror(socket_last_error());
                }else{
                    //向服务端写入字符串信息
                    $reslut = socket_write($socket,$message,$msg_len);
                    if($reslut === false){
                        return "ERROR:".socket_strerror(socket_last_error());
                    }else if($reslut==$msg_len){
                        return "OK";
                    }
                }
//            }else{
//                return "ERROR:false";
//            }

            socket_close($socket);//工作完毕，关闭套接流
        }catch (\Exception $e){
            return "ERROR:".$e->getMessage();
        }
    }

    //提交到游戏服务器
     public static function subServer($user_msg,$uid)
     {
         $Server_Command_User_Base = 219;    //ServerUserBase
         $Server_Type_PHP = 2;  //php
         $message = pack('S4L2',$Server_Type_PHP,$Server_Command_User_Base,0,strlen($user_msg),$uid,0);
         $message .=$user_msg;
         CommClass::message_xor($message);
         $game_wg = config("conf.Game_WG");
         if($uid==1){
             foreach ($game_wg as $wg){
                 $ret_msg = CommClass::connServer($message,$wg);
             }
         }else{
             $port = $game_wg[$uid%2];
             $ret_msg = CommClass::connServer($message,$port);
         }
         if($ret_msg=='OK'){
             return true;
         }else{
             return false;
         }
     }


     /// <summary>
     /// 更新游戏的房卡数量
     /// </summary>
     /// <param name="uid">玩家ID</param>
     /// <param name="type">更新类型</param>
     /// <param name="str">通知内容</param>
     /// <returns></returns>
     public static function UpGameSer($uid,$type,$str=null,$play_num = -1){
        try{
            $ser_user = new ServerUserBase();
            if($uid != 1){
                $user = Users::find($uid);
                if(empty($user)) return false;//玩家不存在，返回
                if($user->online_state != 1) return false;//不在线就不发送
                if($type == 'card')//更新房卡
                    $ser_user->setCardNum($user->roomcard);
                if($type == 'coin')//更新金币
                    $ser_user->setCoinNum($user->gold);
                if($type == 'role')//更新角色
                    $ser_user->setRoleId($user->rid);
            }else{
                if($type == 'msg' && !empty($str)) //更新大厅公告
                    $ser_user->setMessage($str);
                if($type == 'urgent' && !empty($str))//更新紧急通知
                    $ser_user->setUrgent($str);
            }
            $ser_user->setType($type);
            $ser_user->setPlayNum($play_num);
            return CommClass::subServer($ser_user->encode(),$uid);
        }catch (\Exception $e){
            return false;
        }
     }

     ///设置缓存
    public static function SetDataCache()
    {
        //菜单配置表
        Cache::rememberForever('RM',function (){
            return DB::table('role_menu')->get();
        });
        //参数设置
        Cache::rememberForever('PARAMETER',function (){
            return CommClass::GetXml();
        });
    }
    /// <summary>
    /// 读取参数设置的xml
    /// </summary>
    /// <param name="xmlurl"></param>
    /// <returns></returns>
    public static function  GetXml(){
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/Param/Parameter.json')) {
            // 从文件中读取数据到PHP变量
            $json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/Param/Parameter.json');
            // 把JSON字符串转成PHP数组
            return json_decode($json_string, true);
        }
        else{
            return [];
        }
    }

    /*
     * 读取json配置文件
     */
     public static function  GetJson($paramUrl){
         if (file_exists($_SERVER['DOCUMENT_ROOT'].$paramUrl)) {
             // 从文件中读取数据到PHP变量
             $json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].$paramUrl);
             // 把JSON字符串转成PHP数组
             return json_decode($json_string, true);
         }
         else{
             return [];
         }
     }

     /*
      * 保存json配置文件
      */
     public static function SaveJson($param,$paramUrl){
         if (file_exists($_SERVER['DOCUMENT_ROOT'].$paramUrl)) {
             $json_string = json_encode($param);
             file_put_contents($_SERVER['DOCUMENT_ROOT'] . $paramUrl, $json_string);
             return true;
         }else{
             return false;
         }
     }
    /// <summary>
    /// 获取指定的参数
    /// </summary>
    /// <param name="param"></param>
    /// <returns></returns>
    public static function GetParameter($key){
        $param = Cache::rememberForever('PARAMETER', function () {
            return CommClass::GetXml();
        });
        return $param[$key];
    }

    /// <summary>
    /// 将参数保存到xml
    /// </summary>
    /// <param name="xmlurl"></param>
    /// <param name="parameter"></param>
    /// <returns></returns>
    public static function SaveXml($parameter){
        $json_string = json_encode($parameter);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/Param/Parameter.json', $json_string);
        //保存后，重新设置缓存
        Cache::forget('PARAMETER');
        Cache::forever('PARAMETER', $parameter);
    }

     /// <summary>
     /// 插入一条充卡记录
     /// </summary>
    public static function  InsertCard($array){
         try {
             DB::table('xx_sys_cardstrade')->insert($array);
             return true;
         }catch (\Exception $e){
             return false;
         }
    }
     /// <summary>
     /// 充值返卡
     /// </summary>
     /// <param name="buy_id">购卡人id</param>
     /// <param name="buy_number">购卡数量</param>
     /// <returns></returns>
     public static function BackCard($buy_id,$buy_number){
         //星级代理参数
         $staragent =CommClass::GetParameter("staragent");
         //一级代理参数
         $primaryagent = CommClass::GetParameter("primaryagent");
         //二级代理参数
         $twoagent = CommClass::GetParameter("twoagent");
         //三级代理参数
         $threeagent = CommClass::GetParameter("threeagent");
         //代理充值标准参数
         $agentfirst = CommClass::GetParameter("agentfirst");
         //返卡次数参数
         $backnumber = CommClass::GetParameter("backnumber");
         $buy_user=Users::find($buy_id);
         //是否超过返卡次数
         if($backnumber!=0){
             if($buy_user->backcard > $backnumber){
                return;
             }
         }
         //返卡
         if($buy_user->rid == 4 || $buy_user->rid==6){
             if(empty($buy_user->chief_uid) || empty($buy_user->front_uid)) {
                 return;
             }
             $flag = false;
             $table = 'xx_sys_cardstrade';
             //总代返卡
             if($buy_user->chief_uid!=0 && $staragent > 0){
                 $star_number = $buy_number*$staragent/100;
                 if($star_number>0){
                     DB::table($table)->insert(['cbuyid'=>$buy_user->chief_uid,'csellid'=>$buy_id,'cnumber'=>$star_number,'ctype'=>4]);
                     $flag = true;
                     //CommClass::SetGameCard($buy_user->chief_uid);
                 }
             }
             //一级代理返卡
             if($buy_user->front_uid!=0 && $buy_user->chief_uid != $buy_user->front_uid && $primaryagent > 0){
                 $primary_number = $buy_number*$primaryagent/100;
                 if($primary_number>0){
                     DB::table($table)->insert(['cbuyid'=>$buy_user->front_uid,'csellid'=>$buy_id,'cnumber'=>$primary_number,'ctype'=>4]);
                     $flag = true;
                     //CommClass::SetGameCard($buy_user->front_uid);
                 }
                 //二级代理返卡
                 $two_id = CommClass::GetFrontUser($buy_id,2);
                 if(!empty($two_id) && $two_id != $buy_user->chief_uid && $twoagent>0){
                     $two_number = $buy_number*$twoagent/100;
                     if($two_number>0){
                         DB::table($table)->insert(['cbuyid'=>$two_id,'csellid'=>$buy_id,'cnumber'=>$two_number,'ctype'=>4]);
                         $flag = true;
                         //CommClass::SetGameCard($two_id);
                     }
                     //三级代理返卡
                     $three_id = CommClass::GetFrontUser($buy_id,3);
                     if(!empty($three_id) && $three_id != $buy_user->chief_uid && $threeagent > 0) {
                         $three_number = $buy_number * $threeagent / 100;
                         if ($three_number > 0) {
                             DB::table($table)->insert(['cbuyid' => $three_id, 'csellid' => $buy_id, 'cnumber' => $three_number, 'ctype' => 4]);
                             $flag = true;
                            // CommClass::SetGameCard($three_id);
                         }
                     }
                 }
             }
             //返卡后更新返卡次数
             if($flag && $backnumber != 0){
                 $buy_user->backcard += 1;
                 $buy_user->save();
             }
//             if($buy_user->rid==6 && $buy_number>=$agentfirst){
//                 $buy_user->rid=4;
//                 $buy_user->save();
//                 CommClass::UpdateRole($buy_id,4);
//             }
         }
     }

     /// <summary>
     /// 购卡返现
     /// </summary>
     /// <param name="buy_id">购卡人id</param>
     /// <param name="buy_number">购卡金额</param>
     /// <returns></returns>
     public static function BackCash($buy_id,$cash){
        //上级
         $oneback =CommClass::GetParameter("upper_one");
         //上上级
         $twoback = CommClass::GetParameter("upper_two");
         //剩余金额
         $balance = $cash;
         //消费金额小于等于0，return
         if($cash <= 0)
             return;
         //////////////////////////////////代理返利/////////////////////////////////////
         $buy_user = Users::find($buy_id);
         //如果购买的玩家为空，或者不为代理，直接return
         if(empty($buy_user) || $buy_user->rid == 5)
             return;
         //上级返利
         //上级为空，或者上级不为代理，直接return
         $front = Users::find($buy_user->front_uid);
         if(!empty($front) && $front->rid == 2){
             //计算上级返利
             $return_one = $cash*$oneback/100;
             if($return_one > 0){
                 //保存返利信息
                 DB::table("xx_wx_backgold")->insert(
                     ['get_id'=>$buy_user->front_uid,
                         'back_id'=>$buy_id,
                         'backgold'=>$return_one,
                         'gold'=>$cash,
                         'ratio'=>$oneback,
                         'level'=>1]);
                 $balance -=  $return_one;
             }
             //上上级返利
             //上上级为空，或者上上级不为代理，直接返回
             $upper_level = Users::find($front->front_uid);
             if(!empty($upper_level) && $upper_level->rid == 2){
                 //计算上上级返利
                 $return_two = $cash*$twoback/100;
                 if($return_two > 0){
                     //保存返利记录
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$front->front_uid,
                             'back_id'=>$buy_id,
                             'backgold'=>$return_two,
                             'gold'=>$cash,
                             'ratio'=>$twoback,
                             'level'=>2]);
                     $balance -=  $return_two;
                 }
             }
         }
         //////////////////////////////////总代&特级代理返利/////////////////////////////////////
         //vip代理返利
         $vip_scale = 0;
         if(!empty($buy_user->vip_aisle)){
             $vip_user = DB::table('xx_user')->where('aisle',$buy_user->vip_aisle)->first();
             if(!empty($vip_user) && $vip_user->rid == 6){
                 $vip_scale = CommClass::getProxyScale($vip_user->uid);
                 $return_vip = $balance*$vip_scale/100;
                 if($return_vip > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$vip_user->uid,
                             'back_id'=>$buy_id,
                             'backgold'=>$return_vip,
                             'gold'=>$balance,
                             'ratio'=>$vip_scale,
                             'level'=>1]);
                 }
             }
         }
         //总代返利
         $scale = 0 ;
         if(!empty($buy_user->chief_aisle)){
            $chief = DB::table('xx_user')->where('aisle',$buy_user->chief_aisle)->first();
            if(!empty($chief) && $chief->rid == 3){
                $scale = CommClass::getProxyScale($chief->uid);
                $scale -=$vip_scale;
                $return_chief = $balance*$scale/100;
                if($return_chief > 0){
                    //保存返利信息
                    DB::table("xx_wx_backgold")->insert(
                        ['get_id'=>$chief->uid,
                            'back_id'=>$buy_id,
                            'backgold'=>$return_chief,
                            'gold'=>$balance,
                            'ratio'=>$scale,
                            'level'=>1]);
                }
            }
         }
         //特级代理返利
         $super_scale = 0;
         if(!empty($buy_user->super_aisle)){
             $super = DB::table('xx_user')->where('aisle',$buy_user->super_aisle)->first();
             if(!empty($super) && $super->rid == 4){
                 $super_scale = CommClass::getProxyScale($super->uid);
                 $super_scale = $super_scale - $scale - $vip_scale;
                 $return_super = $balance*$super_scale/100;
                 if($return_super > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$super->uid,
                             'back_id'=>$buy_id,
                             'backgold'=>$return_super,
                             'gold'=>$balance,
                             'ratio'=>$super_scale,
                             'level'=>1]);
                 }

             }
         }
     }

     /*
      * 获取总代的提成比例
      * $uid：总代ID
      */
     public static function getProxyScale($uid){
        //总代的提成规则
        $data = DB::table('xx_sys_proxyscale')->where('uid',$uid)->first();
        if(empty($data)){ //如果为空，则取通用比例
            $data = DB::table('xx_sys_proxyscale')->where('uid',0)->first();
        }
        return $data->scale;
     }

     /*
      * 判断玩家是否是代理或者总代
      */
     private static function isAgent($uid){
         try{
             if(empty($uid)){
                 return false;
             }
             else{
                 $user = Users::find($uid);
                 if(empty($user) || $user->rid !=2)
                     return false;
                 else
                     return true;
             }
         }catch (\Exception $e){
             return false;
         }
     }
     /// <summary>
     /// 支付成功后，更新玩家钻石
     /// </summary>
     /// <param name="order_no">订单号</param>
     /// <returns></returns>
     public static function SetPlayerCard($order_no){
        try{
            $wx_order = DB::table('v_buycard_list')->where([['nonce', $order_no],['status',0]])->first();
            if (!empty($wx_order)) {
                $total = $wx_order->total;
                //首冲
                if($wx_order->flag == 0){
                    //首冲套餐
                    if($wx_order->isfirst == 1){
                        CommClass::InsertCard(['cbuyid' => $wx_order->userid, 'csellid' => 999, 'cnumber' => 400]) ;
                    }else{
                        CommClass::InsertCard(['cbuyid' => $wx_order->userid, 'csellid' => 999, 'cnumber' => $wx_order->cardnum]);
                    }
                    $up_arr = ['flag' => 1];
                    if($wx_order->rid == 5){
                        $up_arr["rid"] = 2 ;
                    }
                    if(empty($wx_order->front_uid)
                        && !empty($wx_order->front)
                        && $wx_order->front != $wx_order->userid
                        && !empty($wx_order->frid)
                        && $wx_order->frid == 2){
                        $up_arr["front_uid"] = $wx_order->front ;
                        //绑定代理送100钻石
                        CommClass::InsertCard(['cbuyid' => $wx_order->userid, 'csellid' => 999, 'cnumber' => 100, 'buytype' => 3]);
                        //上级送100返利
                        DB::table("xx_wx_backgold")->insert(
                            ['get_id'=>$wx_order->front,
                                'back_id'=>$wx_order->userid,
                                'backgold'=>100,
                                'gold'=>$wx_order->total,
                                'ratio'=>0,
                                'level'=>5]);
                        //$total -= 100;
                    }
                    //更新我的角色
                    DB::table('xx_user')->where('uid', $wx_order->userid)->update($up_arr);
                    //更新玩家角色
                    CommClass::UpGameSer($wx_order->userid,'role');
                    //首次绑定代理返利
                    //CommClass::FirstBindBackCash($wx_order->userid, $total);
                }else{
                    CommClass::InsertCard(['cbuyid' => $wx_order->userid, 'csellid' => 999, 'cnumber' => $wx_order->cardnum]);
                    //绑定代理
                    if(empty($wx_order->front_uid)
                        && !empty($wx_order->front)
                        && $wx_order->front != $wx_order->userid
                        && !empty($wx_order->frid)
                        && $wx_order->frid == 2){
                        DB::table('xx_user')->where('uid',$wx_order->userid)->update(['front_uid'=>$wx_order->front]);
                        //绑定代理送100钻石
                        CommClass::InsertCard(['cbuyid' => $wx_order->userid, 'csellid' => 999, 'cnumber' => 100, 'buytype' => 3]);

                        DB::table("xx_wx_backgold")->insert(
                            ['get_id'=>$wx_order->front,
                                'back_id'=>$wx_order->userid,
                                'backgold'=>100,
                                'gold'=>$wx_order->total,
                                'ratio'=>0,
                                'level'=>5]);
                        //$total -= 100;
                        //首次绑定代理返利
                        //CommClass::FirstBindBackCash($wx_order->userid, $total);
                    }
                }
                //代理返利
                CommClass::BackCash($wx_order->userid, $total);
                //更新订单状态
                DB::table('xx_wx_buycard')->where('nonce', $order_no)->update(['status'=>1]);
                //更新游戏的钻石数量
                CommClass::UpGameSer($wx_order->userid,'card');//玩家的钻石
            }
        }catch (\Exception $e){

        }
     }


     /*
      * APP玩家购买，支付成功更新砖石
      */
    public static function SetAppPlayerCard($order_no){
        $wx_order = DB::table('xx_wx_buycard')->where([['nonce', $order_no],['status',0]])->first();
        if (!empty($wx_order)) {
            CommClass::InsertCard(['cbuyid' => $wx_order->userid, 'csellid' => 999, 'cnumber' => $wx_order->cardnum]);
            //玩家购买返现
            CommClass::PlayBackCash($wx_order->userid,$wx_order->total);
            //更新订单状态
            DB::table('xx_wx_buycard')->where('nonce', $order_no)->update(['status'=>1]);
            //更新游戏的钻石数量
            CommClass::UpGameSer($wx_order->userid,'card');//玩家的钻石
        }
    }

     ///发送短信
     /// $tel:手机号
     /// $template：模板ID
     /// $param：发送参数
     public static function send_sms($tel,$template,$param = null){
         try{
             $params = array ();
             // *** 需用户填写部分 ***
             // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
             $accessKeyId = "LTAIDIQbWahR7Tic";
             $accessKeySecret = "vAFaoKBtzQ37E1sP1EFB1deUVWnwBV";

             // fixme 必填: 短信接收号码
             $params["PhoneNumbers"] = $tel;

             // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
             $params["SignName"] = "休休科技";
             // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
             $params["TemplateCode"] = $template;
             // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
             if(!empty($param))
                 $params['TemplateParam'] = $param;
             // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
             if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
                 $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
             }
             // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
             $helper = new SignatureHelper();

             // 此处可能会抛出异常，注意catch
             $content = $helper->request(
                 $accessKeyId,
                 $accessKeySecret,
                 "dysmsapi.aliyuncs.com",
                 array_merge($params, array(
                     "RegionId" => "cn-hangzhou",
                     "Action" => "SendSms",
                     "Version" => "2017-05-25",
                 ))
             );
             return $content;
         }catch (\Exception $e){
             return $e->getMessage();
         }
     }


     //玩家购买返现
     public static function PlayBackCash($uid,$cash){
         $play = Users::find($uid);
         //代理返利
         if(!empty($play->chief_uid)){
             $surplus = $cash;
             //玩家的推荐人必须是代理，才返现
             $font = Users::find($play->chief_uid);
             if(!empty($font) && $font->rid == 2 ){
                 //返利比例
                 $invitation = CommClass::GetParameter("invitation");
                 $back_cash = $cash*$invitation/100;
                 if($back_cash>0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$play->chief_uid,
                             'back_id'=>$uid,
                             'backgold'=>$back_cash,
                             'gold'=>$cash,
                             'ratio'=>$invitation,
                             'level'=>3]);
                     $surplus -= $back_cash;
                 }
             }
         }
         //////////////////////////////////总代&特级代理返利/////////////////////////////////////
         //vip代理返利
         $vip_scale = 0;
         if(!empty($play->vip_aisle)){
             $vip_user = DB::table('xx_user')->where('aisle',$play->vip_aisle)->first();
             if(!empty($vip_user) && $vip_user->rid == 6){
                 $vip_scale = CommClass::getProxyScale($vip_user->uid);
                 $return_vip = $surplus*$vip_scale/100;
                 if($return_vip > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$vip_user->uid,
                             'back_id'=>$uid,
                             'backgold'=>$return_vip,
                             'gold'=>$surplus,
                             'ratio'=>$vip_scale,
                             'level'=>3]);
                 }
             }
         }
         //总代返利
         $scale = 0 ;
         if(!empty($play->chief_aisle)){
             $chief = DB::table('xx_user')->where('aisle',$play->chief_aisle)->first();
             if(!empty($chief) && $chief->rid == 3){
                 $scale = CommClass::getProxyScale($chief->uid);
                 $scale -=$vip_scale;
                 $return_chief = $surplus*$scale/100;
                 if($return_chief > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$chief->uid,
                             'back_id'=>$uid,
                             'backgold'=>$return_chief,
                             'gold'=>$surplus,
                             'ratio'=>$scale,
                             'level'=>3]);
                 }
             }
         }
         //特级代理返利
         $super_scale = 0;
         if(!empty($play->super_aisle)){
             $super = DB::table('xx_user')->where('aisle',$play->super_aisle)->first();
             if(!empty($super) && $super->rid == 4){
                 $super_scale = CommClass::getProxyScale($super->uid);
                 $super_scale = $super_scale - $scale - $vip_scale;
                 $return_super = $surplus*$super_scale/100;
                 if($return_super > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$super->uid,
                             'back_id'=>$uid,
                             'backgold'=>$return_super,
                             'gold'=>$surplus,
                             'ratio'=>$super_scale,
                             'level'=>3]);
                 }

             }
         }
     }

     //首次绑定代理返现
     public static function FirstBindBackCash($uid,$cash){
         //消费金额小于等于0，return
         if($cash <= 0)
             return;
         //////////////////////////////////代理返利/////////////////////////////////////
         $buy_user = Users::find($uid);
         //如果购买的玩家为空，或者不为代理，直接return
         if(empty($buy_user) || $buy_user->rid == 5)
             return;

         //vip代理返利
         $vip_scale = 0;
         if(!empty($buy_user->vip_aisle)){
             $vip_user = DB::table('xx_user')->where('aisle',$buy_user->vip_aisle)->first();
             if(!empty($vip_user) && $vip_user->rid == 6){
                 $vip_scale = CommClass::getProxyScale($vip_user->uid);
                 $return_vip = $cash*$vip_scale/100;
                 if($return_vip > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$vip_user->uid,
                             'back_id'=>$uid,
                             'backgold'=>$return_vip,
                             'gold'=>$cash,
                             'ratio'=>$vip_scale,
                             'level'=>1]);
                 }
             }
         }
         //总代返利
         $scale = 0 ;
         if(!empty($buy_user->chief_aisle)){
             $chief = DB::table('xx_user')->where('aisle',$buy_user->chief_aisle)->first();
             if(!empty($chief) && $chief->rid == 3){
                 $scale = CommClass::getProxyScale($chief->uid);
                 $scale -=$vip_scale;
                 $return_chief = $cash*$scale/100;
                 if($return_chief > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$chief->uid,
                             'back_id'=>$uid,
                             'backgold'=>$return_chief,
                             'gold'=>$cash,
                             'ratio'=>$scale,
                             'level'=>1]);
                 }
             }
         }
         //特级代理返利
         $super_scale = 0;
         if(!empty($buy_user->super_aisle)){
             $super = DB::table('xx_user')->where('aisle',$buy_user->super_aisle)->first();
             if(!empty($super) && $super->rid == 4){
                 $super_scale = CommClass::getProxyScale($super->uid);
                 $super_scale = $super_scale - $scale - $vip_scale;
                 $return_super = $cash*$super_scale/100;
                 if($return_super > 0){
                     //保存返利信息
                     DB::table("xx_wx_backgold")->insert(
                         ['get_id'=>$super->uid,
                             'back_id'=>$uid,
                             'backgold'=>$return_super,
                             'gold'=>$cash,
                             'ratio'=>$super_scale,
                             'level'=>1]);
                 }

             }
         }

     }

    /*
     * 获取微信头像Base64格式
     */
     public static function GetWxHeadForBase64($url) {
         // 设置运行时间为无限制
         set_time_limit ( 0 );
         $url = trim ( $url );
         $curl = curl_init ();
         // 设置你需要抓取的URL
         curl_setopt ( $curl, CURLOPT_URL, $url );
         // 设置header
         curl_setopt ( $curl, CURLOPT_HEADER, 0 );
         // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
         curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
         // 运行cURL，请求网页
         $file = curl_exec ( $curl );
         // 关闭URL请求
         curl_close ( $curl );
         // 将文件写入获得的数据
         $base64 = chunk_split(base64_encode($file));
         return $base64;
     }


     /*
      * url参数加密
      */
     public static function encrypt($param){
         $return_str = "";
         $key = 112;
         for ($i = 0; $i < strlen($param); $i++){
             if(empty($return_str)){
                 $return_str .= (ord($param[$i])^ $key) ;
             }else{
                 $return_str .='-'. (ord($param[$i])^ $key);
             }
         }
         return $return_str;
     }

}