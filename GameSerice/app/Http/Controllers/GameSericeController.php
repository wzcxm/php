<?phpnamespace App\Http\Controllers;use App\Common\CommonFunc;use App\User;use Carbon\Carbon;use Google\Protobuf\Internal\GPBType;use Google\Protobuf\Internal\RepeatedField;use Illuminate\Support\Facades\DB;use Xxgame\Business;use Xxgame\BusinessList;use Xxgame\Playerinfo;use Xxgame\TeaInfo;use Xxgame\TeaList;use Xxgame\TeaPlayer;use Xxgame\TeaPlayerList;class GameSericeController extends Controller{    ///获取茶楼列表    /// $uid：玩家ID    /// $key：KEY    public function GetTeaList($uid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($uid)) return "";            $sql =<<<EOT                select t.* from v_tea t left join xx_sys_teas s on  s.tea_id=t.tea_id where s.uid=$uidEOT;            $tea_data = DB::select($sql);            if(empty($tea_data)) return "";            $tesList = new TeaList();            $tea_rf = new RepeatedField(GPBType::MESSAGE, \Xxgame\TeaInfo::class);            foreach ($tea_data as $tea){                $teainfo = new TeaInfo();                $teainfo->setTeaId($tea->tea_id);                $teainfo->setUid($tea->uid);                $teainfo->setForbid($tea->forbid);                $teainfo->setMsg($tea->msg);                $teainfo->setTeaName($tea->tea_name);                $teainfo->setMethod1($tea->method1);                $teainfo->setType1($tea->type1);                $teainfo->setHall1($tea->hall1);                $teainfo->setMethod2($tea->method2);                $teainfo->setType2($tea->type2);                $teainfo->setHall2($tea->hall2);                $teainfo->setMethod3($tea->method3);                $teainfo->setType3($tea->type3);                $teainfo->setHall3($tea->hall3);                $teainfo->setNickname($tea->nickname);                $teainfo->setHead($tea->head_img_url);                $tea_rf->offsetSet(null, $teainfo);            }            $tesList->setTeaList($tea_rf);            return $tesList->encode();        }catch (\Exception $e){            return "";        }    }    ///获取茶楼排行榜    /// $key：KEY    public function GetTeaOrderByList($sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            $tea_data = DB::table('v_tea')->where('ody','>',0)->orderBy('ody','asc')->get();            if(empty($tea_data)) return "";            $tesList = new TeaList();            $tea_rf = new RepeatedField(GPBType::MESSAGE, \Xxgame\TeaInfo::class);            foreach ($tea_data as $tea){                $teainfo = new TeaInfo();                $teainfo->setTeaId($tea->tea_id);                $teainfo->setUid($tea->uid);                $teainfo->setForbid($tea->forbid);                $teainfo->setMsg($tea->msg);                $teainfo->setTeaName($tea->tea_name);                $teainfo->setNickname($tea->nickname);                $teainfo->setHead($tea->head_img_url);                $tea_rf->offsetSet(null, $teainfo);            }            $tesList->setTeaList($tea_rf);            return $tesList->encode();        }catch (\Exception $e){            return "";        }    }    ///获取茶楼玩家列表    /// $teaid：茶楼ID    /// $key：KEY    public function GetTeaPlayerList($teaid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($teaid)) return "";            $sql = <<<EOT            select t.*,u.nickname,u.online_state from xx_sys_teas t left join xx_user u on  u.uid=t.uid where t.tea_id = $teaidEOT;            $player_data =  DB::select($sql);            if(empty($player_data)) return "";            $teaPlayerList =  new TeaPlayerList();            $player_rf = new RepeatedField(GPBType::MESSAGE, \Xxgame\TeaPlayer::class);            foreach ($player_data as $player){                $teaplayer = new TeaPlayer();                $teaplayer->setTeaId($player->tea_id);                $teaplayer->setNickname($player->nickname);                $teaplayer->setUid($player->uid);                $teaplayer->setState($player->state);                $teaplayer->setManager($player->manager);                $teaplayer->setHallId($player->hall_id);                $teaplayer->setWinnum($player->winnum);                $teaplayer->setRemarks($player->remarks);                $teaplayer->setNumbers($player->numbers);                $teaplayer->setDate($player->create_time);                $teaplayer->setOnline($player->online_state);                $player_rf->offsetSet(null,$teaplayer);            }            $teaPlayerList->setPlayerList($player_rf);            return $teaPlayerList->encode();        }catch (\Exception $e){            return "";        }    }    ///修改茶楼玩家备注    /// $teaid：茶楼ID    /// $uid：玩家ID    /// $remark：备注内容    /// $key：KEY    public function updateRemark($teaid,$uid,$remark,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "0";            if(empty($teaid) || empty($uid)) return "0";            DB::table("xx_sys_teas")->where([['tea_id',$teaid],['uid',$uid]])->update(['remarks'=>urldecode($remark)]);            return "1";        }catch (\Exception $e){            return "0";        }    }    //验证签名方法    private  function checkSign($sign){        try{            if(empty($sign)) return false;            if(decrypt($sign) == env('SIGN'))                return true;            else                return false;        }catch (\Exception $e){            return false;        }    }    ///获取版本号    /// $version：版本号    /// $type：系统平台1：苹果；2：安卓    /// $res：资源    /// $src：资源地址    public function GetVersion($version,$type){        $ret = "";        if($type==1) { //苹果版            if ($version == 2.0) {                $ret = "2";            } else {                if($version < 1.0){                    $ret = "1";                }else{                    $ret = "0";                }            }        }else if($type==2){           if($version < 1.0){                $ret = "1";            }else{                $ret = "0";            }        }else{ }        if($ret=="0"){            $ret .= "|1|1|1";        }        return $ret;    }    ///获取回放数据    /// $gtype：游戏类型    /// $rid：战绩ID    /// $sign：签名    public function getPlayback($gtype,$rid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($gtype) || empty($rid)) return "";            $gamt_type = config('conf.GameType');            $table = $gamt_type->get($gtype);            return DB::table($table)->where('record_id',$rid)->value('playback');        }catch (\Exception $e){            return "";        }    }    ///获取玩家的总战绩    /// $uid：玩家ID    /// $g_type：游戏类型    /// $sign：签名    public function GetRecord($g_type,$uid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($uid) || empty($g_type)) return "";        }catch (\Exception $e){            return "";        }    }    ///获取玩家大局战绩    /// $roomid：房间号    /// $time：开房时间    /// $g_type：游戏类型    /// $sign：签名    public function BigRecord($roomid,$time,$g_type,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($roomid) || empty($time) || empty($g_type)) return "";        }catch (\Exception $e){            return "";        }    }    ///短信发送    /// $uid:玩家ID    /// $type:发送类型    public function sendCodeSms($tel){        try{            $code = rand(1000,9999 );            //存入缓存            $expiresAt = Carbon::now()->addMinutes(5);            \Cache::put($tel,$code,$expiresAt);            $param = ['code'=>$code];            CommonFunc::send_sms($tel,'SMS_120375736',$param);        }catch (\Exception $e){        }    }    ///茶楼经营状态    /// $teaid:茶楼ID    /// $sign：签名    public function getBusList($teaid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($teaid)) return "";            $sql = <<<EOT            select date_day,                    count(tea_id) as tea_num,                    count((case when hall<9 then 1 end)) as hall_num_1,                    count((case when hall>8 and hall<17 then 2 end)) as hall_num_2,                    count((case when hall<16 then 3 end)) as hall_num_3            from v_tea_rec             where tea_id = $teaid            group by date_dayEOT;            $rec = DB::select($sql);            if(empty($rec)) return "";            $bus_list =  new BusinessList();            $bus_rf = new RepeatedField(GPBType::MESSAGE, \Xxgame\Business::class);            foreach ($rec as $item){                $bus = new Business();                $bus->setTeaNum($item->tea_num);                $bus->setHallNum1($item->hall_num_1);                $bus->setHallNum2($item->hall_num_2);                $bus->setHallNum3($item->hall_num_3);                $bus->setDateDay($item->date_day);                $bus_rf->offsetSet(null,$bus);            }            $bus_list->setBusList($bus_rf);            return $bus_list->encode();        }catch (\Exception $e){            return "";        }    }    ///获取表情价格    /// $uid:玩家ID    /// $sign：签名    public function getPhiz($uid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($uid)) return "";            $player =  DB::table('xx_user')->where('uid',$uid)->first();            if(empty($player)) return "";            $phiz = DB::table('xx_sys_phiz')->get();            return ['gold'=>$player->gold,'phiz'=>$phiz];        }catch (\Exception $e) {            return "";        }    }    ///更新玩家的分享标识    /// $uid:玩家ID    /// $sign：签名    public function upLottery($uid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "0";            if(empty($uid)) return "0";            $player =  DB::table('xx_user')->where('uid',$uid)->first();            if(empty($player)) return "0";            if($player->lottery==0){                DB::table('xx_user')->where('uid',$uid)->update(['lottery'=>1]);                return "1";            }else{                return "0";            }        }catch (\Exception $e) {            return "0";        }    }    ///更新玩家的分享标识手机号    /// $uid:玩家ID    /// $sign：签名    public function upTel($uid,$tel,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "0";            if(empty($uid) || empty($tel)) return "0";            $list = DB::select('select * from xx_user  where uphone = '.$tel.' and uid <> '.$uid);            if(empty($list) || count($list)<=0){                DB::table('xx_user')->where('uid',$uid)->update(['uphone'=>$tel]);                return "1";            }else{                return "2";            }        }catch (\Exception $e) {            return "0";        }    }}