<?php
namespace App\Common;

use App\Models\Users;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

    //获取用户人数
    public static  function GetPerosn(){
        return DB::table("xx_user")->count();
    }
    //获取在线人数
    public static function GetOnlinePerosn(){
        try{
            return 1;
        }catch (\Exception $e) {
            return 0;
        }
    }
     /// <summary>
     /// 更新游戏的房卡数量
     /// </summary>
     /// <param name="uid">玩家ID</param>
     /// <returns></returns>
     public static function SetGameCard($uid){
//        try{
//            $user = Users::find($uid);
//            if(empty($user)) return false;
//            $url = "http://pdk.csppyx.com:5937/3001?".$uid."&".$user->gold;
//            $response = CommClass::HttpGet($url);
//            if($response==1) {
//                return true;
//            }else{return false;}
//        }catch (\Exception $e){
//            return false;
//        }
     }


     ///设置缓存
    public static function SetDataCache()
    {
        //菜单配置表
        Cache::rememberForever('RM',function (){
            return DB::table('role_menu')->get();
        });
        //角色
        Cache::rememberForever('ROLE',function (){
            return DB::table('xx_sys_role')->get();
        });
        //菜单表
        Cache::rememberForever('MENU',function (){
            return DB::table('xx_sys_menu')->get();
        });
        //权限配置表
        Cache::rememberForever('JUR',function (){
            return DB::table('xx_sys_power')->get();
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
             $table = 'xx_sys_cardstrade';
             DB::table($table)->insert($array);
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
         $buy_user = Users::find($buy_id);
         if(!empty($buy_user->front_uid)){
             $front = Users::find($buy_user->front_uid);
             if(!empty($front)){
                 $return_one = $cash*$oneback/100;
                 DB::table("xx_wx_backgold")->insert(
                     ['get_id'=>$buy_user->front_uid,
                         'back_id'=>$buy_id,
                         'backgold'=>$return_one,
                         'gold'=>$cash,
                         'ratio'=>$oneback,
                         'level'=>1]);
                 if(!empty($front->front_uid)){
                     $front_front = Users::find($front->front_uid);
                     if(!empty($front_front)){
                         $return_two = $cash*$twoback/100;
                         DB::table("xx_wx_backgold")->insert(
                             ['get_id'=>$front->front_uid,
                                 'back_id'=>$buy_id,
                                 'backgold'=>$return_two,
                                 'gold'=>$cash,
                                 'ratio'=>$twoback,
                                 'level'=>2]);
                     }
                 }
             }
         }
     }
}