<?php

namespace App\Http\Controllers;
require_once("redis_data_php.pb.php");
use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Foo\Record;
use Foo\RecordInfo;
use Foo\BigRecord;
use Foo\BigRecordInfo;
use Foo\PlayerInfo;

class GameSericeController extends Controller
{
    //绑定邀请码
    public function Invitation($uid,$code){
        try{
            $flag = 0;
            if(!empty($code) && !empty($uid) && $code!=$uid){
                $user = Users::find($uid);
                $front = Users::find($code);
                if(!empty($user) && !empty($front) ){
                    if($front->rid==2 && empty($user->front_uid)){
                        $user->front_uid = $code;
                        $user->save();
                        $flag = 1;
                    }
                }
            }
            //赠送砖石
            if($flag==1){
                $num = CommClass::GetParameter('invitation');
                CommClass::InsertCard(['cbuyid' => $uid, 'csellid' => 999, 'cnumber' =>$num , 'ctype' => 2]);
                CommClass::InsertCard(['cbuyid' => $code, 'csellid' => 999, 'cnumber' =>$num , 'ctype' => 2]);
            }
            return $flag;
        }catch (\Exception $e){
            return 0;
        }
    }

    //获取绑定我的玩家
    public function GetMyPlayer($uid){
        try{
            $names = Users::where('front_uid',$uid)->select("nickname")->get();
            $json=[];
            foreach ($names as $name){
                Array_push($json,$name->nickname);
            }
            return json_encode($json);
        }catch (\Exception $e){
            return "";
        }
    }

    //消费记录
    public function Consume($uid){
        try{
            $record = DB::table("nxmj_player_record")->where([["player_id",'=',$uid],["roomcard",">",0]])
                ->select('create_time', 'roomcard')
                ->orderBy('create_time', 'desc')
                ->offset(0)
                ->limit(50)->get();
            return json_encode($record);
        }catch (\Exception $e){
            return "";
        }
    }

    //充值记录
    public function Recharge($uid){
        try{
            $record = [];
            return json_encode($record);
        }catch (\Exception $e){
            return "";
        }
    }

    //胜率查询
    public function  win($uid){
        try{
            $list = DB::table("nxmj_player_record")->where("player_id",$uid)->get();
            $win = collect($list)->where('score','>',0)->count();
            $str = sprintf("%.2f",$win/count($list)*100);
            $mon_list = DB::table("nxmj_player_record")
                ->where("player_id",$uid)
                ->whereBetween('create_time',[date('Y-m-01'),date('Y-m-t 23:59:59')])
                ->get();
            $mon_win = collect($mon_list)->where('score','>',0)->count();
            $str .= '|'.$mon_win.'|'.count($mon_list);
            return $str;
        }catch (\Exception $e){
            return "";
        }
    }

    //分享送钻
    public function give($uid){
        try{
            $data = DB::table('pp_agentsys_cardstrade')
                ->where([['cbuyid',$uid],['ctype',4]])
                ->whereBetween('ctradedate',[date('Y-m-d'),date('Y-m-d 23:59:59')])
                ->get();
            if(empty($data) || count($data)<=0){
                $num = CommClass::GetParameter('share');
                CommClass::InsertCard(['cbuyid' => $uid, 'csellid' => 999, 'cnumber' =>$num , 'ctype' => 4]);
                return $num;
            }else{
                return 0;
            }
        }catch (\Exception $e){
            return 0;
        }
    }

    //分享页面
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

