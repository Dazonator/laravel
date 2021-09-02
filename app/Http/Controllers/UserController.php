<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Sodium\add;


class UserController extends Controller
{

    public function index()
    {
        $user = User::where('id', Auth::user()->id)->with(['position', 'department'])->first();
//        return Auth::user();
//    print_r($user->position);
        return $user;
//        return User::where('id', 1)->first();
    }


    public function employees()
    {
        return User::with(['position', 'department'])->get();

    }


//    public function chengePassword(Request $request){
//
//    }



}
