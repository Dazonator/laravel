<?php

namespace App\Http\Controllers;


use App\Http\Requests\AddUserRequest;
use App\Models\Priority;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function Sodium\add;

class UserController extends Controller
{

    public function addUser(AddUserRequest $request){

//        $imageName = $request->name.'.'.$request->lastname.'.'.$request->image->extension();
//        $request->img->move(public_path('img/users/'), $imageName);
//        $finImgPath = 'img/users'.$imageName;
//        print_r($request);
        $user = new User();
        $user::create([
            'login' => $request->login,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'img' => $request->img,
        ]);
    }

    public function uploadPhoto (Request $request){
        $file = $request->img;
//        print_r($file);
//        exit();
        $path   = 'images/users';
        return Storage::disk('public')->putFile($path, $file);
    }

    public function index()
    {
        $user = User::where('id', Auth::user()->id)->with(['department'])->first();
//        return $user;
//        return Auth::user();
        return $user;
    }


    public function employees()
    {
        return User::select('id', 'img', 'name', 'lastname', 'position')->get();
    }


    public function priorities()
    {
        return Priority::all();

    }


//    public function chengePassword(Request $request){
//
//    }



}
