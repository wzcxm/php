<?php

namespace App\Http\Controllers;

use App\Common\CommonFunc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Userinfo\UserInfo;
use Userinfo\UserList;
use Xxgame\Business;
use Xxgame\BusinessList;
use Xxgame\PartnerPlayList;
use Xxgame\Playerinfo;
use Xxgame\PlayWin;
use Xxgame\PlayWinList;
use Xxgame\Record;
use Xxgame\RecordList;
use Xxgame\Single;
use Xxgame\SingleList;
use Xxgame\TeaInfo;
use Xxgame\TeaList;
use Xxgame\TeaPartnerList;
use Xxgame\TeaPlayer;
use Xxgame\TeaPlayerList;
use Xxgame\WinAndNum;

class GameSericeController extends Controller
{
	///获取茶楼列表
	/// $uid：玩家ID
	/// $key：KEY
	public function GetTeaList($uid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";

			if(empty($uid)) return "";
			$sql =<<<EOT
				select t.* from v_tea t left join xx_sys_teas s on  s.tea_id=t.tea_id where s.state=1 and  s.uid=$uid
EOT;
			$tea_data = DB::select($sql);
			if(empty($tea_data)) return "";
			$tesList = new TeaList();
			foreach ($tea_data as $tea){
				$teainfo = new TeaInfo();
				$teainfo->setTeaId($tea->tea_id);
				$teainfo->setUid($tea->uid);
				$teainfo->setForbid($tea->forbid);
				$teainfo->setMsg($tea->msg);
				$teainfo->setTeaName($tea->tea_name);
				$teainfo->setMethod1($tea->method1);
				$teainfo->setType1($tea->type1);
				$teainfo->setHall1($tea->hall1);
				$teainfo->setMethod2($tea->method2);
				$teainfo->setType2($tea->type2);
				$teainfo->setHall2($tea->hall2);
				$teainfo->setMethod3($tea->method3);
				$teainfo->setType3($tea->type3);
				$teainfo->setHall3($tea->hall3);
				$teainfo->setNickname($tea->nickname);
				$teainfo->setHead($tea->head_img_url);
                $tesList->getTeaList()[] = $teainfo;
			}
			return $tesList->encode();
		}catch (\Exception $e){
			return "";
		}
	}

	///获取茶楼排行榜
	/// $key：KEY
	public function GetTeaOrderByList($sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			$tea_data = DB::table('v_tea')->where('ody','>',0)->orderBy('ody','asc')->get();
			if(empty($tea_data)) return "";
			$tesList = new TeaList();
			foreach ($tea_data as $tea){
				$teainfo = new TeaInfo();
				$teainfo->setTeaId($tea->tea_id);
				$teainfo->setUid($tea->uid);
				$teainfo->setForbid($tea->forbid);
				$teainfo->setMsg($tea->msg);
				$teainfo->setTeaName($tea->tea_name);
				$teainfo->setNickname($tea->nickname);
				$teainfo->setHead($tea->head_img_url);
                $tesList->getTeaList()[] = $teainfo;
			}
			return $tesList->encode();
		}catch (\Exception $e){
			return "";
		}
	}

	///获取茶楼玩家列表
	/// $teaid：茶楼ID
	/// $key：KEY
	public function GetTeaPlayerList($teaid,$uid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($teaid)) return "";
            //获取玩家列表
			$player_data =  DB::select('CALL search_tealist('.$teaid.','.$uid .')');
			if(empty($player_data)) return "";
            $id_arr = collect($player_data)->implode ('uid',',');
            $sql = <<<EOT
                select count(*) as count_num,sum(iswin) as count_win,hall_no,player_id,DATE_FORMAT(create_time,'%Y-%m-%d') as cdate 
                from v_xx_record  
                where tea_id =$teaid 
                and player_id in($id_arr)
                and create_time >  SUBDATE(DATE_FORMAT(NOW(),'%Y-%m-%d'), 2)
                GROUP BY hall_no,tea_id,player_id,cdate
