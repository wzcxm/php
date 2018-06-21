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
            $sql .= '(rid = 2 or rid = 3 or rid = 6) and super_aisle = '.session('aisle');
        }else if(session('roleid') == 3){
            $sql .= ' (rid = 2 or rid = 6) and chief_aisle = '.session('aisle');
        }else if(session('roleid') == 6){
            $sql .= ' rid = 2   and vip_aisle = '.session('aisle');
        }
        else{
            $sql .= ' rid = 2  and front_uid = '.session('uid').' or front_uid in (select uid from xx_user where rid = 2  and front_uid = '.session('uid').')';
        }
        $data = DB::select($sql);
        $total = $data[0]->count_num;
        return view('MyAgent.MyAgent',['total'=>$total,'role'=>session('roleid'),'aisle'=>session('aisle'),'uid'=>session('uid')]);
    }
    //我的代理
    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $uid = isset($request['uid']) ? intval($request['uid']) : 0;
        $cxid = isset($request['cxid']) ? intval($request['cxid']) : 0;
        $cxrid = isset($request['cxrid']) ? intval($request['cxrid']) : 0;
        $retrid = isset($request['retrid']) ? intval($request['retrid']) : 0;
        $retid = isset($request['retid']) ? intval($request['retid']) : 0;
        if(session('roleid') == 4){
            $where = ' (rid = 2 or rid = 3 or rid = 6) and super_aisle = '.session('aisle');
        } else if(session('roleid') == 3){
            $where = ' (rid = 2  or rid = 6) and chief_aisle = '.session('aisle');
        } else if(session('roleid') == 6){
            $where = ' rid = 2   and vip_aisle = '.session('aisle');
        } else{
            $where = ' rid = 2  and front_uid = '.session('uid').' or front_uid in (select uid from xx_user where rid = 2  and front_uid = '.session('uid').')';
        }
        //查询代理
        if(!empty($uid)){
            $where .= " and uid = ".$uid;
        }
        //点击显示下级代理
        if(!empty($cxid) && !empty($cxrid)){
            if($cxrid==4){
                $where = ' (rid = 2 or rid = 3 or rid = 6) and super_aisle = '.$cxid;
            }else if($cxrid==3){
                $where = ' (rid = 2  or rid = 6) and chief_aisle = '.$cxid;
            }else if($cxrid==6){
                $where = ' rid = 2   and vip_aisle = '.$cxid;
            }else{
                $where = ' rid = 2  and front_uid = '.$cxid;
            }
        }
        //后退
        if(!empty($retrid) && !empty($retid)){
            if($retrid == 4){
                $where = ' (rid = 2 or rid = 3 or rid = 6) and super_aisle = '.$retid;
            } else if($retrid == 3){
                $where = ' (rid = 2  or rid = 6) and chief_aisle = '.$retid;
            } else if($retrid == 6){
                $where = ' rid = 2   and vip_aisle = '.$retid;
            } else{
                if(session('uid') == $retid){
                    $where = ' rid = 2  and front_uid = '.$retid.' or front_uid in (select uid from xx_user where rid = 2  and front_uid = '.$retid.')';
                }else{
                    $where = ' rid = 2  and front_uid = '.$retid;
                }

            }
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
                if(session('roleid')==4){ //设置总代
                    if(!empty($dl_model) && $dl_model->rid != 3){
                        $my_aisle = $this->getAisle(session('aisle'));
                        DB::table('xx_user')->where('uid',$dl_uid)
                            ->update(['rid'=>3,
                                'aisle'=>$my_aisle,
                                'super_aisle'=>session('aisle'),
                                'vip_aisle'=>0,
                                'chief_aisle'=>0,
                                'front_uid'=>0]);
                    }
                }else if(session('roleid')==3){//设置vip代理
                    if(!empty($dl_model) && $dl_model->rid != 6){
                        if($dl_model->chief_uid != session('uid')){
                            return response()->json(['error'=>'该代理不是通过您的二维码下载的，不能设置！']);
                        }
                        $count_dl = DB::table('xx_user')->where('front_uid',$dl_uid)->count();
                        if($count_dl>0){
                            return response()->json(['error'=>'该代理已经发展了下级代理，不能设置！']);
                        }
                        $my_aisle = $this->getAisle(session('aisle'),3);
                        DB::table('xx_user')->where('uid',$dl_uid)
                            ->update(['rid'=>6,
                                'aisle'=>$my_aisle,
                                'vip_aisle'=>0,
                                'front_uid'=>0]);
                    }
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

    private function getAisle($aisle,$type=4){
        $data = DB::select("select max(aisle) max_aisle from xx_user where aisle LIKE '".$aisle."%'");
        if(empty($data) || empty($data[0]->max_aisle) ){
                return $aisle.'01';
        }else{
            if($type == 4 && $data[0]->max_aisle == $aisle)
                return $aisle.'01';
            else
                return ++$data[0]->max_aisle;
        }
    }

    public function myPlayer(){
        $sql = ' select count(*) as count_num from xx_user where ';
        if(session('roleid') == 4){
            $sql .= ' rid = 5  and super_aisle = '.session('aisle');
        }else if(session('roleid') == 3){
            $sql .= ' rid = 5 and chief_aisle = '.session('aisle');
        }else if(session('roleid') == 6){
            $sql .= ' rid = 5  and vip_aisle = '.session('aisle');
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
        }
        else if(session('roleid') == 6){
            $where = ' rid = 5 and vip_aisle = '.session('aisle');
        }
        else{
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
