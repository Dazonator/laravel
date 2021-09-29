<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{

    public function getEvents(Request $request){
        $request->start = Carbon::parse($request->start)->startOfDay();
        $request->end = Carbon::parse($request->end)->endOfDay();
        $tasks = Events::
            where([
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
            ])
            ->get();
        foreach ($tasks as $task){
            $task->start = Carbon::parse($task->start)->format('Y-m-d H:i');
            $task->end = Carbon::parse($task->end)->format('Y-m-d H:i');
        }

        return $tasks;

    }
}
