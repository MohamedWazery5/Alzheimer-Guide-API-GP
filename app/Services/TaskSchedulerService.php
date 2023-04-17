<?php

namespace App\Services;

use App\Models\CustomRepeat;
use App\Repositories\CustomRepeatTasksRepository;

class TaskSchedulerService
{
    private CustomRepeatTasksRepository $customRepeatRepository;
    public function __construct(CustomRepeatTasksRepository $customRepeatRepository)
    {
        $this->customRepeatRepository = $customRepeatRepository;
    }

    public function addCustomRepeats($repeat_typeID, $days, $start_date, $task_id)
    {
        if ($repeat_typeID == 3) {
            $days = $this->checkCustomDays($days, $start_date);
            $this->customRepeatRepository->addCustomRepeats($days, $task_id);
        }
    }

    public function getTodayCustomTasks($patient_id)
    {
        return $this->customRepeatRepository->getTodayCustomTasks($patient_id);
    }

    public function updateCustomRepeats($repeat_typeID, $days, $start_date, $task_id)
    {
        if ($repeat_typeID == 3) {
            $days = $this->checkCustomDays($days, $start_date);
            CustomRepeat::where("task_id", $task_id)->delete();
            $this->customRepeatRepository->addCustomRepeats($days, $task_id);
        }
        else{
            CustomRepeat::where("task_id", $task_id)->delete();
        }
    }

    public function checkRepeatsPerDays($tasks)
    {
        $data = [];
        foreach ($tasks as $task) {
            $repeats = (int)$task->repeats_per_day;
            $task->time = date('Y-m-d H:i:s', strtotime($task->time));
            $data[] = taskData($task);
            for ($i = 0; $i < $repeats - 1; $i++) {
                $newtask = $task;
                $newtask->time = date('Y-m-d H:i:s', strtotime(' + ' . 24 / $repeats . ' hour', strtotime($newtask->time)));
                $data[] = taskData($newtask);
            }
        }
        return $data;
    }

    public static function checkCustomDays($days, $start_date)
    {
        foreach ($days as $key=>$day) {
            $d = date('Y-m-d', strtotime($day));
            while ($d < $start_date) {
                $d = date('Y-m-d', strtotime($d . ' + 7 days'));
            }
            $date[$key] = $d;
        }
        return $date;
    }
}
