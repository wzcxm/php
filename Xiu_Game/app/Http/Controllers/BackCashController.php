<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\BackGold;
use App\Models\Extract;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Wechat\lib\WxPayConfig;
use App\Wechat\lib\WxPayApi;
use App\Wechat\lib\WxPayExtract;
use Illuminate\Support\Facades\DB;

class BackCashController extends Controller
{
    //返现查询
    public function index(){
        try{
            return view('CashBuy.back');
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? $request['uid']:"";
        $start_date = isset($request['start_date']) ? $request['start_date']:"";
        $end_date = isset($request['end_date']) ? $request['end_date']:"";
        $where = ' and get_id ='.session('uid');
        if(!empty($uid)){
            $where .= ' and back_id ='.$uid;
        }
        if (!empty($start_date) || !empty($end_date)) {
            $where .= " and create_time between '";
            if (!empty($start_date)) {
                $where .= $start_date . "' and '";
            } else {
                $where .= date('Y-m-01', strtotime($end_date)) . "' and '";
            }
            if (!empty($end_date)) {
                $where .= $end_date . " 23:59:59'";
            } else {
                $where .= date('Y-m-t', strtotime($start_date)) . " 23:59:59'";
            }
        }
        $orderby = ' create_time desc ';
        $order_arr = CommClass::PagingData($page,$rows,"view_backcash",$where , $orderby);
        //增加合计行
        $sql = 'select * from view_backcash where '.$where;
        $data = DB::select($sql);
        $total = collect($data)->sum('backgold');
        $total_gold = collect($data)->sum('gold');
        $footer =[['b_head'=>'合计','gold'=>$total_gold,'backgold'=>$total]];
        $order_arr['footer'] = $footer;
        return response()->json($order_arr);
    }

    //提现
    public function extract(){
        $user = Users::find(session('uid'));
        if(!empty($user) && !empty($user->ext_pwd)){
            return redirect('/Extract/login');
        }else{
            return redirect('/Extract/first');
        }
//        return view('CashBuy.extract',['backgold'=>$user->backgold]);
    }

    //设置提现密码
    public function savepwd(Request $request){
        try{
            $pwd = isset($request['pwd'])?$request['pwd']:"";
            if(!empty($pwd)){
                $user = Users::find(session('uid'));
                $user->ext_pwd = md5($pwd);
                $user->save();
                return response()->json(["Error"=>""]);
            }else{
                return response()->json(["Error"=>"保存失败！"]);
            }
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }
    //登录
    public function login(Request $request){
        try{
            $pwd = isset($request['pwd'])?$request['pwd']:"";
            if(!empty($pwd)){
                $user = Users::find(session('uid'));
                if(md5($pwd)==$user->ext_pwd){
                    return response()->json(["Error"=>""]);
                }else{
                    return response()->json(["Error"=>"密码错误！"]);
                }
            }else{
                return response()->json(["Error"=>"登录失败！"]);
            }
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    public function take(){
        $user = Users::find(session('uid'));
        return view('CashBuy.extract',['backgold'=>$user->money]);
    }
    //提现
    public function ext($gold){
        try{
            $model = Users::find(session('uid'));
            if($gold > $model->money){
                return response()->json(["status"=>0,"Error"=>"提现金额不能大于可提现金额！"]);
            }
            //生成订单号
            $openid = $model->wxopenid;
            $amount = $gold*100;
            $orderno = WxPayConfig::MCHID . date("YmdHis");
            //发起提现，并返回提现结果
            $result = $this->GetExtract($openid,$orderno,$amount);
            //如果提现成功，减去积分
            $ret_msg = ["Error"=>""];
            if($result['status']==1){
                //支付成功，扣除用户的积分
                DB::table('xx_user')->where('uid',session('uid'))->decrement('money', $gold);
                //将提现记录保存
                DB::table('xx_sys_extract')->insert(['playerid'=>session('uid'),'gold'=>$gold,'orderno'=>$orderno]);
            }else{
                //返回错误信息
                $ret_msg = ["Error"=>$result['Error']];
            }
            return response()->json($ret_msg);
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    //提交微信申请提现
    private function GetExtract($openid,$orderno,$amount){
        try{
            $input = new WxPayExtract();
            $input->SetPartner_Trade_No($orderno);
            $input->SetCheck_Name("NO_CHECK");
            $input->SetOpenid($openid);
            $input->SetAmount($amount);
            $input->SetDesc("提现");
            $result = WxPayApi::Extract($input);
            $result = $input->FromXml($result);
            if($result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
                return ["status"=>1,"Error"=>""];
            }else{
                //如果返回的的是系统忙，则用原来的定单号再提交一次
                if($result["err_code"] == "SYSTEMERROR"){
                    $this->GetExtract($openid,$orderno,$amount);
                }else{
                    return ["status"=>0,"Error"=>$result["err_code"]."|".$result["err_code_des"]];
                }
            }
        }catch (\Exception $e){
            return ["status"=>0,"Error"=>$e->getMessage()];
        }
    }


    //提现记录
    public function extlist(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $where = " status = 0 and playerid = ".session('uid');
        $orderby = ' create_time desc ';
        $order_arr = CommClass::PagingData($page,$rows,"xx_sys_extract",$where,$orderby);
        //增加合计行
        $sql = 'select * from xx_sys_extract where '.$where;
        $data = DB::select($sql);
        $total = collect($data)->sum('gold');
        $footer =[['playerid'=>'合计','gold'=>$total]];
        $order_arr['footer'] = $footer;
        return response()->json($order_arr);
    }
}
