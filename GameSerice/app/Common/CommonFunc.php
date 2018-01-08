<?php

namespace App\Common;

use Aliyun\DySDKLite\SignatureHelper;

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



	///发送短信
    /// $tel:手机号
    /// $template：模板ID
    /// $param：发送参数
    public static function send_sms($tel,$template,$param = null){
	    try{
            $params = array ();
            // *** 需用户填写部分 ***
            // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
            $accessKeyId = "LTAIdFUXxbXWwab4";
            $accessKeySecret = "F4TcWZLDzUnS2zIufVCBtR1vZSwski";

            // fixme 必填: 短信接收号码
            $params["PhoneNumbers"] = $tel;

            // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
            $params["SignName"] = "短信签名";
            // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
            $params["TemplateCode"] = $template;
            // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
            if(!empty($param))
                $params['TemplateParam'] = $param;
            // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
            if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
                $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
            }
            // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
            $helper = new SignatureHelper();

            // 此处可能会抛出异常，注意catch
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                ))
            );
            return $content;
        }catch (\Exception $e){
	        return $e->getMessage();
        }
    }
}
