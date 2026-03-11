<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',       // todo, in_progress, done
        'project_id',
        'assigned_to',  // user id
        'priority',     // 1-5
    ];

    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

   
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
