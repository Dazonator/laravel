<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{

    protected $fillable = [
        'title',
        'text',
        'performers_id',
        'initiator_id',
        'priority_id',
        'deadline',
        'startdate',
        'created_at',
        'update_at',
    ];

    protected $casts   = [
        'performers_id' => 'json'
    ];

    public function priority()
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function initiator()
    {
        return $this->hasOne(User::class, 'id', 'initiator_id')->select( 'id', 'img', 'name', 'lastname');
    }

    public function performers()
    {
        return $this->hasOne(User::class, 'id', 'performers_id')->select( 'id', 'img');
    }
}
