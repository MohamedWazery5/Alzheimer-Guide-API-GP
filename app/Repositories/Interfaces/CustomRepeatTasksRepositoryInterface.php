<?php

namespace App\Repositories\Interfaces;

interface CustomRepeatTasksRepositoryInterface
{
    public function addCustomRepeats(array $days, $task_id);
    public function updateCustomRepeats($task_id, array $data);
    public function getTodayCustomTasks($patient_id);
}
