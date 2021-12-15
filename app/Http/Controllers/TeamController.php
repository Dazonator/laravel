<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function getDepartments(){
        return Departments::all();
    }

    public function getDepartmentUsers($id){
        return User::where('department_id', $id)->with(['department'])->get();
    }

    public function getUserTasks($user_id){
        $user = User::where('id', $user_id)->first();
        $status = Status::with(['statusTasks' => function($query) use ($user_id){
            $query->with('initiator', 'status')->whereHas('responsibles', function($q) use ($user_id){
                $q->where('id', $user_id);
            });
        }])->get();

        return [
            'user' => $user,
            'status' => $status,
        ];
    }
}
