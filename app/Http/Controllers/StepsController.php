<?php

namespace App\Http\Controllers;

use App\Models\Steps;
use App\Models\Tasks;
use App\Models\TasksHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepsController extends Controller
{

    public function getParentSteps($id){
        return Steps::where('task_id', $id)->get();
    }

    public function createNewStep(Request $request){
        $step = new Steps();
        $step::create([
            'task_id' => $request->task_id,
            'title' => $request->title,
        ]);
    }

    public function deleteStep($id){
        Tasks::where('id', $id)->first()->delete();
    }

    public function updateStep($id){
        Tasks::where('id', $id)->first()->delete();

    }
}
