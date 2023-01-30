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
        $step = Steps::create([
            'task_id' => $request->task_id,
            'title' => $request->title,
        ])->responsibles()->sync([Auth::user()->id]);
    }

    public function deleteStep(Request $request){
        $id = $request->id;
        Steps::find($id)->delete();
    }

    public function updateStepTitle(Request $request){
        $id = $request->id;
        $step = Steps::find($id);

        $step->update([
            'title' => $request->title,
        ]);
    }
     public function updateTaskStep(Request $request){
        $task = Tasks::find($request->id);
        $task->update([
             'in_step' => $request->in_step,
         ]);
     }
}
