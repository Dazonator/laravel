<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEventRequest;
use App\Models\Events;
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
        $tasks = Events::
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
        foreach ($tasks as $task){
            $task->start = Carbon::parse($task->start)->format('Y-m-d H:i');
            $task->end = Carbon::parse($task->end)->format('Y-m-d H:i');
        }
        return $tasks;
    }

    public function getById($id){
        return Events::where('id', $id)->first();
    }
}
