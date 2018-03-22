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

	public function login($uid, $type, $value)
	{
		try {
			if ($uid < 9000 || $uid >= 10000)
				return $this->error_message(ErrorCode::Error_WeiXin_Login);
			switch ($type)
			{
				case 1:
					return $this->youke_login();
				case 2:
					return $this->weixin_login($uid, $value);
				case 3:
					return $this->auto_login($uid, $value);
				case 4:
					return $this->account_login($uid);
                case 5:
                    return $this->tel_login($uid,$value);
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
	private function tel_login($tel,$code){
	    //验证验证码
        $oldcode = \Cache::get($tel);
        if(empty($oldcode) || $oldcode!=$code)
            return $this->error_message(ErrorCode::Error_Not_Found_Code);
        //查找用户信息
        $user = DB::table('xx_user')->where("uphone",$tel)->first();
        if (empty($user))
            return $this->error_message(ErrorCode::Error_Not_Found_User);
        $send_data = $this->getServerLoginInfo($user);
        return $send_data;
    }

	//游客登录 ID(9401-9500)
	private function youke_login()
	{

		$uid = mt_rand(9401, 9500);
		$user = DB::table('xx_user')->where("uid",$uid)->first();
		if (empty($user))
			return $this->error_message(ErrorCode::Error_Not_Found_User);
        $send_data = $this->getServerLoginInfo($user);
		return $send_data;
	}
	
	//账号登录 ID(9000-9400)
	private function account_login($uid)
	{
		if ($uid < 9000 || $uid > 9400)
			return $this->error_message(ErrorCode::Error_Not_Found_User);
		
		$user = DB::table('xx_user')->where("uid",$uid)->first();
		if (empty($user))
			return $this->error_message(ErrorCode::Error_Not_Found_User);
        $send_data = $this->getServerLoginInfo($user);
		return $send_data;
	}

	//微信登录
	private function weixin_login($uid, $code)
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
				$head_img_url = $user_data['headimgurl'];
				$unionid = $user_data['unionid'];
				$roomcard = 5;
				$gold = 3000;
				$passwd = CommonFunc::random_string(11);
				$user = DB::table('xx_user')->where("unionid",$unionid)->first();
				if (empty($user))
				{
					//插入数据
					$uid = DB::table('xx_user')->insertGetId([
					    'nickname'=>$nickname,'head_img_url'=>$head_img_url,'sex'=>$sex,'roomcard'=>$roomcard,'gold'=>$gold,
                        'openid'=>$openid,'unionid'=>$unionid,'refresh_token'=>$refresh_token,'pwd'=>$passwd
                    ]);
					//添加成功，更新推荐人的红包
					if(!empty($uid)){
					    $this->setRedPack($unionid);
                    }
				} else {
                    $uid = $user->uid;
                    $ustate = $user->ustate;
					if ($ustate != 0)
						goto error_end;
					//更新数据
					$affected = DB::table('xx_user')->where('unionid',$unionid)
                        ->update([
                        'nickname'=>$nickname,'head_img_url'=>$head_img_url,'sex'=>$sex,
                        'openid'=>$openid,'refresh_token'=>$refresh_token,'pwd'=>$passwd
                        ]);
					if ($affected != 1)
						goto error_end;
				}
                //生成protobuf
                $new_user = DB::table('xx_user')->where("uid",$uid)->first();
                $send_data = $this->getServerLoginInfo($new_user);
				curl_close($curl);
				return $send_data;
			}
		}
		error_end:
		curl_close($curl);
		return $this->error_message(ErrorCode::Error_WeiXin_Login);
	}

	//自动登录
	private function auto_login($uid, $passwd)
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
				$head_img_url = $user_data['headimgurl'];

				//更新数据
				$affected = DB::update('update xx_user set nickname = ?, head_img_url = ?, sex = ?, refresh_token = ? where uid = ?',
					[$nickname, $head_img_url, $sex, $refresh_token, $uid]);
                if ($affected != 1)
                    goto error_end;
                //生成protobuf
				$new_user = DB::table('xx_user')->where("uid",$uid)->first();
                $send_data = $this->getServerLoginInfo($new_user);
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

	//生成登录返回protobuf数据
    private function getServerLoginInfo($user){
        $server_login_info = new ServerLoginInfo();
        $server_login_info->setCode(1);
        $server_login_info->setUid($user->uid);
        $server_login_info->setNickname($user->nickname);
        $server_login_info->setSex($user->sex);
        $server_login_info->setRoomcard($user->roomcard);
        $server_login_info->setBubble($user->gold);
        $server_login_info->setRid($user->rid);
        $server_login_info->setRoomId($user->room_id);
        //$server_login_info->setPhone($user->uphone);
        $server_login_info->setTeaId($user->tea_id);
        $server_login_info->setToken($user->openid);
        $server_login_info->setSharestatus($user->lottery);
        $server_login_info->setSign(encrypt(env('SIGN')));
        $sysMssage = $this->GetMessage($user->uid,$user->tea_id);
        $server_login_info->setHallId($sysMssage['hallid']);//大厅号
        $server_login_info->setServerType($sysMssage['game_type']);//大厅游戏类型
        $server_login_info->setMarquee($sysMssage['marquee']);//跑马灯
        $server_login_info->setUrgent($sysMssage['urgent']);//紧急通知
        $domain_info = config('conf.GAME_DOMAIN');
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
    }


    //获取玩家的茶楼大厅、游戏类型、公告、紧急通知
    private function GetMessage($uid,$teaid){
        //所在的茶楼大厅号
        $hallid = DB::table('xx_sys_teas')->where([['uid',$uid],['tea_id',$teaid]])->value('hall_id');
        $hallid = empty($hallid)?0:$hallid;
        //所在茶楼大厅的游戏类型
        if($hallid==0){
            $game_type = 0;
        }else{
            $type_id = 'type'.$hallid;
            $game_type = DB::table('xx_sys_tea')->where('tea_id',$teaid)->value($type_id);
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
        return ['hallid'=>$hallid,'game_type'=>$game_type,'marquee'=>$marquee,'urgent'=>$urgent];
    }

    //设置推荐人的人数和红包
    private function setRedPack($unionid){
	    try{
	        $temp_user = DB::table('xx_user_temp')->where('unionid',$unionid)->first();
	        if(!empty($temp_user)){
	            if(!empty($temp_user->front)){
                    $front = DB::table('xx_user')->where('uid',$temp_user->front)->first();
                    if(!empty($front)){
                        $sharenum = $front->sharenum + 1;
                        $redbag = $front->redbag + 2;
                        $red_name = "2元红包";
                        $red_other = "";
                        if($sharenum == 20){ //如果推荐满20人，额外送18.8元
                            $redbag += 18.8;
                            $red_other = "18.8元红包";
                        }else if ($sharenum == 50){//如果推荐满50人，额外送38.8元
                            $redbag += 38.8;
                            $red_other = "38.8元红包";
                        }else if ($sharenum == 80){//如果推荐满80人，额外送58.8元
                            $redbag += 58.8;
                            $red_other = "58.8元红包";
                        }
                        //更新推荐人的推荐人数和红包金额
                        DB::table('xx_user')
                            ->where('uid',$temp_user->front)
                            ->update(['sharenum'=>$sharenum,'redbag'=>$redbag]);
                        //保存红包记录
                        if(!empty($red_name)){
                            DB::table('xx_sys_prize')->insert(['name'=>$red_name,'uid'=>$temp_user->front,'type'=>2,'jptype'=>3]);
                        }
                        if(!empty($red_other)){
                            DB::table('xx_sys_prize')->insert(['name'=>$red_other,'uid'=>$temp_user->front,'type'=>2,'jptype'=>3]);
                        }
                    }
                }
                //保存我的微信openid
                DB::table('xx_user')
                    ->where('unionid',$unionid)
                    ->update(['wxopenid'=>$temp_user->wxopenid]);
            }
        }catch (\Exception $e){

        }
    }

}
