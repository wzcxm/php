<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateIdController extends Controller
{
    //
    public function update(Request $request){
        try{
            $data = isset($request['data'])?$request['data']:"";
            $old_uid = isset($data['old_uid'])?$data['old_uid']:0;
            $new_uid = isset($data['new_uid'])?$data['new_uid']:0;
            $old_teaid = isset($data['old_teaid'])?$data['old_teaid']:0;
            $new_teaid = isset($data['new_teaid'])?$data['new_teaid']:0;
            if(!empty($old_uid) && !empty($new_uid)){
                DB::select('CALL update_uid('.$old_uid.','.$new_uid .')');
            }
            if(!empty($old_teaid) && !empty($new_teaid)){
                DB::select('CALL update_teaid('.$old_teaid.','.$new_teaid .')');
            }
            return response()->json(['Error'=>'']);
        }catch (\Exception $e){
            return response()->json(['Error'=>$e->getMessage()]);
        }
    }
}