EOT;
            $three_data = DB::select($sql);
			$teaPlayerList =  new TeaPlayerList();
			foreach ($player_data as $player){
                $teaplayer = new TeaPlayer();
                $teaplayer->setTeaId($player->tea_id);
                $teaplayer->setNickname($player->nickname);
                $teaplayer->setUid($player->uid);
                $teaplayer->setState($player->state);
                $teaplayer->setManager($player->manager);
                $teaplayer->setHallId($player->hall_id);
                $teaplayer->setRemarks($player->remarks);
                $teaplayer->setDate($player->create_time);
                $teaplayer->setOnline($player->online_state);
                $teaplayer->setTpScore($player->score);
                $teaplayer->setHeadUrl($player->head_img_url);
                $teaplayer->setRecid($player->recid);
                $play_data_three = collect($three_data)->where('player_id',$player->uid);
                //今天局数和赢家次数
                $play_data_one = collect($play_data_three)->where('cdate',date('Y-m-d'));
                $wan_jt = new WinAndNum();
                $wan_jt->setDays(1);
                foreach ($play_data_one as $item){
                    if($item->hall_no==1){
                        $wan_jt->setOneHallNum($item->count_num);//1号厅总局数
                        $wan_jt->setOneHallWin($item->count_win);//1号厅大赢家次数
                    } else if($item->hall_no==2){
                        $wan_jt->setTwoHallNum($item->count_num);//2号厅总局数
                        $wan_jt->setTwoHallWin($item->count_win);//2号厅大赢家次数
                    } else if($item->hall_no==3){
                        $wan_jt->setThreeHallNum($item->count_num);//3号厅总局数
                        $wan_jt->setThreeHallWin($item->count_win);//3号大赢家次数
                    }
                }
                $teaplayer->getWanList()[] = $wan_jt;
                //昨天局数和赢家次数
                $play_data_two = collect($play_data_three)->where('cdate',date('Y-m-d',strtotime('-1 days')));
                $wan_zt = new WinAndNum();
                $wan_zt->setDays(2);
                foreach ($play_data_two as $item){
                    if($item->hall_no==1){
                        $wan_zt->setOneHallNum($item->count_num);//1号厅总局数
                        $wan_zt->setOneHallWin($item->count_win);//1号厅大赢家次数
                    } else if($item->hall_no==2){
                        $wan_zt->setTwoHallNum($item->count_num);//2号厅总局数
                        $wan_zt->setTwoHallWin($item->count_win);//2号厅大赢家次数
                    } else if($item->hall_no==3){
                        $wan_zt->setThreeHallNum($item->count_num);//3号厅总局数
                        $wan_zt->setThreeHallWin($item->count_win);//3号大赢家次数
                    }
                }
                $teaplayer->getWanList()[] = $wan_zt;
                //前天局数和赢家次数
                $play_data_three = collect($play_data_three)->where('cdate',date('Y-m-d',strtotime('-2 days')));
                $wan_qt = new WinAndNum();
                $wan_qt->setDays(3);
                foreach ($play_data_three as $item){
                    if($item->hall_no==1){
                        $wan_qt->setOneHallNum($item->count_num);//1号厅总局数
                        $wan_qt->setOneHallWin($item->count_win);//1号厅大赢家次数
                    } else if($item->hall_no==2){
                        $wan_qt->setTwoHallNum($item->count_num);//2号厅总局数
                        $wan_qt->setTwoHallWin($item->count_win);//2号厅大赢家次数
                    } else if($item->hall_no==3){
                        $wan_qt->setThreeHallNum($item->count_num);//3号厅总局数
                        $wan_qt->setThreeHallWin($item->count_win);//3号大赢家次数
                    }
                }
                $teaplayer->getWanList()[] = $wan_qt;
                $teaPlayerList->getPlayerList()[] = $teaplayer;
            }
			return $teaPlayerList->encode();
		}catch (\Exception $e){
			return "";
		}
	}



	///修改茶楼玩家备注
	/// $teaid：茶楼ID
	/// $uid：玩家ID
	/// $remark：备注内容
	/// $key：KEY
	public function updateRemark($teaid,$uid,$remark,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "0";
			if(empty($teaid) || empty($uid)) return "0";
			DB::table("xx_sys_teas")->where([['tea_id',$teaid],['uid',$uid]])->update(['remarks'=>urldecode($remark)]);
			return "1";
		}catch (\Exception $e){
			return "0";
		}
	}

	//验证签名方法
	private  function checkSign($sign){
		try{
			if(empty($sign)) return false;
			if($sign == md5(env('SIGN')))
				return true;
			else
				return false;
		}catch (\Exception $e){
			return false;
		}
	}

	///获取版本号
	/// $version：版本号
	/// $type：系统平台1：苹果；2：安卓
	/// $res：资源
	/// $src：资源地址
	public function GetVersion($version,$type){
		$ret = "";
		if($type==1) { //苹果版
			if ($version == 5.0) { //
				$ret = "2";//2-审核版本；0-正常；1-强制更新
			} else {
				if($version < 4.6){
					$ret = "1";
				}else{
					$ret = "0";
				}
			}
		}else if($type==2){
			if($version < 4.6){
				$ret = "1";
			}else{
				$ret = "0";
			}
		}else{ }
		if($ret=="0"){
			$ret .= "|1|1|1";
		}
		return $ret;
	}

	///获取回放数据
	/// $gtype：游戏类型
	/// $rid：战绩ID
	/// $sign：签名
	public function getPlayback($gtype,$rid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($gtype) || empty($rid)) return "";
			$Gt = config('conf.GameType');
			$table = $Gt[$gtype];
			return DB::table($table)->where('record_id',$rid)->value('playback');
		}catch (\Exception $e){
			return "";
		}
	}

	///获取玩家的总战绩
	/// $uid：玩家ID
	/// $g_type：游戏类型
	/// $sign：签名
	public function GetRecord($uid,$offset,$sign){
		try{
		    if($uid == 9001 || $uid == 23491){
                $uid = 23606;
            }
            //验证签名
            if(!$this->checkSign($sign)) return "";
            if(empty($uid)) return "";
            $data = DB::table('v_xx_record')
                ->where('player_id',$uid)
                ->whereBetween('create_time',[date('Y-m-d',strtotime('-7 days')),date('Y-m-d 23:59:59') ])
                ->orderBy('create_time', 'desc')
                ->offset(0)
                ->limit(50)
                ->get();
//            $total = DB::table('v_xx_record')
//                ->where('player_id',$uid)
//                ->whereBetween('create_time',[date('Y-m-d',strtotime('-7 days')),date('Y-m-d 23:59:59') ])
//                ->count();
            $recordList =  new RecordList();
            $recordList->setTotal(-1);
            if(!empty($data)){
                $play_list = DB::table('xx_player_record_info')->whereIn('id',$data->pluck('id'))->get();
                foreach ($data as $da){
                    $record = new Record();
                    $record->setGameno($da->id);
                    $record->setRoomid($da->roomid);
                    $record->setNumber($da->number);
                    $record->setGametype($da->gametype);
                    $record->setCreatetime($da->create_time);
                    $plays = $play_list->where('id',$da->id);
                    if(!empty($plays)){
                        foreach ($plays as $item) {
                            $player =  new Playerinfo();
                            $player->setUid($item->player_id);
                            $player->setNickname($item->player_name);
                            $player->setScore($item->score);
                            $player->setTili($item->tili);
                            $player->setIswin($item->iswin);
                            $record->getPlayer()[] = $player;
                        }
                    }
                    $recordList->getRecordList()[] = $record;
                }
            }
            return $recordList->encode();
		}catch (\Exception $e){
			return "";
		}
	}

	///获取玩家大局战绩
	/// $roomid：房间号
	/// $time：开房时间
	/// $g_type：游戏类型
	/// $sign：签名
	public function BigRecord($roomid,$time,$g_type,$offset,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($roomid) || empty($time) || empty($g_type)) return "";

			$Gt = config('conf.GameType');
			$table = $Gt[$g_type];
			$time = urldecode($time);
			$data = DB::table($table)
				->where([['room_id',$roomid],['create_time',$time]])
