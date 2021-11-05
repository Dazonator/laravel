<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use App\Models\Departments;
use App\Models\Messages;
use App\Models\MessagesUser;
use App\Models\Status;
use App\Models\Steps;
use App\Models\Tasks;
use App\Models\TasksHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    public function submitTask(AddTaskRequest $request){
        $user = Auth::user()->id;
        $performers = $request->performers_id;
        Tasks::create([
            'title' => $request->title,
            'text' => $request->text,
            'performers_id' => $request->performers_id,
            'initiator_id' => $user,
            'priority_id' => $request->priority_id,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,
            'parent_id' => $request->parent_id,
            'in_step' => $request->in_step,
            'creator_id' => $user,
        ])->responsibles()->sync($performers);
    }

    public function submitTaskDepartment(Request $request){
        $user = Auth::user()->id;
        Tasks::create([
            'title' => $request->title,
            'text' => $request->text,
            'initiator_id' => $user,
            'initial_department' => $request->department_id,
            'is_distributed' => false,
            'creator_id' => $user,
        ]);
    }

    public function updateTask(AddTaskRequest $request){
        $id = $request->id;
        $performers = $request->performers_id;

        $isDistributed = null;
        if ($request->meeting_id) {
            $isDistributed = true;
        }
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
            'in_step' => $request->in_step,
            'meeting_id' => $request->meeting_id,
            'is_distributed' => $isDistributed,
        ]);
        $task->responsibles()->sync($performers);
    }

    public function completedTask(Request $request){
        $id = $request->id;
        $task = Tasks::where('id', $id)->first();
        $task->update([
            'status_id' => '3',
        ]);
    }
    public function restoreTask(Request $request){
        $id = $request->id;
        $task = Tasks::where('id', $id)->first();
        $task->update([
            'status_id' => '1',
        ]);
    }

    public function deleteTask($id){
        Tasks::where('id', $id)->first()->delete();
    }

//    public function userTasks()
//    {
//        $user = Auth::user()->id;
//        $status = Status::with(['statusTasks' => function($query) use ($user){
//            $query->whereHas('responsibles', function($q) use ($user){
//                $q->where('id', $user);
//            });
//        }])->get();
//
//        return $status;
//    }

    public function getStatusesAndDepartments(){
//        return Status::all();
        return [
            'statuses' => Status::all(),
            'departments' => Departments::all()
        ];
    }


    public function tasksByDepartment($id){
//        return Tasks::with(['responsibles'])->whereHas('responsibles', function($q) use ($id){
//            $q->where('department_id', $id);
//        })->get();
        return Tasks::where('distribution_department', $id)->get();
    }


    public function statusTasks($id){
        return Tasks::where('status_id', $id) -> where(function ($query){
            $query->where('initiator_id', Auth::user()->id)->orWhereHas('responsibles', function ($q){
                $q->where('id', Auth::user()->id);
            });
        })->get();
    }

    public function editTask($id){
        return Tasks::where('id', $id)->with([
            'steps',
            'parent' => function ($q){
                $q->with('steps');
            }
        ])->first();
    }

    public function getTask($id){
        return Tasks::where('id', $id)->with([
            'responsibles',
            'priority',
            'status',
            'initiator',
            'parent',
            'children' => function ($q){
                $q->where('in_step', null);
            },
            'steps' => function ($q){
                $q->with('tasks');
            }
        ])->first();
    }

}
