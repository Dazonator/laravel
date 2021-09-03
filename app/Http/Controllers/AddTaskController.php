<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;
use App\Models\AddTask;

class AddTaskController extends Controller
{
    public function submit(AddTaskRequest $request){

        DB::table('add_tasks')->create([
            'title' => $request->title,
            'text' => $request->text,
            'performers_id' => $request->performers_id,
            'initiator_id' => $request->initiator_id,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,

        ]);

        return view('tasks');
    }

    public function index()
    {
        return AddTask::all();
    }

}
