<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Jur;
use App\Models\Menus;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //获取角色列表
    public function getRole(Request $request){
        try{
            $data = Role::all();
            $json = CommClass::DataToTreeJson($data,'roleid','rname');
            return response()->json($json);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    //获取菜单列表
    public function getMenus(Request $request){
        try{
            $data = Menus::all();
            $json = CommClass::DataToTreeJson($data,'menuid','name','frontid',0);
            return response()->json($json);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    //根据角色id获取对应的菜单
    public function getPower($rid){
        try{
            $mids = Jur::where('roleid',$rid)->select('menuid')->get();
            return response()->json($mids);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    //保存权限设置
    public function save(Request $request){
        try{
            $Error = "";
            $role_node = $request['role'];
            $menu_nodes = $request['menus'];
            if(empty($role_node)){
                $Error = "请选择角色！";
            }else{
                if(empty($menu_nodes)){
                    $Error = "请选择菜单！";
                }else{
                    //删除原有的权限设置记录
                    Jur::where('roleid',$role_node)->delete();
                    //添加新的权限设置记录
                    foreach ($menu_nodes as $menu_node){
                        $power =  new Jur();
                        $power->roleid = $role_node;
                        $power->menuid = $menu_node;
                        $power->save();
                    }
                    //更新菜单缓存
                    Cache::forget('RM');
                    Cache::forever('RM', DB::table('role_menu')->get());
                }
            }
            return response()->json(['Error'=>$Error]);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
