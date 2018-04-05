<?php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

require __DIR__.'/../app/Common/CommClass.php';
require __DIR__.'/../app/Common/message.pb.php';
require __DIR__.'/../app/Common/SignatureHelper.php';
require __DIR__.'/../app/Common/WeChatHelper.php';
require __DIR__.'/../app/Wechat/lib/WxPay.Api.php';
require __DIR__.'/../app/Wechat/lib/WxPay.Config.php';
require __DIR__.'/../app/Wechat/lib/WxPay.Data.php';
require __DIR__.'/../app/Wechat/lib/WxPay.Exception.php';
require __DIR__.'/../app/Wechat/lib/WxPay.Notify.php';
require __DIR__.'/../app/Wechat/example/WxPay.JsApiPay.php';
require __DIR__.'/../app/Wechat/example/WxPay.MicroPay.php';
require __DIR__.'/../app/Wechat/example/WxPay.NativePay.php';
require __DIR__.'/../app/Wechat/example/log.php';
