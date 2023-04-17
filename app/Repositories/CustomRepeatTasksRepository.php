<?php

namespace App\Repositories;

use App\Models\CustomRepeat;
use App\Models\TaskScheduler;
use Illuminate\Database\Eloquent\Builder;

class CustomRepeatTasksRepository
{
    public function __construct(private CustomRepeat $customRepeat)
    {
    }
    public function addCustomRepeats(array $days, $task_id)
    {
        $data = [];
        foreach ($days as $key => $date) {
            $day = CustomRepeat::create(['date' => $date, "task_id" => $task_id]);
            $data[] = $day;
        }
        return $data;
    }
    public function updateCustomRepeats($task_id, array $data)
    {
        
    }
    public function getTodayCustomTasks($patient_id)
    {
        $data = [];
        $customRepeatsTasks = TaskScheduler::where('patient_id', $patient_id)->active()
            ->whereHas('customRepeats', function (Builder $query) {
                $today = date('Y-m-d');
                $query->where('date', $today);
            })->get();
        foreach ($customRepeatsTasks as $task) {
            $data[] = $task;
        }
        return $data;
    }
}
