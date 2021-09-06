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

        $task = new Tasks();
        $performers = $request->performers_id;

        print_r($performers);

        $task::create([
            'title' => $request->title,
            'text' => $request->text,
            'performers_id' => $request->performers_id,
            'initiator_id' => $request->initiator_id,
            'priority_id' => $request->priority_id,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,
        ])->responsibles()->sync($performers);

        return view('tasks');
    }

    public function userTasks()
    {

        return Auth::user()->tasks()->with([
            'priority',
            'status',
            'initiator' => function($q) {
                $q->select('id', 'img', 'name', 'lastname');
            },
            'responsibles' => function($q) {
                $q->select('id', 'img', 'name', 'lastname');
            },
        ])->get();

    }

    public function getTask($id){
        return Tasks::where('id', $id)->with(['responsibles', 'priority', 'status', 'initiator'])->first();
    }


}
