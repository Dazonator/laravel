<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksTests extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'is_tested',
        'tested_time',
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
