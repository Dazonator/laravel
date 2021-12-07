<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksFiles extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'path',
        'attached',
        'task_id',
    ];
}
