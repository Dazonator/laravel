<?php

namespace App\Models;

use App\Events\MeetingsEvent;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetings extends Model
{
    protected $fillable = [
        'number',
        'initiator_id',
        'department_id',
        'color',
        'additional_staff',
        'start',
        'end',
        'user_id',
        'completed_at',
    ];
    protected $casts   = [
        'additional_staff' => 'json'
    ];

    public function department()
    {
        return $this->hasOne(Departments::class, 'id', 'department_id');
    }

    public function initiator()
    {
        return $this->hasOne(User::class, 'id', 'initiator_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    protected static function booted()
    {
        static::created(function ($meeting) {
//            event(new TaskUpdatedEvent($task->toArray(), Auth::user()->toArray()));
            event(new MeetingsEvent('create-meeting', $meeting->toArray(), Auth::user()->toArray(), $meeting->getChanges()));
            //
        });
        static::updated(function ($meeting) {
            event(new MeetingsEvent('update-meeting', $meeting->toArray(), Auth::user()->toArray(), $meeting->getChanges()));
        });
        static::deleted(function ($user) {
            //
        });
    }
}
