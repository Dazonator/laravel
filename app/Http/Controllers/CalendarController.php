<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEventRequest;
use App\Models\Events;
use App\Models\Meetings;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{


    public function submitEvent(AddEventRequest $request){
        $user = Auth::user();
        $event = new Events();
        $event::create([
            'title' => $request->title,
            'text' => $request->text,
            'color' => $request->color,
            'start' => $request->start,
            'end' => $request->end,
            'user_id' => $user->id,
        ]);
    }

    public function updateEvent(AddEventRequest $request){
        $id = $request->id;
        $event = Events::find($id);
        $event->update([
            'title' => $request->title,
            'text' => $request->text,
            'start' => $request->start,
            'end' => $request->end,
        ]);
    }

    public function deleteEvent($id){
        Events::where('id', $id)->delete();
    }

    public function getEvents(Request $request){
        $request->start = Carbon::parse($request->start)->startOfDay();
        $request->end = Carbon::parse($request->end)->endOfDay();
        $events = Events::
            where('user_id', Auth::user()->id)->
            where(function ($q) use ($request) {
                $q->where([
                    ['start', '>=', $request->start],
                    ['start', '<=', $request->end],
                ])
                ->orWhere([
                    ['end', '>=', $request->start],
                    ['end', '<=', $request->end],
                ])
                ->orWhere([
                    ['start', '<=', $request->start],
                    ['end', '>=', $request->end],
                ])
                ->orWhere([
                    ['start', '<=', $request->start],
                    ['end', '>=', $request->end],
                ]);
            })
            ->get();
        foreach ($events as $event){
            $event->start = Carbon::parse($event->start)->format('Y-m-d H:i');
            $event->end = Carbon::parse($event->end)->format('Y-m-d H:i');
        }


        $meetings = Meetings::with(['department', 'users'])->where(function ($query) use ($request) {
            $query->where([
                ['start', '>=', $request->start],
                ['start', '<=', $request->end],
            ])->orWhere([
                ['end', '>=', $request->start],
                ['end', '<=', $request->end],
            ])->orWhere([
                ['start', '<=', $request->start],
                ['end', '>=', $request->end],
            ])->orWhere([
                ['start', '<=', $request->start],
                ['end', '>=', $request->end],
            ]);
        })->where(function ($query){
            $query->where('department_id', Auth::user()->department_id)->orWhereHas('users', function ($q){
                $q->where('user_id', Auth::user()->id);
            })->orWhere('initiator_id', Auth::user()->id);
        })->get();
        foreach ($meetings as $meeting){
            $meeting->start = Carbon::parse($meeting->start)->format('Y-m-d H:i');
            $meeting->end = Carbon::parse($meeting->end)->format('Y-m-d H:i');
        }

//        $tasks = Tasks::where('status_id', 2)->
//        where(function ($query) use ($request) {
//            $query->where([
//                ['startdate', '>=', $request->start],
//                ['startdate', '<=', $request->end],
//            ])->orWhere([
//                ['deadline', '>=', $request->start],
//                ['deadline', '<=', $request->end],
//            ])->orWhere([
//                ['startdate', '<=', $request->start],
//                ['deadline', '>=', $request->end],
//            ])->orWhere([
//                ['startdate', '<=', $request->start],
//                ['deadline', '>=', $request->end],
//            ]);
//        })->get();

        $tasks = Tasks::where('status_id', 2)->get();
        foreach ($tasks as $task){
            if(!empty($task->startdate)){
                $task->start = Carbon::parse($task->startdate)->format('Y-m-d');
            } else {
                $task->start = Carbon::parse($task->created_at)->format('Y-m-d');
            }

            if(!empty($task->deadline)){
                $task->end = Carbon::parse($task->deadline)->format('Y-m-d');
            } else {
                $task->end = Carbon::parse($request->end)->format('Y-m-d');
            }
//            $task->start = Carbon::parse($task->start)->format('Y-m-d');
//            $task->end = Carbon::parse($task->end)->format('Y-m-d');
//            $task->created_at = Carbon::parse($task->end)->format('Y-m-d');
        }


        return [
            'meetings' => $meetings,
            'events' => $events,
            'tasks' => $tasks
        ];
//        return $meetings;
//        return $tasks;
    }

    public function getById($id){
        return Events::where('id', $id)->first();
    }
}
