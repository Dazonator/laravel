<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    public function submit(AddTaskRequest $request){
        Tasks::create([
            'title' => $request->title,
            'text' => $request->text,
            'performers' => $request->performers,
            'initiator' => $request->initiator,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,
        ]);

        return view('tasks');
    }

    public function userTasks()
    {
        return Tasks::whereJsonContains('performers', Auth::user()->id)->get();
    }


}
