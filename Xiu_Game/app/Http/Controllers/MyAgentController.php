<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyAgentController extends Controller
{

    public function myAgent(){
        $sql = ' select count(*) as count_num from xx_user where ';
        if(session('roleid') == 4){
            $sql .= '(rid = 2 or rid = 3) and super_aisle = '.session('aisle');
        }else if(session('roleid') == 3){
            $sql .= ' rid = 2 and chief_aisle = '.session('aisle');
        }else{
            $sql .= ' rid = 2  and front_uid = '.session('uid');
        }
        $data = DB::select($sql);
        $total = $data[0]->count_num;
        return view('MyAgent.MyAgent',['total'=>$total,'role'=>session('roleid')]);
    }
    //我的代理
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        if(session('roleid') == 4){
            $where = ' (rid = 2 or rid = 3) and super_aisle = '.session('aisle');
        }
        else if(session('roleid') == 3){
            $where = ' rid = 2 and chief_aisle = '.session('aisle');
        }else{
            $where = ' rid = 2  and front_uid = '.session('uid');
        }
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $orderby = ' create_time desc ';
        $menu_arr = CommClass::PagingData($page,$rows,"xx_user" ,$where,$orderby);
        return response()->json($menu_arr);
    }


    /*
     * 设置总代
     */
    public function setRole(Request $request){
        try{
            $data = isset($request['data'])?$request['data']:"";
            $dl_uid = isset($data['uid'])?$data['uid']:0;
            $rate = isset($data['rate'])?$data['rate']:0;
            $my_rate = DB::table('xx_sys_proxyscale')->where('uid',session('uid'))->first();
            if(!empty($dl_uid)){
                if($rate > $my_rate->scale){
                    return response()->json(['error'=>'提成比例不能大于您的提成比例！']);
                }
                $dl_model = Users::find($dl_uid);
                if(!empty($dl_model) && $dl_model->rid != 3){
                    $my_aisle = $this->getAisle(session('aisle'));
                    DB::table('xx_user')->where('uid',$dl_uid)
                        ->update(['rid'=>3,
                            'aisle'=>$my_aisle,
                            'super_aisle'=>session('aisle'),
                            'chief_aisle'=>0,
                            'front_uid'=>0]);
                }
                $scale = DB::table('xx_sys_proxyscale')->where('uid',$dl_uid)->get();
                if(empty($scale) || count($scale)<=0){
                    DB::table('xx_sys_proxyscale')->insert(['uid'=>$dl_uid,'scale'=>$rate]);
                }else{
                    DB::table('xx_sys_proxyscale')->where('uid',$dl_uid)->update(['scale'=>$rate]);
                }
                return response()->json(['error'=>'']);
            }else{
                return response()->json(['error'=>'代理ID错误！']);
            }
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }

    }

    private function getAisle($aisle){
        $data = DB::select("select max(aisle) max_aisle from xx_user where aisle LIKE '".$aisle."%'");
        if(empty($data) || empty($data[0]->max_aisle) || $data[0]->max_aisle == $aisle){
            return $aisle.'01';
        }else{
            return ++$data[0]->max_aisle;
        }
    }

    public function myPlayer(){
        $sql = ' select count(*) as count_num from xx_user where ';
        if(session('roleid') == 4){
            $sql .= ' rid = 5  and super_aisle = '.session('aisle');
        }else if(session('roleid') == 3){
            $sql .= ' rid = 5 and chief_aisle = '.session('aisle');
        }else{
            $sql .= ' rid = 5  and chief_uid = '.session('uid');
        }
        $data = DB::select($sql);
        $total = $data[0]->count_num;
        return view('MyAgent.MyService',['total'=>$total]);
    }

    //我的玩家
    public function getPlayer(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        if(session('roleid') == 4){
            $where = ' rid = 5 and super_aisle = '.session('aisle');
        }
        else if(session('roleid') == 3){
            $where = ' rid = 5 and chief_aisle = '.session('aisle');
        }else{
            $where = ' rid = 5  and chief_uid = '.session('uid');
        }
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        $orderby = ' create_time desc ';
        $menu_arr = CommClass::PagingData($page,$rows,"xx_user" ,$where,$orderby);
        return response()->json($menu_arr);
    }
}
