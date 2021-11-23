<?php

namespace App\Http\Controllers;

use App\Models\Meetings;
use App\Models\Tasks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingsController extends Controller
{
    public function submitMeeting(Request $request){
        $user = Auth::user();
//        $meeting = new Meetings();

        Meetings::create([
            'number' => $request->number,
            'initiator_id' => $user->id,
            'department_id' => $request->department_id,
            'additional_staff' => $request->additional_staff,
            'start' => $request->start,
            'end' => $request->end,
        ])->users()->sync($request->additional_staff);
    }

    public function updateMeeting(Request $request){
        $id = $request->id;
        $meeting = Meetings::find($id);
        $meeting->update([
            'numder' => $request->numder,
            'department_id' => $request->department_id,
            'additional_staff' => $request->additional_staff,
            'start' => $request->start,
            'end' => $request->end,
        ]);
        $meeting->users()->sync($request->additional_staff);
    }

    public function deleteMeeting($id){
        Meetings::where('id', $id)->delete();
    }

    public function completedMeeting($id){
        $meeting = Meetings::find($id);
        $meeting->update([
            'completed_at' => Carbon::now(),
        ]);
    }

    public function getMeetings(){
        return Meetings::with('department', 'initiator')->get();
    }

    public function getMeetingsCalendar(Request $request){
//        $meetings = Meetings::
//            where([
//                ['start', '>=', $request->start],
//                ['start', '<=', $request->end],
//            ])
//            ->orWhere([
//                ['end', '>=', $request->start],
//                ['end', '<=', $request->end],
//            ])
//            ->orWhere([
//                ['start', '<=', $request->start],
//                ['end', '>=', $request->end],
//            ])
//            ->orWhere([
//                ['start', '<=', $request->start],
//                ['end', '>=', $request->end],
//            ])
//            ->with('department')->get();
//        foreach ($meetings as $meeting){
//            $meeting->start = Carbon::parse($meeting->start)->format('Y-m-d H:i');
//            $meeting->end = Carbon::parse($meeting->end)->format('Y-m-d H:i');
//        }
//        return $meetings;

        $request->start = Carbon::parse($request->start)->startOfDay();
        $request->end = Carbon::parse($request->end)->endOfDay();
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
            });
        })->get();
        foreach ($meetings as $meeting){
            $meeting->start = Carbon::parse($meeting->start)->format('Y-m-d H:i');
            $meeting->end = Carbon::parse($meeting->end)->format('Y-m-d H:i');
        }
        return $meetings;
    }

    public function getById($id){
        $meeting = Meetings::where('id', $id)->with('department')->first();
        $tasksInitial = Tasks::where('initial_department', $meeting->department_id)->where('distribution_department', null)->where('is_distributed', false)->with('initiator')->get();
        $tasksDistribution = Tasks::where('distribution_department', $meeting->department_id)->where('is_distributed', false)->with('initiator')->get();
        $distributionTasksTrue = Tasks::where('meeting_id', $id)->where('is_distributed', true)-> get();
        return [
            'meeting' => $meeting,
            'tasksInitial' => $tasksInitial,
            'tasksDistribution' => $tasksDistribution,
            'distributionTasksTrue' => $distributionTasksTrue,
        ];
    }

    public function getByIdForUpdate($id){
        $meeting = Meetings::find($id);
        $meeting->start = Carbon::parse($meeting->start)->format('Y-m-d H:i');
        $meeting->end = Carbon::parse($meeting->end)->format('Y-m-d H:i');
        return $meeting;
    }

    public function getMaxNumber($id)
    {
        if($id){
            return Meetings::where('department_id', $id)->max('number');
        } else {
            return Meetings::whereNull('department_id')->max('number');
        }

    }
}
