<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
}
