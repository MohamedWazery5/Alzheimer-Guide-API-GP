<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    use HasFactory;
    protected $table = 'tasks_history';
    protected $fillable=[
        'photo','task_id'
    ];

    public function taskScheduler(){
        return $this->belongsTo(TaskScheduler::class,'task_id');
    }
}
