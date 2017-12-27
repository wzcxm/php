<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\SendCards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendCardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('SendCards.Index',['person'=>CommClass::GetPerosn()]);
    }

    public function GetPerson(){
        try{
            return response()->json(['person'=>CommClass::GetPerosn()]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
    public function  GetUserInfo($uid){
        try{
            $user = DB::table('view_user')->where('uid',$uid)->select('uid','head_img_url','rname')->first();
            return response()->json(['user'=>$user]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
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
        $list = DB::table('view_sendcards')->get();
        $sum = DB::table('view_sendcards')->sum('sumgold');
        return view('SendCards.SendList',['SumCards'=>$sum,'List'=>$list]);
    }

    public function Send($type,$number,$uid='0'){
        if(!empty($type)){
            $csellid = session('uid');
            //$table = 'pp_agentsys_cardstrade_'.date('Ym');
            $person=0;
            switch ($type){
                case 1://个人
                    $arr = ['cbuyid'=>$uid,'csellid'=>$csellid,'cnumber'=>$number,'ctype'=>3];
                    CommClass::InsertCard($arr);
                    $person=1;
                    break;
                case 2://个人及下属
                    $sql = "select uid from xx_user where find_in_set(uid,queryChildrenAreaInfo(:uid))";
                    $uids=DB::select($sql,['uid'=>$uid]);
                    $values=[];
                    foreach ($uids as $id)
                    {
                        Array_push($values,['cbuyid'=>$id->uid,'csellid'=>$csellid,'cnumber'=>$number,'ctype'=>3]);
                    }
                    CommClass::InsertCard($values);
                    $person=sizeof($values);
                    break;
                case 3://全服赠送
                    $uids=DB::select('select uid from xx_user');
                    $values=[];
                    foreach ($uids as $id)
                    {
                        Array_push($values,['cbuyid'=>$id->uid,'csellid'=>$csellid,'cnumber'=>$number,'ctype'=>3]);
                    }
                    CommClass::InsertCard($values);
                    $person=sizeof($values);
                    break;
                default:
                    break;
            }
            SendCards::insert(['uid'=>$uid,'stype'=>$type,'speople'=>$person,'sbubble'=>$number]);
            return response()->json(['msg'=>1]);
        }
        else{
            return response()->json(['msg'=>2]);
        }
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
