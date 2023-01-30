<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Steps extends Model
{
    protected $fillable = [
        'task_id',
        'title',
    ];


    public static function boot() {
        parent::boot();
        self::created(function ($model) {
            TasksHistory::create([
                'task_id' => $model->id,
                'user_id' => Auth::user()->id,
                'action' => 'created-step',
            ]);
        });
        self::deleted(function ($model) {
            TasksHistory::create([
                'task_id' => $model->id,
                'user_id' => Auth::user()->id,
                'action' => 'deleted-step',
            ]);

        });
        self::updated(function ($model) {
            TasksHistory::create([
                'task_id' => $model->id,
                'user_id' => Auth::user()->id,
                'action' => 'updated-step',
            ]);
        });
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'in_step', 'id');
    }

    public function responsibles()
    {
        return $this->belongsToMany(User::class);
    }
}
