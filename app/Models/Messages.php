<?php

namespace App\Models;

use App\Events\CreatedMessageTaskEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Messages extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'task_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function messagesFor()
    {
        return $this->belongsToMany(User::class);
    }


    protected static function booted()
    {
        static::created(function ($message) {
            event(new CreatedMessageTaskEvent($message, Auth::user()->toArray()));
        });
    }
}
