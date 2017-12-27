<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roles=Role::all();
        return view('Role.index',['List'=>$roles]);
    }

    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;

        $menu_arr = CommClass::PagingData($page,$rows,"xx_sys_role" );
        return response()->json($menu_arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        //
        if(empty($id)){
            $role = null;
        }else{
            $role = Role::find($id);
        }
        return view('Role.create',['role'=>$role]);
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
        try{
            $data = isset($request['data'])?$request['data']:"";
            if(empty($data)) {
                return response()->json(['msg'=>'Error NULL']);
            }
            if(empty($data['roleid'])){
                $role = new Role;
            }else{
                $role = Role::find($data['roleid']);
            }
            $role->rname = isset($data['rname'])?$data['rname']:"";
            $role->remarks = isset($data['remarks'])?$data['remarks']:"";
            $role->save();
            return response()->json(['msg'=>1]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
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
        $role=Role::find($id);
        return view('Role.edit',['role'=>$role]);
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
        try{
            $role = Role::find($id);
            $role->rname = $request['rname'];
            $role->remarks = empty($request['remarks'])?"":$request['remarks'];
            $role->save();
            return response()->json(['msg'=>1]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
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
        try {
            $ids=explode(',',$id);
            Role::destroy($ids);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
}