//				->offset($offset*5)
//				->limit(5)
				->get();
//			$total = DB::table($table)
//				->where([['room_id',$roomid],['create_time',$time]])->count();
			$singleList = new SingleList();
			$singleList->setTotal(-1);
			if(!empty($data)){
				foreach ($data as $da){
					$single = new Single();
					$single->setRecordId($da->record_id);
					$single->setCreatetime($da->end_time);
					$single->setIndex($da->indexs);
					for($i=1;$i<5;$i++){
						$player =  new Playerinfo();
						$uid = 'uid'.$i;
						$nick = 'nickname'.$i;
						$score = 'score'.$i;
						$player->setUid($da->$uid);
						$player->setNickname($da->$nick);
						$player->setScore($da->$score);
                        $single->getPlayer()[] = $player;
					}
                    $singleList->getSingleList()[] = $single;
				}
			}
			return $singleList->encode();
		}catch (\Exception $e){
			return "";
		}
	}

	///获取茶楼的战绩
	/// $teaid:茶楼ID
	/// $offset:页数
	/// $sign：签名
	public function getTeaRec($teaid,$uid,$offset,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($teaid) || empty($uid)) return "";
            //战绩日期
            if($offset == 1){//今天的战绩
                $start = date('Y-m-d');
                $end = date('Y-m-d 23:59:59');
            }else if($offset == 2){ //昨天的战绩
                $start = date('Y-m-d' ,strtotime('-1 days'));
                $end = date('Y-m-d 23:59:59',strtotime('-1 days'));
            }else if($offset == 3){//前天的战绩
                $start = date('Y-m-d',strtotime('-2 days'));
                $end = date('Y-m-d 23:59:59',strtotime('-2 days'));
            }else{ //默认今天
                $start = date('Y-m-d');
                $end = date('Y-m-d 23:59:59');
            }
            //查询战绩
            $data = DB::select("CALL search_play_record(".$teaid.",".$uid .",'".$start."','".$end."')");
            //为空，返回
            if(empty($data)) return "";
            $recordList =  new RecordList();
            $recordList->setTotal(-1);
            //所有人的消息分数
            $play_list = DB::table('xx_player_record_info')->whereIn('id',collect($data)->pluck('id'))->get();
            foreach ($data as $da){
                $record = new Record();
                $record->setGameno($da->id);
                $record->setRoomid($da->roomid);
                $record->setNumber($da->number);
                $record->setGametype($da->gametype);
                $record->setCreatetime($da->create_time);
                $record->setHallNum($da->hall_no);
                $plays = $play_list->where('id',$da->id);
                if(!empty($plays)){
                    foreach ($plays as $item) {
                        $player =  new Playerinfo();
                        $player->setUid($item->player_id);
                        $player->setNickname($item->player_name);
                        $player->setScore($item->score);
                        $player->setTili($item->tili);
                        $player->setIswin($item->iswin);
                        $record->getPlayer()[] = $player;
                    }
                }
                $recordList->getRecordList()[] = $record;
            }
            return $recordList->encode();
		}catch (\Exception $e){
			return "";
		}
	}


	///获取牌馆玩家的大赢家次数
    /// $teaid:茶楼ID
    /// $uid：玩家ID
    /// $sign：签名
    public function getPlayRec($teaid,$uid,$sign){
        try{
            //验证签名
            if(!$this->checkSign($sign)) return "";
            if(empty($teaid) || empty($uid)) return "";
            $data = DB::select("CALL search_play_winnum(".$teaid.",".$uid .")");
            if(empty($data)) return "";
            $playwinList =  new PlayWinList();
            foreach ($data as $item){
                if($item->sum_winnum <= 0) continue;
                $playwin = new PlayWin();
                $playwin->setHead($item->head_img_url);
                $playwin->setNickname($item->nickname);
                $playwin->setUid($item->uid);
                $playwin->setHallWinOne($item->winnum1);
                $playwin->setHallWinTwo($item->winnum2);
                $playwin->setHallWinThree($item->winnum3);
                $playwin->setTotalWin($item->sum_winnum);
                $playwinList->getPlaywinList()[] = $playwin;
            }
            return $playwinList->encode();
        }catch (\Exception $e){
            return "";
        }
    }

    /*
     * 获取牌馆的合伙人信息
     */
    public function getPartner($teaid,$uid,$sign){
        try{
            //验证签名
            if(!$this->checkSign($sign)) return "";
            if(empty($teaid) || empty($uid)) return "";
            $data = DB::select("CALL search_tea_partner(".$teaid.",".$uid .")");
            if(empty($data)) return "";
            $teapartner =  new TeaPartnerList();
            foreach ($data as $item){
                $playwin = new PlayWin();
                $playwin->setHead($item->head_img_url);
                $playwin->setNickname($item->nickname);
                $playwin->setUid($item->uid);
                $playwin->setHallWinOne($item->total_win_one);
                $playwin->setHallWinTwo($item->total_win_two);
                $playwin->setHallWinThree($item->total_win_three);
                $playwin->setTotalNumber($item->total_numbers);
                $playwin->setTotalInvite($item->total_invite);
                $teapartner->getTeapartnerList()[] = $playwin;
            }
            return $teapartner->encode();
        }catch (\Exception $e){
            return "";
        }
    }

    /*
     * 获取合伙人的玩家
     */
    public function getPartnerPlay($teaid,$uid,$sign){
        try{
            //验证签名
            if(!$this->checkSign($sign)) return "";
            if(empty($teaid) || empty($uid)) return "";
            $data = DB::select("CALL search_partner_play(".$teaid.",".$uid .")");
            if(empty($data)) return "";
            $partnerplay =  new PartnerPlayList();
            foreach ($data as $item){
                $playwin = new PlayWin();
                $playwin->setHead($item->head_img_url);
                $playwin->setNickname($item->nickname);
                $playwin->setUid($item->uid);
                $playwin->setHallWinOne($item->winnum1);
                $playwin->setHallWinTwo($item->winnum2);
                $playwin->setHallWinThree($item->winnum3);
                $playwin->setTotalNumber($item->numbers);
                $playwin->setClearTime($item->clear_time);
                $partnerplay->getPartnerplayerList()[] = $playwin;
            }
            return $partnerplay->encode();
        }catch (\Exception $e){
            return "";
        }
    }

	///获取茶楼我的战绩
	/// $teaid:茶楼ID
	/// $uid：玩家ID
	/// $offset:页数
	/// $sign：签名
	public function getMyTeaRec($teaid,$uid,$offset,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($teaid)) return "";
			$data = DB::table('v_xx_record')
				->where([['player_id',$uid],['tea_id',$teaid]])
                ->whereBetween('create_time',[date('Y-m-d',strtotime('-7 days')),date('Y-m-d 23:59:59') ])
				->orderBy('create_time', 'desc')
				->offset(0)
				->limit(50)
				->get();
