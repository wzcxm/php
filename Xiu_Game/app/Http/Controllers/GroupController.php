<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupInfo;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Wechat\example\JsApiPay;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //
    public function index(){
        $List = Group::where('uid',session('uid'))->get();
        return view('Group.index',['List'=>$List]);
    }
    ///保存群
    public function save($groupname){
        try{
            if(strpos($groupname, "元") !== false){
                return response()->json(["Error"=>"群名称包含了敏感字符：元"]);
            }else if(strpos($groupname, "块") !== false){
                return response()->json(["Error"=>"群名称包含了敏感字符：块"]);
            }else{
                $group = Group::where([['group_name','=',$groupname],['group_id','=',session('uid')]])->get();
                if(sizeof($group)>0){
                    return response()->json(["Error"=>"群名称已存在！"]);
                }else{
                    $id = DB::table('xx_sys_tea')->insertGetId(
                        ['uid' =>session('uid'), 'tea_name' => $groupname]
                    );
                    DB::table("xx_sys_teas")->insert([
                        'uid'=>session('uid'),
                        'groupid'=>$id
                    ]);
                    $this->SetRedis($id);
                    return response()->json(["Error"=>""]);
                }
            }
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    ///解散群
    public function del($id){
        try{
            DB::table("xx_sys_teas")->where('groupid','=',$id)->delete();
            Group::destroy($id);
            //删除redis
            Redis::set('group_'.$id,'');
            return response()->json(["Error"=>""]);
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }


    ///编辑群
    public function info($id){
        $List = DB::table('view_groupinfo')->where('groupid',$id)->get();
        $model = Group::find($id);
        return view('Group.info',['List'=>$List,'Group'=>$model]);
    }

    ///保存群玩家
    public function infosave($id,$player){
        try{
            $user = Users::find($player);
            if(empty($user)){
                return response()->json(["Error"=>"玩家ID不存在，请重新输入"]);
            }else{
                $groupinfo = GroupInfo::where([['groupid','=',$id],['uid','=',$player]])->get();
                if(sizeof($groupinfo)<=0){
                    DB::table("xx_sys_teas")->insert([
                        'uid'=>$player,
                        'groupid'=>$id
                    ]);
                    $this->SetRedis($id);//设置redis
                    return response()->json(["Error"=>""]);
                }else{
                    return response()->json(["Error"=>"玩家ID已在群中，不能重复添加！"]);
                }
            }
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    ///删除群玩家
    public function  infodel($id){
        try{
            $info = GroupInfo::find($id);
            if($info->uid == session('uid')){
                return response()->json(["Error"=>"不能删除群主！"]);
            }else{
                GroupInfo::destroy($id);
                //设置redis
                $this->SetRedis($info->groupid);
                return response()->json(["Error"=>""]);
            }
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    ///设置redis
    private function SetRedis($groupid){
        try{
            $group = Group::find($groupid);
            $palyers = $group->group_id;
            $groupinfo = GroupInfo::where('groupid',$groupid)->get();
            if(!empty($groupinfo)){
                foreach ($groupinfo as $item) {
                    $palyers .= '|'.$item->player_id;
                }
            }
            //添加redis
            Redis::set('group_'.$groupid,$palyers);
        }catch (\Exception $e){

        }
    }

    //修改群名称
    public function edit($id){
        $group = Group::find($id);
        return view('Group.edit',['Group'=>$group]);
    }

    //保存群名称
    public function editSave($id,$name){
        try{
            $group = Group::find($id);
            $group->tea_name = $name;
            $group->save();
            return response()->json(["Error"=>""]);
        }catch (\Exception $e){
            return response()->json(["Error"=>$e->getMessage()]);
        }
    }

    //战绩查询
    public function grouprecord($id){
        $start = date('Y-m-01');
        $end = date('Y-m-t'). " 23:59:59";
        $List = $this->GetList($id,$start,$end,0);
        return view('Group.record',['List'=>$List,'g_id'=>$id]);
    }
    public function RecordSearch($gid,$start,$end){
        try{
            if (empty($start) && empty($end)) {
                $start = date('Y-m-01');
                $end = date('Y-m-t'). " 23:59:59";
            } else {
                if (empty($start)) {
                    $start = date('Y-m-01', strtotime($end));
                }
                if (empty($end)) {
                    $end = date('Y-m-t', strtotime($start)) . " 23:59:59";
                }
            }
            $List = $this->GetList($gid,$start,$end,0);
            return response()->json(['List'=>$List]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    //二维码
    public function getQrCode($id){
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/AddGroup/'.$id;
        return view('Group.qrcode',['Url'=>$url]);
    }

    public function addGroup($id){
        try{
            if(!empty($id)){
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                if (strpos($user_agent, 'MicroMessenger') === false) {
                    return "<div style='width:100%;text-align: center;font-size: 50px;font-weight: bold;'>请使用微信扫描！</div>";
                } else {
                    //获取用户微信信息
                    $tools = new JsApiPay();
                    $openid = $tools->GetOpenid();
                    $unionid = $tools->data['unionid'];
                    $user = Users::where('unionid', $unionid)->first();
                    $group = Group::find($id);
                    if(empty($user)){
                        return "<div style='width:100%;text-align: center;font-size: 50px;font-weight: bold;'>您还没有下载游戏，请下载并登陆游戏后，再加入群！</div>";
                    }
                    $groupinfo = DB::table("xx_sys_teas")->where([['player_id',$user->uid],['groupid',$id]])->get();
                    if(!empty($groupinfo)){
                        return  "<div style='width:100%;text-align: center;font-size: 50px;font-weight: bold;'>您已经加入该群，不能重复加入！</div>";
                    }
                    DB::table("xx_sys_teas")->insert([
                            'uid'=>$user->uid,
                            'groupid'=>$id
                    ]);
                    $this->SetRedis($id);//设置redis
                    return view('Group.addgroup',['group'=>$group->group_name]);
                }
            }
        }catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function NextPage(Request $request){
        try {
            $data = $request['data'];
            $start = $data['start'];
            $end = $data['end'];
            $gid = $data['g_id'];
            $offset = $data['offset'];
            if (empty($start) && empty($end)) {
                $start = date('Y-m-01');
                $end = date('Y-m-t 23:59:59');
            } else {
                if (empty($start)) {
                    $start = date('Y-m-01', strtotime($end));
                }
                if (empty($end)) {
                    $end = date('Y-m-t 23:59:59', strtotime($start)) ;
                }else{
                    $end = date('Y-m-d 23:59:59', strtotime($end)) ;
                }
            }
            $List = $this->GetList($gid,$start,$end,$offset);
            return response()->json(['List'=>$List]);
        }catch (\Exception $e){
            return response()->json(['Error' => $e->getMessage()]);
        }
    }

    private function GetList($gid,$start,$end,$offset){
        try{
            $sql = <<<EOT
            select t.*, r.player_id,r.player_name
            from view_item_record t
            left join game_player_record r on r.roomid=t.roomid and r.create_time=t.r_time and r.group_id = t.group_id
            where r.is_open = 1 and t.group_id = $gid and t.r_time Between '$start' and '$end'
            ORDER  BY t.r_time DESC  LIMIT $offset,10 
EOT;
         return DB::connection('mysql_game')->select($sql);
        }catch (\Exception $e){
            return [];
        }
    }
}
