<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;
use App\Models\Menus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Mockery\Exception;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Menus=Menus::where('frontid',1)->get();
        return view('Menus.Index',['List'=>$Menus]);
        //
    }

    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $where = ' frontid = 1 ';
        $menu_arr = CommClass::PagingData($page,$rows,"xx_sys_menu" ,$where);
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
            $menu = null;
        }else{
            $menu = Menus::find($id);
        }
        return view('Menus.create',['menu'=>$menu]);
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
           if(empty($data['menuid'])){
               $menu = new Menus;
           }else{
               $menu = Menus::find($data['menuid']);
           }
           $menu->name = isset($data['name'])?$data['name']:"";
           $menu->linkurl = isset($data['linkurl'])?$data['linkurl']:"";
           $menu->remarks = isset($data['remarks'])?$data['remarks']:"";
           $menu->icon = isset($data['icon'])?$data['icon']:"";
           $menu->frontid = 1;
           $menu->save();
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
        $menu=Menus::find($id);
        return view('Menus.edit',['menu'=>$menu]);
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
            $menu = Menus::find($id);
            $menu->name = $request['name'];
            $menu->linkurl = $request['linkurl'];
            $menu->remarks = $request['remarks'];
            $menu->save();
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
            Menus::destroy($ids);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
}
