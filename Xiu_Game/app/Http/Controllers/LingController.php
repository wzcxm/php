<?php

namespace App\Http\Controllers;

use App\Models\Ling;
use App\Models\Users;
use Illuminate\Http\Request;

class LingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Liang.index',['List'=>Ling::orderBy('id','asc')->offset(0)->limit(10)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Liang.create');
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
            $count = Ling::where('liang',$request['liang'])->count();
            if($count==0) {
                $liang = new Ling;
                $liang->liang = $request['liang'];
                $liang->state = 0;
                $liang->olduid = 0;
                $liang->save();
                return response()->json(['msg' => 1]);
            }
            else{
                return response()->json(['msg' => 2]);
            }
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
        return view('Liang.edit',['liang'=>Ling::find($id)]);
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
            $liang = Ling::find($id);
            $liang->liang = $request['liang'];
            $liang->state = 1;
            $liang->olduid = $request['olduid'];
            $liang->save();
            $user = Users::find($request['olduid']);
            $user->uid = $request['liang'];
            $user->save();
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
    }

    public function GetNextPageList($offset){
        try{
            $list=Ling::orderBy('id','asc')->offset($offset)->limit(10)->get();
            return response()->json(['NextList'=>$list]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }

    public function Search($number){
        try{
            $liang=Ling::where('liang','like','%'.$number.'%')->get();
            return response()->json(['liang'=>$liang]);
        }catch (\Exception $e){
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
}
