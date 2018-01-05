<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Message.index',['List'=>Message::all()]);
    }


    public function getData(Request $request){
        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) : 10;
        $msg_arr = CommClass::PagingData($page,$rows,"xx_sys_message" );
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
            $msg = null;
        }else{
            $msg = Message::find($id);
        }
        return view('Message.create',['msg'=>$msg]);
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
            if(empty($data['msgid'])){
                $message = new Message();
            }else{
                $message = Message::find($data['msgid']);
            }
            $message->mtype = isset($data['mtype'])?$data['mtype']:0;
            $message->mcontent = isset($data['mcontent'])?$data['mcontent']:"";
            $message->create_date = date("Y-m-d H:i:s");
            $message->save();

            //保存成功，后发送游戏服务器
            //大厅公告
            if($message->mtype==1)
                CommClass::UpGameSer(1,'msg',$message->mcontent);
            //紧急通知
            if($message->mtype==3)
                CommClass::UpGameSer(1,'urgent',$message->mcontent);

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
        return view('Message.edit',['message'=>Message::find($id)]);
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
        try {
            $meg = Message::find($id);
            $meg->mtype=$request['mtype'];
            $meg->mgametype=$request['mgametype'];
            $meg->mcontent=$request['mcontent'];
            $meg->save();
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
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
            Message::destroy($ids);
            return response()->json(['msg'=>1]);
        }catch (\Exception $e) {
            return response()->json(['msg'=>$e->getMessage()]);
        }
    }
}
