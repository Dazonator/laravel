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

        DB::table('add_tasks')->insert([
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

    public function index(): array
    {
//        $tasks = AddTask::all();
//        return $tasks;
////        return view('home', $tasks);
        return view('tasks');
    }

}
