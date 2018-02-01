<?php

namespace App\Http\Controllers;
use App\Common\CommClass;
use App\Models\BackGold;
use App\Models\CardStrade;
use App\Models\Users;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    //
    public function index(){
        //用户信息
        $user = DB::table('view_user')->where('uid',session('uid'))->first();
        //注册用户
        $count_person=CommClass::GetPerosn();
        //当日登录
        $today_person = CommClass::GetOnlinePerosn();
        //我的积分
        $total_num = $user->money;
        //当月积分
        $start = date('Y-m-01');
        $end =  date('Y-m-t 23:59:59', strtotime($start));
        $month =BackGold::where('get_id',session('uid'))->whereBetween('create_time', [$start, $end])->sum('backgold');
        //菜单
        $mymenus= $this->GetMyMenus(session('roleid'));
        //返回页面的数据
        $retView = [
            'User'=>$user,
            'roleid'=>session('roleid'),
            'count_person'=>$count_person,
            'today_person'=>$today_person,
            'total_num'=>$total_num,
            'month'=>$month,
            'Menus'=>$mymenus
        ];
            return view('Home.index',$retView);
    }

    function  GetMyMenus($roleid){
         $Menus = Cache::rememberForever('RM', function () {
             return DB::table('role_menu')->get();
         });
         return $Menus->where('roleid',$roleid);
    }

}
