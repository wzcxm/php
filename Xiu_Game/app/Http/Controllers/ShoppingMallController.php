<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Http\Request;
use App\Models\ShoppingMall;

class ShoppingMallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('ShoppingMall.index',['List'=>ShoppingMall::all()]);
    }

    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $msg_arr = CommClass::PagingData($page,$rows,"xx_sys_mall" );
        return response()->json($msg_arr);
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
            $mall = null;
        }else{
            $mall = ShoppingMall::find($id);
        }
        return view('ShoppingMall.create',['Mall'=>$mall]);
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
            if(empty($data['sid'])){
                $shopping = new ShoppingMall();
            }else{
                $shopping = ShoppingMall::find($data['sid']);
            }
            $shopping->scommodity = isset($data['scommodity'])?$data['scommodity']:"";
            $shopping->sprice = isset($data['sprice'])?$data['sprice']:0;
            $shopping->snumber = isset($data['snumber'])?$data['snumber']:0;
            $shopping->sgive = isset($data['sgive'])?$data['sgive']:0;
            $shopping->sremarks = isset($data['sremarks'])?$data['sremarks']:"";
            $shopping->type = isset($data['type'])?$data['type']:0;
            $shopping->img = isset($data['img'])?$data['img']:0;
            $shopping->isfirst = isset($data['isfirst'])?$data['isfirst']:0;
            return response()->json(['msg'=>1]);
        }
        catch (\Exception $e){
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
        return view('ShoppingMall.edit',['spml'=>ShoppingMall::find($id)]);
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
            $shopping= ShoppingMall::find($id);
            $shopping->scommodity=$request['scommodity'];
            $shopping->sprice=$request['sprice'];
            $shopping->snumber=$request['snumber'];
            $shopping->sgive=$request['sgive'];
            $shopping->sremarks=$request['sremarks'];
            $shopping->save();
            return response()->json(['msg'=>1]);
        }
        catch (\Exception $e){
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
            ShoppingMall::destroy($ids);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
}
