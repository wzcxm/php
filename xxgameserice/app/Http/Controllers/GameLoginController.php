<?php

namespace App\Http\Controllers;

use App\Common\CommonFunc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CsppLogin\ErrorCode;
use CsppLogin\ClientLoginInfo;
use CsppLogin\ServerLoginInfo;
use CsppLogin\ServerDomainInfo;

class GameLoginController extends Controller
{
	public function login($uid, $type, $value)
	{
		try {
			//if ($uid < 9000 || $uid >= 10000)
			//	return $this->error_message(ErrorCode::Error_WeiXin_Login);
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
				default:
					break;
			}

			return $this->error_message(ErrorCode::Error_WeiXin_Login);
		} catch (\Exception $e)
		{
			return $this->error_message(ErrorCode::Error_System);
		}
	}

	//游客登录 ID(9401-9500)
	private function youke_login()
	{
		$uid = mt_rand(9401, 9500);
		$user = DB::select('select nickname, sex, roomcard, rid, room_id from xx_user where uid = ?', [$uid]);
		if (empty($user))
			return $this->error_message(ErrorCode::Error_Not_Found_User);
		$server_login_info = new ServerLoginInfo();
		$server_login_info->setCode(1);
		$server_login_info->setUid($uid);
		foreach ($user as $value){
			$server_login_info->setNickname($value->nickname);
			$server_login_info->setSex($value->sex);
			$server_login_info->setRoomcard($value->roomcard);
			$server_login_info->setRid($value->rid);
			$server_login_info->setRoomId($value->room_id);
		}
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
	
	//账号登录 ID(9000-9400)
	private function account_login($uid)
	{
		if ($uid < 9000 || $uid > 9400)
			return $this->error_message(ErrorCode::Error_Not_Found_User);
		
		$user = DB::select('select nickname, sex, roomcard, rid, room_id from xx_user where uid = ?', [$uid]);
		if (empty($user))
			return $this->error_message(ErrorCode::Error_Not_Found_User);
		$server_login_info = new ServerLoginInfo();
		$server_login_info->setCode(1);
		$server_login_info->setUid($uid);
		foreach ($user as $value){
			$server_login_info->setNickname($value->nickname);
			$server_login_info->setSex($value->sex);
			$server_login_info->setRoomcard($value->roomcard);
			$server_login_info->setRid($value->rid);
			$server_login_info->setRoomId($value->room_id);
		}

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
				$roomcard = 3;
				$bubble = 0;
				$rid = 5;
				$ustate = 0;
				$uid = 0;
				$room_id = 0;
				$passwd = CommonFunc::random_string(11);

				$user = DB::select('select uid, roomcard, bubble, ustate, room_id from xx_user where unionid = ?', [$unionid]);
				if (empty($user))
				{
					//插入数据
					$uid = DB::insert('insert into xx_user (nickname, head_img_url, sex, roomcard, bubble, openid, unionid, refresh_token, pwd) values(?, ?, ?, ?, ?, ?, ?, ?, ?)',
					 	[$nickname, $head_img_url, $sex, $roomcard, $bubble, $openid, $unionid, $refresh_token, $passwd]);
				}
				else
				{
					foreach ($user as $value){
						$uid = $value->uid;
						$bubble = $value->bubble;
						$roomcard = $value->roomcard;
						$ustate = $value->ustate;
						$room_id = $value->room_id;
					}
					if ($ustate != 0)
						goto error_end;
					//更新数据
					$affected = DB::update('update xx_user set nickname = ?, head_img_url = ?, sex = ?, openid = ?, refresh_token = ?, pwd = ? where unionid = ?',
						[$nickname, $head_img_url, $sex, $openid, $refresh_token, $passwd, $unionid]);
					if ($affected != 1)
						goto error_end;
				}

				$server_login_info = new ServerLoginInfo();
				$server_login_info->setCode(1);
				$server_login_info->setUid($uid);
				$server_login_info->setNickname($nickname);
				$server_login_info->setSex($sex);
				$server_login_info->setHeadImgUrl($head_img_url);
				$server_login_info->setRoomcard($roomcard);
				$server_login_info->setBubble($bubble);
				$server_login_info->setRid($rid);
				$server_login_info->setRoomId($room_id);
				$passwd = encrypt($passwd);
				$server_login_info->setPasswd($passwd);

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
		$user = DB::select('select roomcard, bubble, rid, ustate, refresh_token, room_id from xx_user where uid = ? and pwd = ?',
			[$uid, $passwd]);
		if (empty($user))
			return $this->error_message(ErrorCode::Error_WeiXin_Login);
		
		foreach ($user as $value){
			$roomcard = $value->roomcard;
			$bubble = $value->bubble;
			$rid = $value->rid;
			$ustate = $value->ustate;
			$refresh_token = $value->refresh_token;
			$room_id = $value->room_id;
		}
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

				$server_login_info = new ServerLoginInfo();
				$server_login_info->setCode(1);
				$server_login_info->setUid($uid);
				$server_login_info->setNickname($nickname);
				$server_login_info->setSex($sex);
				$server_login_info->setHeadImgUrl($head_img_url);
				$server_login_info->setRoomcard($roomcard);
				$server_login_info->setBubble($bubble);
				$server_login_info->setRid($rid);
				$server_login_info->setPasswd($old_passwd);
				$server_login_info->setRoomId($room_id);

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
}
