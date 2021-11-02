<?php

namespace App\Http\Controllers;

use App\Models\Meetings;
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
        ]);
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
    }

    public function deleteMeeting($id){
        Meetings::where('id', $id)->delete();
    }

    public function getMeetings(Request $request){
        $request->start = Carbon::parse($request->start)->startOfDay();
        $request->end = Carbon::parse($request->end)->endOfDay();
        $meetings = Meetings::
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
            ->with('department')->get();
        foreach ($meetings as $meeting){
            $meeting->start = Carbon::parse($meeting->start)->format('Y-m-d H:i');
            $meeting->end = Carbon::parse($meeting->end)->format('Y-m-d H:i');
        }
        return $meetings;
    }

    public function getById($id){
        return Meetings::where('id', $id)->first();
    }

    public function getMaxNumber()
    {
        $maxNumber = Meetings::max('number');
        if(!$maxNumber){
            return 0;
        }
        return $maxNumber;
    }
}
