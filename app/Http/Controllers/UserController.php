<?php

namespace App\Http\Controllers;


use App\Models\Priority;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Sodium\add;


class UserController extends Controller
{

    public function index()
    {
        $user = User::where('id', Auth::user()->id)->with(['position', 'department'])->first();
//        return $user;
//        return Auth::user();
        return $user;
    }


    public function employees()
    {
        return User::select('id', 'img', 'name', 'lastname', 'position_id')->with(['position'])->get();

    }


    public function priorities()
    {
        return Priority::all();

    }


//    public function chengePassword(Request $request){
//
//    }



}
