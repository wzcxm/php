<?php

namespace App\Http\Controllers;

use App\Common\CommonFunc;
use Illuminate\Support\Facades\DB;
use CsppLogin\ErrorCode;
use CsppLogin\ServerLoginInfo;
use CsppLogin\ServerDomainInfo;
use Illuminate\Support\Facades\Redis;

class GameLoginController extends Controller
{

	public function login($uid, $type,$gw_type, $value)
	{
		try {
//			if ($uid < 9000 || $uid >= 10000)
//				return $this->error_message(ErrorCode::Error_WeiXin_Login);
			switch ($type)
			{
				//case 1:
					//return $this->youke_login();
				case 2:
					return $this->weixin_login($uid, $value,$gw_type);
				case 3:
					return $this->auto_login($uid, $value,$gw_type);
				case 4:
					return $this->account_login($uid,$gw_type);
				//case 5:
					//return $this->tel_login($uid,$value,$gw_type);
				default:
					break;
			}

			return $this->error_message(ErrorCode::Error_WeiXin_Login);
		} catch (\Exception $e)
		{
			return $this->error_message(ErrorCode::Error_System);
		}
	}

	///手机号登陆
	/// $tel：手机号
	/// $code：验证码
	private function tel_login($tel,$code,$gw_type){
		//验证验证码
		$oldcode = \Cache::get($tel);
		if(empty($oldcode) || $oldcode!=$code)
			return $this->error_message(ErrorCode::Error_Not_Found_Code);
		//查找用户信息
		$user = DB::table('xx_user')->where("uphone",$tel)->first();
		if (empty($user))
			return $this->error_message(ErrorCode::Error_Not_Found_User);
        DB::table('xx_user')->where("uphone",$tel)->update(['gw_type'=>$gw_type]);
        //返回序列化数据
        return $this->setProtobuf($user,$gw_type);
	}

	//游客登录 ID(9401-9500)
	private function youke_login()
	{

		$uid = mt_rand(9401, 9500);
		$user = DB::table('xx_user')->where("uid",$uid)->first();
		if (empty($user))
			return $this->error_message(ErrorCode::Error_Not_Found_User);
        DB::table('xx_user')->where("uid",$uid)->update(['gw_type'=>1]);
        //返回序列化数据
        return $this->setProtobuf($user,1);
	}
	
	//账号登录 ID(9000-9400)
	private function account_login($uid,$gw_type)
	{
		if ($uid < 9000 || $uid > 9400)
			return $this->error_message(ErrorCode::Error_Not_Found_User);
		
		$user = DB::table('xx_user')->where("uid",$uid)->first();
		if (empty($user))
			return $this->error_message(ErrorCode::Error_Not_Found_User);
        DB::table('xx_user')->where("uid",$uid)->update(['gw_type'=>$gw_type]);
        //返回序列化数据
		return $this->setProtobuf($user,$gw_type);
	}

	//微信登录
	private function weixin_login($uid, $code,$gw_type)
	{
		$app_id = config('conf.APP_ID');
		$app_secret = config('conf.APP_SECRET');
		$curl = curl_init();

		$url = sprintf("https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code",
			$app_id, $app_secret, $code);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$token_data = curl_exec($curl);
		if (empty($token_data))
			goto error_end;
		$token_data = json_decode($token_data, true);

		if (!array_key_exists("errcode", $token_data))
		{
			$token = $token_data['access_token'];
			$refresh_token = $token_data['refresh_token'];
			$openid = $token_data['openid'];
			$url = sprintf("https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s",
				$token, $openid);
			curl_setopt($curl, CURLOPT_URL, $url);
			$user_data = curl_exec($curl);
			if (empty($user_data))
				goto error_end;
			$user_data = json_decode($user_data, true);
			if (!array_key_exists("errcode", $user_data))
			{
				$nickname = $user_data['nickname'];
				$sex = $user_data['sex'];
				$head_img_url = substr($user_data['headimgurl'],0,strrpos($user_data['headimgurl'],'/')).'/46';
				$unionid = $user_data['unionid'];
				$roomcard = 5;
				$gold = 3000;
				$passwd = CommonFunc::random_string(11);
				$user = DB::table('xx_user')->where("unionid",$unionid)->first();
				if (empty($user))
				{
				    $user_uid = Redis::lpop('xx_user_id_list');
					//插入数据
					$uid = DB::table('xx_user')->insertGetId(['uid'=>$user_uid,
						'nickname'=>$nickname,'head_img_url'=>$head_img_url,'sex'=>$sex,'roomcard'=>$roomcard,'gold'=>$gold,
						'openid'=>$openid,'unionid'=>$unionid,'refresh_token'=>$refresh_token,'pwd'=>$passwd,'gw_type'=>$gw_type
					]);
					//添加成功，更新推荐人的红包
					if(!empty($uid)){
						$this->setRedPack($unionid);
					}
				} else {
					$ustate = $user->ustate;
					if ($ustate != 0)
						goto error_end;
					//更新数据
					$affected = DB::table('xx_user')->where('unionid',$unionid)
						->update([
						'nickname'=>$nickname,'head_img_url'=>$head_img_url,'sex'=>$sex,
						'openid'=>$openid,'refresh_token'=>$refresh_token,'pwd'=>$passwd,
                        'gw_type'=>$gw_type
						]);
					if ($affected != 1)
						goto error_end;
				}
                $user_ret = DB::table('xx_user')->where("unionid",$unionid)->first();
                $send_data = $this->setProtobuf($user_ret,$gw_type);
				curl_close($curl);
				return $send_data;
			}
		}
		error_end:
		curl_close($curl);
		return $this->error_message(ErrorCode::Error_WeiXin_Login);
	}

