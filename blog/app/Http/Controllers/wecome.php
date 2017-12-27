<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class wecome extends Controller
{
    //

    public function index(){
        return view('welcome');
    }
}
