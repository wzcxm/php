<?phpnamespace App\Http\Controllers;use App\Common\CommonFunc;use Google\Protobuf\Internal\GPBType;use Google\Protobuf\Internal\RepeatedField;use Illuminate\Support\Facades\DB;use Xxgame\Playerinfo;use Xxgame\TeaInfo;use Xxgame\TeaList;use Xxgame\TeaPlayer;use Xxgame\TeaPlayerList;class GameSericeController extends Controller{    ///获取茶楼列表    /// $uid：玩家ID    /// $key：KEY    public function GetTeaList($uid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($uid)) return "";            $sql =<<<EOT                select t.* from xx_sys_tea t left join xx_sys_teas s on  s.tea_id=t.tea_id where s.uid=$uidEOT;            $tea_data = DB::select($sql);            if(empty($tea_data)) return "";            $tesList = new TeaList();            $tea_rf = new RepeatedField(GPBType::MESSAGE, \Xxgame\TeaInfo::class);            foreach ($tea_data as $tea){                $teainfo = new TeaInfo();                $teainfo->setTeaId($tea->tea_id);                $teainfo->setUid($tea->uid);                $teainfo->setForbid($tea->forbid);                $teainfo->setMsg($tea->msg);                $teainfo->setTeaName($tea->tea_name);                $teainfo->setMethod1($tea->method1);                $teainfo->setType1($tea->type1);                $teainfo->setHall1($tea->hall1);                $teainfo->setMethod2($tea->method2);                $teainfo->setType2($tea->type2);                $teainfo->setHall2($tea->hall2);                $teainfo->setMethod3($tea->method3);                $teainfo->setType3($tea->type3);                $teainfo->setHall3($tea->hall3);                $tea_rf->offsetSet(null, $teainfo);            }            $tesList->setTeaList($tea_rf);            return $tesList->encode();        }catch (\Exception $e){            return "";        }    }    ///获取茶楼玩家列表    /// $teaid：茶楼ID    /// $key：KEY    public function GetTeaPlayerList($teaid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($teaid)) return "";            $sql = <<<EOT            select t.*,u.nickname from xx_sys_teas t left join xx_user u on  u.uid=t.uid where t.tea_id = $teaidEOT;            $player_data =  DB::select($sql);            if(empty($player_data)) return "";            $teaPlayerList =  new TeaPlayerList();            $player_rf = new RepeatedField(GPBType::MESSAGE, \Xxgame\TeaPlayer::class);            foreach ($player_data as $player){                $teaplayer = new TeaPlayer();                $teaplayer->setTeaId($player->tea_id);                $teaplayer->setNickname($player->nickname);                $teaplayer->setUid($player->uid);                $teaplayer->setState($player->state);                $teaplayer->setManager($player->manager);                $teaplayer->setHallId($player->hall_id);                $teaplayer->setWinnum($player->winnum);                $teaplayer->setRemarks($player->remarks);                $teaplayer->setNumbers($player->numbers);                $player_rf->offsetSet(null,$teaplayer);            }            $teaPlayerList->setPlayerList($player_rf);            return $teaPlayerList->encode();        }catch (\Exception $e){            return "";        }    }    ///获取玩家信息    /// $uid：玩家ID    /// $key：KEY    public function GetPlayer($uid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($uid)) return "";            $player = DB::table("xx_user")->where('uid',$uid)->first();            if(empty($player)){                return "";            }else{                $playerinfo = new Playerinfo();                $playerinfo->setUid($uid);                $playerinfo->setHead($player->head_img_url);                $playerinfo->setNickname($player->nickname);                $playerinfo->setRoomcard($player->roomcard);                $playerinfo->setRole($player->rid);                $playerinfo->setMarquee($this->getMsg(1));//跑马灯                $playerinfo->setUrgent($this->getMsg(3));//紧急通知                return $playerinfo->encode();            }        }catch (\Exception $e){            return "";        }    }    //获取游戏公告信息    private function getMsg($type){        try{            $msg = DB::table("xx_sys_message")->get();            if(!empty($msg))                return collect($msg)->where('mtype',$type)->get('mcontent');            else                return "";        }catch (\Exception $e){            return "";        }    }    //验证签名方法    private  function checkSign($sign){        try{            if(empty($sign)) return false;            if(decrypt($sign) == env('SIGN'))                return true;            else                return false;        }catch (\Exception $e){            return false;        }    }    ///获取版本号    /// $version：版本号    /// $type：系统平台1：苹果；2：安卓    /// $res：资源    /// $src：资源地址    public function GetVersion($version,$type){        $ret = "";        if($type==1) { //苹果版            if ($version == 3.0) {                $ret = "2";            } else {                if($version < 1.0){                    $ret = "1";                }else{                    $ret = "0";                }            }        }else if($type==2){            if($version==0.5) {                $ret = "2";            }else if($version < 1.0){                $ret = "1";            }else{                $ret = "0";            }        }else{ }        if($ret=="0"){            $ret .= "|1|1|1";        }        return $ret;    }    ///获取回放数据    /// $gtype：游戏类型    /// $rid：战绩ID    /// $sign：签名    public function getPlayback($gtype,$rid,$sign){        try{            //验证签名            if(!$this->checkSign($sign)) return "";            if(empty($gtype) || empty($rid)) return "";            $gamt_type = config('conf.GameType');            $table = $gamt_type->get($gtype);            return DB::table($table)->where('record_id',$rid)->value('playback');        }catch (\Exception $e){            return "";        }    }}