<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use SoftDeletes;

    protected $fillable = [
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
        'distribution_department',
        'creator_id',
    ];

    protected $casts   = [
        'performers_id' => 'json'
    ];

    protected $dates = ['deleted_at'];


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

    /* Получение задач с уведомлениями */
    public function taskMessages(){
        return $this->hasMany(Messages::class, 'task_id', 'id');
    }
}
