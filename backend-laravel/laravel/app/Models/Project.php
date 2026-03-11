<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
    ];

    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

   
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
