<?php

namespace App\Http\Controllers;
use App\Common\CommClass;
use App\Models\BackGold;
use App\Wechat\example\JsApiPay;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Wechat\example\log;
use App\Wechat\example\CLogFileHandler;

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

    public function  updatePhone(Request $request){
        try{
            $tel = isset($request['tel'])?$request['tel']:0;
            $code = isset($request['code'])?$request['code']:0;
            if(empty($tel)){
                return response()->json(['Error'=>'请输入手机号！']);
            }
            if(empty($code)){
                return response()->json(['Error'=>'请输入验证码！']);
            }
            //验证验证码
            $oldcode = \Cache::get($tel);
            if(empty($oldcode) || $oldcode!=$code){
                return response()->json(['Error'=>'验证码错误或已失效,请重新获取！']);
            }
            if(!$this->is_mobile($tel)){
                return response()->json(['Error'=>"请输入合法的手机号!"]);
            }
            $user_tel = DB::table('xx_user')->where("uphone",$tel)->get();
            if(count($user_tel) == 0){
                DB::table('xx_user')->where('uid',session('uid'))->update(['uphone'=>$tel]);
                return response()->json(['Error'=>""]);
            }else{
                return response()->json(['Error'=>"该手机号已绑定，不能重复绑定!"]);
            }

        }catch (\Exception $e){
            return response()->json(['Error'=>"绑定失败！"]);
        }
    }

    //验证手机号是否合法
    private function is_mobile($phone) {
        $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
        if ( preg_match( $search, $phone ) ) {
            return  true ;
        } else {
            return  false ;
        }
    }


    public function updateWx(){
        try{
            $tools = new JsApiPay();
            $data = $tools->GetUserInfo();
            if(!empty($data)){
                //删除该微信已有的账号
                DB::table('xx_user')->where('unionid',$data['unionid'])->delete();
                //微信信息绑定到游戏账号
                DB::table('xx_user')->where('uid',session('uid'))->update([
                    'nickname'=>$data['nickname'],
                    'head_img_url'=>$data['headimgurl'],
                    'sex'=>$data['sex'],
                    'unionid'=>$data['unionid'],
                    'wxopenid'=>$data['openid']
                ]);
                return redirect('/Home');
            }
        }catch (\Exception $e){
            //var_dump($e->getMessage());
            //return response()->json(['status'=>0,'message'=>$e->getMessage()]);
        }

    }

}
