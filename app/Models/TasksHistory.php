<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksHistory extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'action',
    ];
}
