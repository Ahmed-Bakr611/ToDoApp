<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'name',
    ];

    // Define many-to-many relationship with Task
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_tag');
    }
}