//			$total = DB::table('v_xx_record')
//				->where([['player_id',$uid],['tea_id',$teaid]])
//                ->whereBetween('create_time',[date('Y-m-d',strtotime('-7 days')),date('Y-m-d 23:59:59') ])
//				->count();
			$recordList =  new RecordList();
			$recordList->setTotal(-1);
			if(!empty($data)){
                $play_list = DB::table('xx_player_record_info')->whereIn('id',$data->pluck('id'))->get();
				foreach ($data as $da){
					$record = new Record();
                    $record->setGameno($da->id);
					$record->setRoomid($da->roomid);
					$record->setNumber($da->number);
					$record->setGametype($da->gametype);
					$record->setCreatetime($da->create_time);
                    $plays = $play_list->where('id',$da->id);
					if(!empty($plays)){
						foreach ($plays as $item) {
							$player =  new Playerinfo();
							$player->setUid($item->player_id);
							$player->setNickname($item->player_name);
							$player->setScore($item->score);
                            $record->getPlayer()[] = $player;
						}
					}
                    $recordList->getRecordList()[] = $record;
				}
			}
			return $recordList->encode();
		}catch (\Exception $e){
			return "";
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
			$content = CommonFunc::send_sms($tel,'SMS_123797991',$param);
			if($content->Code == "OK"){
				return 1;
			}else{
				return 0;
			}
		}catch (\Exception $e){
			return 0;
		}
	}

	///茶楼经营状态
	/// $teaid:茶楼ID
	/// $sign：签名
	public function getBusList($teaid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($teaid)) return "";
			$sql = <<<EOT
			select date_day,
					count(tea_id) as tea_num,
					count((case when hall<9 then 1 end)) as hall_num_1,
					count((case when hall>8 and hall<17 then 2 end)) as hall_num_2,
					count((case when hall>16 then 3 end)) as hall_num_3
			from v_tea_rec 
			where tea_id = $teaid
			group by date_day
