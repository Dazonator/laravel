<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\MessagesUser;
use App\Models\Priority;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function mainAppParameters()
    {
        return [
            'authUser' => User::where('id', Auth::user()->id)->with(['department'])->first(),
//            'statuses' => Status::all(),
//            'departments' => Departments::all(),
//            'priorities' => Priority::all(),
//            'users' => User::with(['department'])->get(),
            'notifications' => MessagesUser::where('user_id', Auth::user()->id)->count()
        ];
    }
}
