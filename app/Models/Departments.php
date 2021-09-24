<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $fillable = [
        'department'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }

//    public function tasksByDepartments(){
//        return $this->hasManyThrough(Tasks::class, User::class, 'department_id', 'performers_id');
//    }
}
