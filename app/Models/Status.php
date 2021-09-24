<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'status'
    ];


    public function statusTasks()
    {
        return $this->hasMany(Tasks::class);
    }
}
