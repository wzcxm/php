<?php

namespace App\Http\Middleware;

use App\Common\CommClass;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AuthenUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userid= session('uid');
        $roleid = session('roleid');
        if($userid !='ADMIN'){
            $url = $request->getRequestUri();
            $action = explode('/',$url);
            if(empty($userid)){
                return redirect('/');
            }else{
                if(!$this->is_auth($roleid,$action[1])){
                    return redirect('/');
                }
            }
        }
        return $next($request);
    }

    private  function is_auth($roleid,$action){
        if(empty($roleid) || empty($action)) return false;
        $role_menu = Cache::rememberForever('RM',function (){
            return DB::table('role_menu')->get();
        });
        $menu = collect($role_menu)->where('roleid',$roleid)->where('linkurl','/'.$action)->first();
        if(empty($menu)){
            return false;
        }else{
            if(($roleid == 2 || $roleid == 3 || $roleid == 6) && $action == 'BuyCard'){
                $isGift = DB::table('xx_sys_isgift')->where('uid',session('uid'))->first();
                if(!empty($isGift)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }
    }
}