    //总战绩
    public function GetRecord($uid){
        try {
            if ($uid == '6666') {
                return $this->checkRecord($uid);
            } else {
                $sql = <<<EOT
               select * from v_player_record where userinfo like '%$uid%' ORDER BY r_time DESC
EOT;
                //var_dump($sql);
                $records = DB::select($sql);
                if (!empty($records)) {
                    $ret_Record = new Record();
                    $rf = new RepeatedField(GPBType::MESSAGE, \Foo\RecordInfo::class);
                    foreach ($records as $record) {
                        $add_RecordInfo = new RecordInfo();
                        $add_RecordInfo->setGametype($record->gametype);
                        $add_RecordInfo->setRoomId($record->roomid);
                        $add_RecordInfo->setNumGame($record->number);
                        $arry_userinfo = explode('|',$record->userinfo);
                        if(!empty($arry_userinfo)){
                            for ($i=0;$i<sizeof($arry_userinfo);$i++){
                                $arry = explode('&',$arry_userinfo[$i]);
                                if(!empty($arry)){
                                    switch ($i) {
                                        case 0:
                                            $add_RecordInfo->setUidOne($arry[0]);
                                            $add_RecordInfo->setNikenameOne($arry[1]);
                                            $add_RecordInfo->setScoreSumOne($arry[2]);
                                            break;
                                        case 1:
                                            $add_RecordInfo->setUidTwo($arry[0]);
                                            $add_RecordInfo->setNikenameTwo($arry[1]);
                                            $add_RecordInfo->setScoreSumTwo($arry[2]);
                                            break;
                                        case 2:
                                            $add_RecordInfo->setUidThree($arry[0]);
                                            $add_RecordInfo->setNikenameThree($arry[1]);
                                            $add_RecordInfo->setScoreSumThree($arry[2]);
                                            break;
                                        case 3:
                                            $add_RecordInfo->setUidFour($arry[0]);
                                            $add_RecordInfo->setNikenameFour($arry[1]);
                                            $add_RecordInfo->setScoreSumFour($arry[2]);
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                        $add_RecordInfo->setCreateTime($record->r_time);
                        $rf->offsetSet(null, $add_RecordInfo);
                    }
                    $ret_Record->setRecordInfo($rf);
                    return $ret_Record->encode();
                } else {
                    return "";
                }
            }
        } catch(\Exception $e){
            //var_dump($e->getMessage());
            return "";
        }

    }
    //单局战绩
    public function BigRecord($roomid,$time){
        try{
            $sql=<<<EOT
                select indexs,
                         uid1,
                         nickname1,
                         score1,
                         uid2,
                         nickname2,
                         score2,
                         uid3,
                         nickname3,
                         score3,
                         uid4,
                         nickname4,
                         score4,
                         record_id,
                         date_format(create_time,'%Y-%m-%d %H:%i:%s') r_time,
                         date_format(end_time,'%Y-%m-%d %H:%i:%s') e_time
                from nxmj_record_table
                where room_id=$roomid and create_time='$time'
                ORDER BY indexs ASC
EOT;
            $bigrecordinfo = DB::select($sql);
            if(!empty($bigrecordinfo)) {
                $big_Record = new BigRecord();
                $rf = new RepeatedField(GPBType::MESSAGE,\Foo\BigRecordInfo::class);
                foreach ($bigrecordinfo as $bigrecord){
                    $brinfo =  new BigRecordInfo();
                    $brinfo->setIndexs($bigrecord->indexs);
                    $brinfo->setUidOne($bigrecord->uid1);
                    $brinfo->setNikenameOne($bigrecord->nickname1);
                    $brinfo->setScoreOne($bigrecord->score1);
                    $brinfo->setUidTwo($bigrecord->uid2);
                    $brinfo->setNikenameTwo($bigrecord->nickname2);
                    $brinfo->setScoreTwo($bigrecord->score2);
                    $brinfo->setUidThree($bigrecord->uid3);
                    $brinfo->setNikenameThree($bigrecord->nickname3);
                    $brinfo->setScoreThree($bigrecord->score3);
                    $brinfo->setUidFour($bigrecord->uid4);
                    $brinfo->setNikenameFour($bigrecord->nickname4);
                    $brinfo->setScoreFour($bigrecord->score4);
                    $brinfo->setPlayback($bigrecord->record_id);
                    $brinfo->setCreateTime($bigrecord->e_time);
                    //$brinfo->setEndTime($bigrecord->e_time);
                    $rf->offsetSet(null,$brinfo);
                }
                $big_Record->setBigrecordInfo($rf);
                return $big_Record->encode();
            }
            else{
                return "";
            }
        }catch (\Exception $e) {

            return "";
        }
    }
    //玩家信息
    public function GetPlayer($uid){
        try{
            $player = DB::table('pp_user')->where('uid',$uid)->first();
            $paoma = DB::table('pp_agentsys_message')->where('mtype',1)->value('mcontent');
            $jjtz = DB::table('pp_agentsys_message')->where('mtype',3)->value('mcontent');
            $baju = 0; //CommClass::HttpGet("http://agent.csppyx.com/GetParam/ten");
            $shiliu = 0;// CommClass::HttpGet("http://agent.csppyx.com/GetParam/twenty");
            $player_info = new PlayerInfo();
            if(!empty($player)){
                $player_info->setCardNum($player->roomcard);
                $player_info->setBubbleNum($player->front_uid);
                $player_info->setRoleId($player->rid);
            }
            $player_info->setBajuNum($baju);
            $player_info->setShiliuNum($shiliu);
            $player_info->setPaoma($paoma.'|'.$jjtz);
            return $player_info->encode();
        }catch (\Exception $e)
        {
            //var_dump($e->getMessage());
            return "";
        }
    }

    public function getPlayback($rid){
        try{
            return DB::table('nxmj_record_table')->where('record_id',$rid)->value('playback');
        }catch (\Exception $e){
            return "";
        }
    }

    //根据房间ID查询战绩
    public function checkRecord($id){
        try{
            if($id==='6666'){
                $roomid = '257916';
                $sql=<<<EOT
                select * from v_player_record where roomid = $roomid ORDER BY r_time DESC
EOT;
                $records = DB::select($sql);
                if(!empty($records)) {
                    $ret_Record = new Record();
                    $rf = new RepeatedField(GPBType::MESSAGE,\Foo\RecordInfo::class);
                    foreach ($records as $record){
                        $add_RecordInfo = new RecordInfo();
                        $add_RecordInfo->setGametype($record->gametype);
                        $add_RecordInfo->setRoomId($record->roomid);
                        $add_RecordInfo->setNumGame($record->number);
                        $arry_userinfo = explode('|',$record->userinfo);
                        if(!empty($arry_userinfo)){
                            for ($i=0;$i<sizeof($arry_userinfo);$i++){
                                $arry = explode('&',$arry_userinfo[$i]);
                                if(!empty($arry)){
                                    switch ($i) {
                                        case 0:
                                            $add_RecordInfo->setUidOne($arry[0]);
                                            $add_RecordInfo->setNikenameOne($arry[1]);
                                            $add_RecordInfo->setScoreSumOne($arry[2]);
                                            break;
                                        case 1:
                                            $add_RecordInfo->setUidTwo($arry[0]);
                                            $add_RecordInfo->setNikenameTwo($arry[1]);
                                            $add_RecordInfo->setScoreSumTwo($arry[2]);
                                            break;
                                        case 2:
                                            $add_RecordInfo->setUidThree($arry[0]);
                                            $add_RecordInfo->setNikenameThree($arry[1]);
                                            $add_RecordInfo->setScoreSumThree($arry[2]);
                                            break;
                                        case 3:
                                            $add_RecordInfo->setUidFour($arry[0]);
                                            $add_RecordInfo->setNikenameFour($arry[1]);
                                            $add_RecordInfo->setScoreSumFour($arry[2]);
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            }
                        }
                        $add_RecordInfo->setCreateTime($record->r_time);
                        $rf->offsetSet(null, $add_RecordInfo);
                    }
                    $ret_Record->setRecordInfo($rf);
                    return $ret_Record->encode();
                }
                else{
                    return "";
                }
            }else{return "";}
        }catch (\Exception $e){
            return "";
        }
    }
}
