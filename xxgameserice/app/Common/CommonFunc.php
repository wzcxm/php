<?php

namespace App\Common;

class CommonFunc
{

	//异或加密
	public static function message_xor(&$strInput)
	{
		$key = 109;
		for ($i = 0; $i < strlen($strInput); $i++)
			$strInput[$i] = chr(ord($strInput[$i]) ^ $key);
	}
	
	//生成指定长度的随机字符串
	public static function random_string($length)
	{
		$strRandom = "";
		$strSymbols = "!@#$^*()+-=";
		for ($i = 0; $i < $length; $i++) {
			$tmp_int = mt_rand(1, 4);
			if ($tmp_int == 1)
				$tmp_char = chr(mt_rand(48, 57));
			else if ($tmp_int == 2)
				$tmp_char = chr(mt_rand(65, 90));
			else if ($tmp_int == 3)
				$tmp_char = chr(mt_rand(97, 122));
			else
				$tmp_char = $strSymbols[mt_rand(0, 10)];
			$strRandom .= $tmp_char;
		}
		return $strRandom;
	}
}
