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
    public function submitTask(AddTaskRequest $request){

        $task = new Tasks();
        $performers = $request->performers_id;
        $task::create([
            'title' => $request->title,
            'text' => $request->text,
            'performers_id' => $request->performers_id,
            'initiator_id' => $request->initiator_id,
            'priority_id' => $request->priority_id,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,
            'parent_id' => $request->parent_id,
        ])->responsibles()->sync($performers);
    }
    public function updateTask(AddTaskRequest $request){
        $id = $request->id;
        $performers = $request->performers_id;
        $task = Tasks::find($id);
        $task->update([
            'title' => $request->title,
            'text' => $request->text,
            'performers_id' => $request->performers_id,
            'initiator_id' => $request->initiator_id,
            'priority_id' => $request->priority_id,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,
            'parent_id' => $request->parent_id,
        ]);
        $task->responsibles()->sync($performers);
    }

    public function completedTask(Request $request){
//        dd($request->id);
        $id = $request->id;
        $task = Tasks::find($id);
        $task->update([
            'status_id' => '3',
        ]);
        $task2 = Tasks::find($id);
        dd($task2);
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

    public function editTask($id){
        return Tasks::where('id', $id)->first();
    }

    public function getTask($id){
        return Tasks::where('id', $id)->with(['responsibles', 'priority', 'status', 'initiator', 'parent', 'children'])->first();
    }


}