	//自动登录
	private function auto_login($uid, $passwd,$gw_type)
	{
		if ($uid <= 0)
			return $this->error_message(ErrorCode::Error_WeiXin_Login);
		$old_passwd = $passwd;
		$passwd = decrypt($passwd);
		//从数据库获取信息
		$user = DB::table('xx_user')->where([["uid",$uid],['pwd',$passwd]])->first();
		if (empty($user))
			return $this->error_message(ErrorCode::Error_WeiXin_Login);
		$ustate = $user->ustate;
		$refresh_token = $user->refresh_token;
		if ($ustate != 0)
			return $this->error_message(ErrorCode::Error_WeiXin_Login);
		$app_id = config('conf.APP_ID');
		$curl = curl_init();
		$url = sprintf("https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s",
			$app_id, $refresh_token);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$refresh_token_data = curl_exec($curl);
		if (empty($refresh_token_data))
			goto error_end;
		$refresh_token_data = json_decode($refresh_token_data, true);

		if (!array_key_exists("errcode", $refresh_token_data))
		{
			$token = $refresh_token_data['access_token'];
			$refresh_token = $refresh_token_data['refresh_token'];
			$openid = $refresh_token_data['openid'];

			$url = sprintf("https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s",
				$token, $openid);
			curl_setopt($curl, CURLOPT_URL, $url);
			$user_data = curl_exec($curl);
			if (empty($user_data))
				goto error_end;
			$user_data = json_decode($user_data, true);
			if (!array_key_exists("errcode", $user_data))
			{
				$nickname = $user_data['nickname'];
				$sex = $user_data['sex'];
				$head_img_url = substr($user_data['headimgurl'],0,strrpos($user_data['headimgurl'],'/')).'/46';

				//更新数据
				$affected = DB::update('update xx_user set nickname = ?, head_img_url = ?, sex = ?, refresh_token = ?,gw_type = ? where uid = ?',
					[$nickname, $head_img_url, $sex, $refresh_token,$gw_type, $uid]);
                $user_ret = DB::table('xx_user')->where("uid",$uid)->first();
                $send_data = $this->setProtobuf($user_ret,$gw_type);
				curl_close($curl);
				return $send_data;
			}
		}

		error_end:
		curl_close($curl);
		return $this->error_message(ErrorCode::Error_WeiXin_Login);
	}

	//错误处理
	private function error_message($code)
	{
		$server_login_info = new ServerLoginInfo();
		$server_login_info->setCode($code);
		$send_data = $server_login_info->encode();
		CommonFunc::message_xor($send_data);
		return $send_data;
	}

	//获取玩家的茶楼大厅、游戏类型、公告、紧急通知
	private function GetMessage($teaid,$room_id){
		//所在的茶楼大厅号
		$desk = $room_id%100;
		if($desk>0 && $desk<9){
            $hallid = 1;
        }else if($desk>8 && $desk<17){
            $hallid = 2;
        }else if($desk>16 && $desk<25){
            $hallid = 3;
        }else{
            $hallid = 0;
        }
		//所在茶楼大厅的游戏类型
		if($hallid > 0 && $hallid < 4){
            $type_id = 'type'.$hallid;
            $tea_data = DB::table('xx_sys_tea')->where('tea_id',$teaid)->first();
            $game_type = $tea_data->$type_id;
            $voice = $tea_data->voice;
            $embar = $tea_data->embar;
		}else{
            $game_type = 0;
            $voice = 1;
            $embar = 2;
		}
		//获取系统信息
		$message =  DB::table("xx_sys_message")->get();
		if(empty($message)){
			$marquee = "";
			$urgent = "";
		}else{
			//跑马灯
			$marquee = collect($message)->where('mtype',1)->first()->mcontent;//get('mcontent');
			//紧急通知
			$urgent = collect($message)->where('mtype',3)->first()->mcontent;//->get('mcontent');
		}
		return ['hallid'=>$hallid,'game_type'=>$game_type,'marquee'=>$marquee,'urgent'=>$urgent,'voice'=>$voice,'embar'=>$embar];
	}