EOT;
			$rec = DB::select($sql);
			if(empty($rec)) return "";
			$bus_list =  new BusinessList();
			foreach ($rec as $item){
				$bus = new Business();
				$bus->setTeaNum($item->tea_num);
				$bus->setHallNum1($item->hall_num_1);
				$bus->setHallNum2($item->hall_num_2);
				$bus->setHallNum3($item->hall_num_3);
				$bus->setDateDay($item->date_day);
                $bus_list->getBusList()[] = $bus;
			}
			return $bus_list->encode();
		}catch (\Exception $e){
			return "";
		}
	}


	///获取表情价格
	/// $uid:玩家ID
	/// $sign：签名
	public function getPhiz($uid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($uid)) return "";
			$player =  DB::table('xx_user')->where('uid',$uid)->first();
			if(empty($player)) return "";
			$phiz = DB::table('xx_sys_phiz')->get();
			return ['gold'=>$player->gold,'phiz'=>$phiz];
		}catch (\Exception $e) {
			return "";
		}
	}

	///更新玩家的分享标识
	/// $uid:玩家ID
	/// $sign：签名
	public function upLottery($uid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "0";
			if(empty($uid)) return "0";
			$player =  DB::table('xx_user')->where('uid',$uid)->first();
			if(empty($player)) return "0";
			if($player->lottery==0){
				DB::table('xx_user')->where('uid',$uid)->update(['lottery'=>1]);
				return "1";
			}else{
				return "0";
			}
		}catch (\Exception $e) {
			return "0";
		}
	}

	///更新玩家的手机号
	/// $uid:玩家ID
	/// $sign：签名
	public function upTel($uid,$tel,$code,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "0";
			//验证验证码
			$oldcode = \Cache::get($tel);
			if(empty($oldcode) || $oldcode!=$code)
				return "3";
			if(empty($uid) || empty($tel)) return "0";
			$list = DB::select('select * from xx_user  where uphone = '.$tel.' and uid <> '.$uid);
			if(empty($list) || count($list)<=0){
				DB::table('xx_user')->where('uid',$uid)->update(['uphone'=>$tel]);
				return "1";
			}else{
				return "2";
			}
		}catch (\Exception $e) {
			return "0";
		}
	}


	///获取玩家红包金额和分享人数
	/// $uid:玩家ID
	/// $sign：签名
	public function getRedBag($uid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($uid)) return "";
			$user =  DB::table('xx_user')->where('uid',$uid)->first();
			if(empty($user)) return "";
			return $user->redbag.'|'.$user->sharenum;
		}catch (\Exception $e){
			return "";
		}
	}

	///获取玩家红包记录
	/// $uid:玩家ID
	/// $sign：签名
	public function getRedList($uid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($uid)) return "";
			$sql = <<<EOT
				select 
					(case when t.type=1 then '分享抽奖' when t.type=2 then '推荐新玩家' end) as lq_type,
					DATE_FORMAT(t.u_date,'%Y-%m-%d') as lq_date,t.name
				 from xx_sys_prize t where t.jptype=3 and t.isreceive=1 and t.uid = $uid  ORDER BY t.u_date DESC
