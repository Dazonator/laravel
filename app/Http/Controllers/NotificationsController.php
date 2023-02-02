<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Models\NotificationsUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function createNotification(Request $request){
        //Пользователи для уведомления
        $users = User::where('id', '<>' , Auth::user()->id)->get();
        $users = $users->toArray();
        $forUsers = [];
        foreach ($users as $user){
            $forUsers[] = $user['id'];
        }

        $notification = new Notifications();
        $notification::create([
            'title' => $request->title,
            'text' => $request->text,
        ])->notificationsFor()->sync($forUsers);

        //Событие для уведомления
        event(new \App\Events\Notifications($forUsers));
    }

    public function updateNotification(Request $request){

        $id = $request->id;
        $notification = Notifications::find($id);

        $notification->update([
            'title' => $request->title,
            'text' => $request->text,
        ]);

        event(new \App\Events\Notifications());
    }

    public function deleteNotification($id){
        $notification = Notifications::find($id);
        $notification->delete();
        event(new \App\Events\Notifications());
    }

    public function getAllNotifications(){
        NotificationsUser::where('user_id', Auth::user()->id)->delete();
        return Notifications::latest()->get();
    }

    public function getById($id){
        return Notifications::where('id', $id)->first();
    }

    public function getNotificationsCount() {
        return NotificationsUser::where('user_id', Auth::user()->id)->count();
    }
}
