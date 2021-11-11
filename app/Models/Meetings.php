<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetings extends Model
{
    protected $fillable = [
        'number',
        'initiator_id',
        'department_id',
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
}
