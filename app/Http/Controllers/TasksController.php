<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTaskRequest;
use App\Models\Departments;
use App\Models\Messages;
use App\Models\MessagesUser;
use App\Models\Status;
use App\Models\Steps;
use App\Models\Structure;
use App\Models\Tasks;
use App\Models\TasksFiles;
use App\Models\TasksHistory;
use App\Models\TasksTests;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TasksController extends Controller
{
    public function submitTask(Request $request){
        $user = Auth::user()->id;
        $performers = $request->performers_id;

        $isDistributed = false;
        if ($request->meeting_id) {
            $isDistributed = true;
        }

        $task = Tasks::create([
            'title' => $request->title,
            'text' => $request->text,
            'performers_id' => $request->performers_id,
            'initiator_id' => $user,
            'priority_id' => $request->priority_id,
            'deadline' => $request->deadline,
            'startdate' => $request->startdate,
            'parent_id' => $request->parent_id,
            'in_step' => $request->in_step,
            'structure_id' => $request->structure_id,
            'meeting_id' => $request->meeting_id,
            'is_distributed' => $isDistributed,
            'creator_id' => $user,
        ]);
        $task->responsibles()->sync($performers);

        if($request->post('files')){
            if(count($request->post('files')) > 0){
                foreach ($request->post('files') as $key => $file) {
                    TasksFiles::create([
                        'name' => $file['name'],
                        'path' => $file['path'],
                        'attached' => true,
                        'task_id' => $task->id,
                    ]);
                }
            }
        }

    }

    public function submitTaskDepartment(Request $request){
        $user = Auth::user();
        if($user->hasPermission('create-tasks-for-department')) {
            Tasks::create([
                'title' => $request->title,
                'text' => $request->text,
                'initiator_id' => $user->id,
                'initial_department' => $user->department_id,
                'is_distributed' => false,
                'creator_id' => $user->id,
            ]);
        }
        if($request->department_id && $user->hasPermission('create-tasks-for-all-department')) {
            Tasks::create([
                'title' => $request->title,
                'text' => $request->text,
                'initiator_id' => $user->id,
                'initial_department' => $request->department_id,
                'is_distributed' => false,
                'creator_id' => $user->id,
            ]);
        }
    }

    public function updateTask(Request $request){
        $id = $request->id;
        $performers = $request->performers_id;

        $isDistributed = false;
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
            'distribution_department' => $request->distribution_department,
            'structure_id' => $request->structure_id,
            'is_distributed' => $isDistributed,
        ]);
        $task->responsibles()->sync($performers);

        if(count($request->post('files')) > 0){
            foreach ($request->post('files') as $key => $file) {
                TasksFiles::create([
                    'name' => $file['name'],
                    'path' => $file['path'],
                    'attached' => true,
                    'task_id' => $task->id,
                ]);
            }
        }
    }
    public function distributionDepartment(Request $request){
        $id = $request->id;
        $task = Tasks::find($id);
        $task->update([
            'distribution_department' => $request->distribution_department,
        ]);
    }

    public function completedTask($id){
        $task = Tasks::where('id', $id)->first();
        $task->update([
            'status_id' => '3',
        ]);
    }
    public function startTask($id){
        $task = Tasks::find($id);
        $task->update([
            'status_id' => '2',
        ]);
    }
    public function pauseTask($id){
        $task = Tasks::find($id);
        $task->update([
            'status_id' => '1',
        ]);
    }

    public function deleteTask($id){
        Tasks::where('id', $id)->first()->delete();
    }

    public function uploadFiles(Request $request){
        if ($request->items && count($request->items) > 0){
            $files = $request->items;
            $results = [];
            foreach ($files as $key => $file) {
                $path = $file->store('test-files');
                array_push($results,  [
                    'name' => $file->getClientOriginalName(),
                    'path' => Storage::url($path)
                ]);
            }
            return $results;
        }
    }
    public function deleteFile($id){
        TasksFiles::where('id', $id)->first()->delete();
    }

    public function getStatusesAndDepartments(){
        return [
            'statuses' => Status::all(),
            'department' => Departments::where('id', Auth::user()->department_id)->first(),
        ];
    }


    public function tasksByDepartment($id){
        return Tasks::where('initial_department', $id)->with('initiator')->get();
    }


    public function statusTasks($id){
        return Tasks::where('status_id', $id) -> with(['children', 'parent', 'initiator', 'responsibles', 'priority', 'status']) -> where(function ($query){
            $query->whereHas('responsibles', function ($q){
                $q->where('id', Auth::user()->id);
            });
        })->get();
    }

    public function tasksInitiator(){
        return Status::with(['statusTasks' => function($query){
            $query->with('children', 'parent', 'initiator', 'status', 'priority', 'responsibles')->where('initiator_id', Auth::user()->id);
        }])->get();
    }

    public function tasksTests(){
        return Tasks::with(['initiator', 'responsibles', 'priority', 'status', 'tests'])->whereHas('tests', function ($q){
            $q->where('user_id', Auth::user()->id)->whereNull('is_tested');
        })->get();
    }

    public function completeTest($id){
        $test = TasksTests::where('task_id', $id)->first();
        $test->update([
            'is_tested' => true,
            'tested_time' => Carbon::now()
        ]);
    }
    public function sendForTest(Request $request){
        $test = TasksTests::create([
            'user_id' => $request->user_id,
            'task_id' => $request->task_id,
        ]);
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
        $tasks = Tasks::where('id', $id)->with([
            'responsibles',
            'priority',
            'status',
            'initiator',
            'parent' => function($q){
                $q->with(['status', 'priority']);
            },
            'files',
            'children' => function ($q){
                $q->where('in_step', null)->with(['status', 'priority', 'responsibles', 'initiator']);
            },
            'steps' => function ($q){
                $q->with(['tasks' => function($q){
                    $q->with(['status', 'priority', 'responsibles', 'initiator']);
                }]);
            },
            'structure',
            'tests' => function ($q){
                $q->with('user');
            },
        ])->first();

        $users = User::all();

        return [
            'task' => $tasks,
            'users' => $users
        ];
    }

}
