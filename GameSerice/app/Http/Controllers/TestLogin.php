<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class TestLogin extends Controller
{
	public function test($uid)
	{
		$key = 'player_'.$uid;
		if (!Cache::has($key))
		{
			$user = DB::select('select nickname, sex, roomcard, rid, room_id from pp_user where uid = ?', [$uid]);
			if (empty($user))
			{
				var_dump('3333');
				return;
			}
			foreach ($user as $value){
				$data[] = array(
					'nickname' => $value->nickname,
					'sex' => $value->sex,
					'roomcard' => $value->roomcard,
					'rid' => $value->rid,
					'room_id' => $value->room_id,
				);
			}
			Cache::add($key, $data, 4320);
		}
		$user = Cache::get($key);
		return $user;

		/*
		$value = 'oWnVY0oa@*()!^+-r-fOV-bbi7oyVMFNG0';
		$value = Crypt::encryptString($value);
		var_dump($value);
		$value = Crypt::decryptString($value);
		var_dump($value);
		*/
	}
}
