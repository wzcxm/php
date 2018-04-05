<?php
namespace App\Common;

class WeChatHelper{

    const APPID = 'wx052f88f97207e5b0';
    const APPSECRET = '9785764b07bd0dfc4ca8fbb056df1404';
    const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
    const CURL_PROXY_PORT = 0;//8080;

    //发送Get请求
    private function HttpGet($url){
        //初始化curl
        $ch = curl_init();
        //设置超时
        //curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if(WeChatHelper::CURL_PROXY_HOST != "0.0.0.0"
            && WeChatHelper::CURL_PROXY_PORT != 0){
            curl_setopt($ch,CURLOPT_PROXY, WeChatHelper::CURL_PROXY_HOST);
            curl_setopt($ch,CURLOPT_PROXYPORT, WeChatHelper::CURL_PROXY_PORT);
        }
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res,true);
    }


    private function GetCode()
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            //$openid = $this->getOpenidFromMp($code);
            return $code;
        }
    }

    public function GetUserInfo(){
        $code = $this->GetCode();

        $url = $this->__CreateOauthUrlForOpenid($code);
        //取出openid
        $data = $this->HttpGet($url);
        if (!array_key_exists("errcode", $data)) {
            $this->data = $data;
            $openid = $data['openid'];
            $access_token = $data['access_token'];
            $url = $this->__CreateOauthUrlForUserInfo($access_token,$openid);
            //取出unionid
            $data = $this->HttpGet($url);
            if (!array_key_exists("errcode", $data)){
                return $data;
            }
            else{
                return "";
            }
        }
    }
    //生成获取用户信息的url
    private function __CreateOauthUrlForUserInfo($access_token,$openid)
    {
        $urlObj["access_token"] = $access_token;
        $urlObj["openid"] = $openid;
        $urlObj["lang"] = "zh_CN";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/userinfo?".$bizString;
    }
    /**
     *
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     *
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = WeChatHelper::APPID;
        $urlObj["secret"] = WeChatHelper::APPSECRET;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }


    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = WeChatHelper::APPID;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_userinfo"; //snsapi_base
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }


    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }
}
