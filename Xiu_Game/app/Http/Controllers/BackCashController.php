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
            return view('CashBuy.back',["role"=>session('roleid')]);
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
        $where = ' 1 = 1 ';
        if(session('roleid')==1){
            if(!empty($uid)){
                $where .= ' and get_id ='.$uid;
            }
        }else{
            $where .= ' and get_id ='.session('uid');
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

    public function NextPage(Request $request, $offset){
        try{
            $start = $request['start_date'];
            $end = $request['end_date'];
            if (empty($start) && empty($end)) {
                $start = date('Y-m-01');
                $end =  date('Y-m-t', strtotime($start)) . " 23:59:59";
            } else {
                if (empty($start)) {
                    $start = date('Y-m-01', strtotime($end));
                }
                if (empty($end)) {
                    $end = date('Y-m-t', strtotime($start)) . " 23:59:59";
                }else{
                    $end = $end.' 23:59:59';
                }
            }
            $arr = [];
            if(empty($request['userid'])){
                Array_push($arr,["get_id","=",session('uid')]);
            }else{
                if(session('roleid')==1){
                    Array_push($arr,["get_id","=",$request['userid']]);
                }
            }
            $List = BackGold::where($arr)
                ->whereBetween('create_time', [$start, $end])
                ->offset($offset)
                ->limit(10)
                ->get();
            $gold = collect($List)->sum("backgold");
            return response()->json(["backgold"=>$gold,"List"=>$List]);
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
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
        return view('CashBuy.extract',['backgold'=>$user->backgold]);
    }
    //提现
    public function ext($gold){
        try{
            $model = Users::find(session('uid'));
            if($gold > $model->backgold){
                return response()->json(["status"=>0,"Error"=>"提现金额不能大于可提现金额！"]);
            }
            //生成订单号，并保存到数据库
            $openid = session('openid');
            $amount = $gold*100;
            $orderno = WxPayConfig::MCHID . date("YmdHis");
            $extract = new Extract();
            $extract->playerid = session('uid');
            $extract->gold = $gold;
            $extract->orderno = $orderno;
            $extract->status = 0;
            $extract->save();
            //发起提现，并返回提现结果
            $result = $this->GetExtract($openid,$orderno,$amount);
            return response()->json($result);
        }catch (\Exception $e){
            return response()->json(["status"=>0,"Error"=>$e->getMessage()]);
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
                //更新提现数据状态
                DB::table("xx_sys_extract")->where('orderno',$orderno)->update(["status"=>1]);
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
        $where = " playerid = ".session('uid');
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
