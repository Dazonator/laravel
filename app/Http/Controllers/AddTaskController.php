<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use App\Models\Departments;
use App\Models\Priority;
use App\Models\Steps;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;
use App\Models\AddTask;

class AddTaskController extends Controller
{
    public function getParamsAddTask($id = null){
//        $task = Tasks::where('id', $id)->first();
        return [
            'employees' => User::select('id', 'img', 'name', 'lastname', 'position')->get(),
            'priorities' => Priority::all(),
            'departments' => Departments::all(),
        ];
    }

}
