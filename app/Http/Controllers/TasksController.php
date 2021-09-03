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
            'performers_id' => $request->performers_id,
            'initiator_id' => $request->initiator_id,
            'priority_id' => $request->priority_id,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,
        ]);

        return view('tasks');
    }

    public function userTasks()
    {
        return Tasks::whereJsonContains('performers_id', Auth::user()->id)->with(['priority', 'status', 'initiator', 'performers'])->get();
    }


}
