<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Tasks extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'text',
        'performers_id',
        'initiator_id',
        'priority_id',
        'deadline',
        'startdate',
        'status_id',
        'created_at',
        'update_at',
        'parent_id',
        'in_step',
        'initial_department',
        'distribution_department',
        'is_distributed',
        'meeting_id',
        'creator_id',
    ];

    protected $casts   = [
        'performers_id' => 'json'
    ];

    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();
        self::created(function ($model) {
            TasksHistory::create([
                'task_id' => $model->id,
                'user_id' => Auth::user()->id,
                'action' => 'created',
            ]);
        });
        self::deleted(function ($model) {
            TasksHistory::create([
                'task_id' => $model->id,
                'user_id' => Auth::user()->id,
                'action' => 'deleted',
            ]);

        });
        self::updated(function ($model) {
            TasksHistory::create([
                'task_id' => $model->id,
                'user_id' => Auth::user()->id,
                'action' => 'updated',
            ]);
        });
    }

    /* Связь для получения приоритетов */
    public function priority()
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    /* Связь для получения статусов */
    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }


    /* Связь для получения данных инициатора */
    public function initiator()
    {
        return $this->hasOne(User::class, 'id', 'initiator_id');
    }


    /* Связь для получения ответственных по задаче */
    public function responsibles()
    {
        return $this->belongsToMany(User::class);
    }


    /* Связь для получения родительской задачи */
    public function parent()
    {
        return $this->belongsTo(Tasks::class, 'parent_id');
    }

    /* Связь для получения подзадач задачи */
    public function children()
    {
        return $this->hasMany(Tasks::class, 'parent_id');
    }

    public function steps()
    {
        return $this->hasMany(Steps::class, 'task_id');
    }

//    /* Связь для получения сообщений задач */
//    public function taskMessages(){
//        return $this->hasMany(Messages::class, 'task_id', 'id');
//    }

    /* Связь для доступа к связующей таблице через сообщения */
    public function taskNotifications(){
        return $this->hasManyThrough(MessagesUser::class, Messages::class, 'task_id', 'messages_id');
    }


}
