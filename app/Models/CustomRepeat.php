<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomRepeat extends Model
{
    use HasFactory;
    protected $table = 'custom_repeats';
    protected $fillable = [
        'task_id', 'date'
    ];
    public $timestamps = false;

    public function taskScheduler()
    {
        return $this->belongsTo(TaskScheduler::class);
    }
}