EOT;
			$data =  DB::select($sql);
			if(empty($data)) return "";
			return $data;
		}catch (\Exception $e){
			return "";
		}
	}


	///修改玩家微信
	/// $uid:玩家ID
	/// $code:微信code
	/// $sign：签名
	public function setWxinfo($uid,$code,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return 0;
			$app_id = config('conf.APP_ID');
			$app_secret = config('conf.APP_SECRET');
			$curl = curl_init();
			$url = sprintf("https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code",
				$app_id, $app_secret, $code);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$token_data = curl_exec($curl);
			if (empty($token_data)) return 0;
			$token_data = json_decode($token_data, true);
			if (array_key_exists("errcode", $token_data)) return 0;
			$token = $token_data['access_token'];
			$refresh_token = $token_data['refresh_token'];
			$openid = $token_data['openid'];
			$url = sprintf("https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s",
				$token, $openid);
			curl_setopt($curl, CURLOPT_URL, $url);
			$user_data = curl_exec($curl);
			if (empty($user_data)) return 0;
			$user_data = json_decode($user_data, true);
			if (array_key_exists("errcode", $user_data)) return 0;
			$user = DB::table('xx_user')->where('uid',$uid)->first();
			if(empty($user)) return 0;
			DB::table('xx_user')->where('uid',$uid)->update([
				'nickname'=>$user_data['nickname'],
				'head_img_url'=>$user_data['headimgurl'],
				'sex'=>$user_data['sex'],
				'openid'=>$openid,
				'refresh_token'=>$refresh_token,
				'unionid'=>$user_data['unionid']
			]);
			return 1;
		}catch (\Exception $e){
			return 0;
		}
	}


	///中奖记录
	/// $uid:玩家ID
	/// $sign：签名
	public function getWinnList($uid,$sign){
		try{
			//验证签名
			if(!$this->checkSign($sign)) return "";
			if(empty($uid)) return "";
			$data =  DB::table('xx_sys_prize')
						->where([['uid',$uid],['type',1]])
						->select('name','u_date as lq_date')
						->orderByDesc('u_date')
						->get();
			if(empty($data)) return "";
			return $data;
		}catch (\Exception $e){
			return "";
		}
	}


	/*
	 * 实名认证
	 * $uid:游戏ID
	 * $realname:姓名
	 * $idnum:身份证号
	 */
    public function realName($uid,$realname,$idnum){
        try{
            if(!CommonFunc::is_idcard($idnum)){
                return ['status'=>0,'message'=>'身份证格式错误！'];
            }
            if(empty($realname)){
                return ['status'=>0,'message'=>'真实姓名为空！'];
            }
            DB::table('xx_user')->where('uid',$uid)->update(['realname'=>urldecode($realname),'idnum'=>$idnum]);
            return ['status'=>1,'message'=>''];
        }catch (\Exception $e){
            return ['status'=>0,'message'=>$e->getMessage()];
        }
    }


    public function setRedisList(){
	    try{
            $arr_uid = DB::table("xx_sys_liang")->select('liang_uid')->get()->map(function ($value){
	            return (array)$value;
            })->toArray();
            $uid = DB::table("xx_user")->whereNotIn('uid',$arr_uid)->max('uid');
            $str = [];
	        for ($i=$uid+1;$i<1000000;$i++){
	            if(!in_array($i,$arr_uid)){
                   array_push($str,$i);
                }
            }
            //删除队列
            Redis::del('xx_user_id_list');
	        //创建队列
            Redis::rpush('xx_user_id_list', $str);
            //查看队列元素个数
            return Redis::llen('xx_user_id_list') ;
        }catch (\Exception $e){
            return  $e->getMessage();
        }

        //var_dump($temp);
    }


    /*
     * 获取玩家的日志
     */
    public function getPlayLog($teaid,$uid,$role,$sign){
        //验证签名
        if(!$this->checkSign($sign)) return "";

        if(empty($teaid)) return "";
        if(empty($uid)) return "";
        if($role == 3){
            $sql = <<<EOT
			select * from (select * from xx_sys_log where tea_id = $teaid and type <>4 and uid = $uid  ORDER BY create_date desc LIMIT 0,50) t ORDER BY t.create_date
EOT;
        }else{
            $sql = <<<EOT
			select * from (select * from xx_sys_log where tea_id = $teaid and type <>4 and (uid = $uid or operate = $uid) ORDER BY create_date desc LIMIT 0,50) t ORDER BY t.create_date
EOT;
        }
        $data =  DB::select($sql);
        return $data;
    }

    /*
     * 保存玩家的推荐人
     */
    public function setRecommend($teaid,$uid,$recid,$sign){

        //验证签名
        if(!$this->checkSign($sign)) return "";

        if(empty($teaid)) return "";
        if(empty($uid)) return "";

        $rows = DB::table('xx_sys_teas')->where([['tea_id',$teaid],['uid',$uid]])->update(['recid'=>$recid]);
        if($rows > 0){
            DB::table('xx_sys_teas')->where([['tea_id',$teaid],['uid',$recid]])->increment('invite');
        }
        return 1;
    }

    /**
     * 获取玩家的头像和昵称
     * $uid：玩家id
     * $sign：签名
     */
    public function getUserHead($uid,$sign){
        try{
            //验证签名
            if(!$this->checkSign($sign)) return "";
            $user = DB::table('xx_user')->where('uid',$uid)->first();
            if(!empty($user)){
                $userinfo = new UserInfo();
                $userinfo->setUid($uid);
                $userinfo->setNickname($user->nickname);
                $userinfo->setHead($user->head_img_url);
                return $userinfo->encode();
            }else{
                return "";
            }
        }catch (\Exception $e){
            return "";
        }
    }

    /**
     * 获取玩家的头像和昵称
     * $uid：玩家id
     * $sign：签名
     */
    public function getUserOrTeaHead($type,$uid,$sign){
        try{
            //验证签名
            if(!$this->checkSign($sign)) return "";
            if($type==1){
                $user = DB::table('xx_user')->where('uid',$uid)->first();
                if(!empty($user)){
                    $userinfo = new UserInfo();
                    $userinfo->setUid($uid);
                    $userinfo->setNickname($user->nickname);
                    $userinfo->setHead($user->head_img_url);
                    return $userinfo->encode();
                }else{
                    return "";
                }
            }else{
                $sql = <<<EOT
select t.uid,u.nickname,u.head_img_url,t.manager,t.recid from xx_sys_teas t left join xx_user u on u.uid=t.uid where t.state=1 and  t.tea_id=$uid
EOT;
                $datas = DB::select($sql);
                if(!empty($datas)){
                    $userlist = new UserList();
                    foreach ($datas as $data){
                        $userinfo = new UserInfo();
                        $userinfo->setUid($data->uid);
                        $userinfo->setNickname($data->nickname);
                        $userinfo->setHead($data->head_img_url);
                        $userinfo->setManager($data->manager);
                        $userinfo->setRecid($data->recid);
                        $userlist->getUserList()[] = $userinfo;
                    }
                    return $userlist->encode();
                }else{
                    return "";
                }
            }
        }catch (\Exception $e){
            return "";
        }
    }



}