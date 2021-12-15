<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function createNotification(Request $request){
        $notification = new Notifications();
        $notification::create([
            'title' => $request->title,
            'text' => $request->text,
        ]);
    }

    public function updateNotification(Request $request){

        $id = $request->id;
        $notification = Notifications::find($id);

        $notification->update([
            'title' => $request->title,
            'text' => $request->text,
        ]);
    }

    public function deleteNotification($id){
        $notification = Notifications::find($id);
        $notification->delete();
    }

    public function getAllNotifications(){
        return Notifications::latest()->get();
    }
    public function getById($id){
        return Notifications::where('id', $id)->first();
    }

}
