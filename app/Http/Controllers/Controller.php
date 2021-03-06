<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\MessagesUser;
use App\Models\Priority;
use App\Models\Status;
use App\Models\User;
//use http\Env\Request;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function editorUpload(Request $request){
        $file = $request->upload;
        $path   = 'images/users';

        return [
            'result' => 'success',
            'url' => '/storage/'.Storage::disk('public')->putFile($path, $file),
            'message' => 'Сообщение'
        ];
    }

    public function mainAppParameters()
    {
        if(Auth::check()){
            $user = User::where('id', Auth::user()->id)->
            with([
                'department',
                'permissions'
            ])->first();


            return [
                'authUser' => $user,
//            'statuses' => Status::all(),
//            'departments' => Departments::all(),
//            'priorities' => Priority::all(),
//            'users' => User::with(['department'])->get(),
                'notifications' => MessagesUser::where('user_id', Auth::user()->id)->count(),
                'permissions'=> app(UserController::class)->getPermissions(),
            ];

        }
    }

}
