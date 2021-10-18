<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessagesUser extends Model
{
    protected $table = 'messages_user';
    public function tasks()
    {
        return $this->hasMany(Messages::class, 'id', 'user_id');
    }
}