	//设置推荐人的人数和红包
	private function setRedPack($unionid){
		try{
			$temp_user = DB::table('xx_user_temp')->where('unionid',$unionid)->orderByDesc('id')->first();
			if(!empty($temp_user)){
			    $arr = ['wxopenid'=>$temp_user->wxopenid];
			    if(!empty($temp_user->front)){
                    //推荐人增加一次分享次数
                    DB::table('xx_user')->where('uid',$temp_user->front)->increment('sharenum');
                    $arr['chief_uid'] = $temp_user->front;
                    $front_mode = DB::table('xx_user')->where('uid',$temp_user->front)->first();
                    if($front_mode->rid == 4){ //如果推荐人是特级代理，保存特级代理的渠道号
                        if(!empty($front_mode->aisle)){
                            $arr['super_aisle'] = $front_mode->aisle;
                        }
                    }else if($front_mode->rid == 3){ //如果推荐人总代，保存总代和总代对应的特级代理的渠道号
                        if(!empty($front_mode->aisle)){
                            $arr['chief_aisle'] = $front_mode->aisle;
                        }
                        if(!empty($front_mode->super_aisle)){
                            $arr['super_aisle'] = $front_mode->super_aisle;
                        }
                    }else if($front_mode->rid == 6){ //如果推荐人vip代理，保存总代和特级渠道号
                        if(!empty($front_mode->chief_aisle)){
                            $arr['chief_aisle'] = $front_mode->chief_aisle;
                        }
                        if(!empty($front_mode->super_aisle)){
                            $arr['super_aisle'] = $front_mode->super_aisle;
                        }
                        if(!empty($front_mode->aisle)){
                            $arr['vip_aisle'] = $front_mode->aisle;
                        }
                    }else if($front_mode->rid == 2){ //如果是普通代理，保存代理的总代和特级渠道号
                        if(!empty($front_mode->chief_aisle)){
                            $arr['chief_aisle'] = $front_mode->chief_aisle;
                        }
                        if(!empty($front_mode->super_aisle)){
                            $arr['super_aisle'] = $front_mode->super_aisle;
                        }
                        if(!empty($front_mode->vip_aisle)){
                            $arr['vip_aisle'] = $front_mode->vip_aisle;
                        }
                    }else{
                        if(!empty($front_mode->chief_aisle)){
                            $arr['chief_aisle'] = $front_mode->chief_aisle;
                        }
                        if(!empty($front_mode->super_aisle)){
                            $arr['super_aisle'] = $front_mode->super_aisle;
                        }
                        if(!empty($front_mode->vip_aisle)){
                            $arr['vip_aisle'] = $front_mode->vip_aisle;
                        }
                    }
                }
				//保存我的微信openid
				DB::table('xx_user')
					->where('unionid',$unionid)
					->update($arr);
			    //删除临时信息
                DB::table('xx_user_temp')->where('unionid',$unionid)->delete();
			}
		}catch (\Exception $e){

		}
	}


	//生成返回protobuf数据
    private function setProtobuf($user,$gw_type){
	    try{
            $server_login_info = new ServerLoginInfo();
            $server_login_info->setLogSwitch(1);//日志开关；1-关闭；2-开启
            $server_login_info->setCode(1);
            $server_login_info->setUid($user->uid);
            $server_login_info->setNickname($user->nickname);
            $server_login_info->setSex($user->sex);
            $server_login_info->setHeadImgUrl($user->head_img_url);
            $server_login_info->setRoomcard($user->roomcard);
            $server_login_info->setBubble($user->gold);
            $server_login_info->setPasswd(encrypt($user->pwd));
            //$server_login_info->setPhone($user->uphone);
            $server_login_info->setIdnum($user->idnum);
            $server_login_info->setRid($user->rid);
            $server_login_info->setRealname($user->realname);
            $server_login_info->setRoomId($user->room_id);
            $server_login_info->setTeaId($user->tea_id);
            $server_login_info->setToken($user->openid);
            $server_login_info->setSign(md5(env('SIGN')));
            if(empty($user->tea_id)){
                $temp_teaid = floor($user->room_id/100);
            }else{
                $temp_teaid = $user->tea_id;
            }

            $sysMssage = $this->GetMessage($temp_teaid,$user->room_id);
            $server_login_info->setHallId($sysMssage['hallid']);//大厅号
            $server_login_info->setServerType($sysMssage['game_type']);//大厅游戏类型
            $server_login_info->setVoice($sysMssage['voice']);//语音开关
            $server_login_info->setEmbar($sysMssage['embar']);//是否禁止分享
            $server_login_info->setMarquee($sysMssage['marquee']);//跑马灯
            $server_login_info->setUrgent($sysMssage['urgent']);//紧急通知
            if($gw_type == 1){
                $domain_info = config('conf.GAME_DOMAIN_APP');
            }else if($gw_type == 2){
                $domain_info = config('conf.GAME_DOMAIN_H5');
            }else{
                return $this->error_message(ErrorCode::Error_System);
            }
            foreach ($domain_info as $value){
                $server_domain_info = new ServerDomainInfo();
                $server_domain_info->setIndex($value['INDEX']);
                $server_domain_info->setDomain($value['DOMAIN']);
                $server_domain_info->setPort($value['PORT']);
                $server_domain_info->setStatus($value['STATUS']);
                $server_login_info->getDomainInfo()[] = $server_domain_info;
            }

            $send_data = $server_login_info->encode();
            CommonFunc::message_xor($send_data);
            return $send_data;
        }catch (\Exception $e){
            return $this->error_message(ErrorCode::Error_System);
        }


    }
}
