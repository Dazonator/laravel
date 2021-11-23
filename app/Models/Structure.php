<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'structure_id'
    ];

//    public function structure()
//    {
//        return $this->hasOne(Structure::class);
//    }

    public function children()
    {
        return $this->hasMany(Structure::class)->with('children');
    }
}
