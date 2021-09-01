<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{

    protected $fillable = [
        'title',
        'text',
        'performers',
        'initiator',
        'priority',
        'deadline',
        'startdate',
        'created_at',
        'update_at',
    ];

    protected $casts   = [
        'performers' => 'json'
    ];
}
