<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'message_for',
        'task_id',
    ];

    protected $casts   = [
        'message_for' => 'json'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

//    public function tasksNotifications(){
//        return
//    }
}
