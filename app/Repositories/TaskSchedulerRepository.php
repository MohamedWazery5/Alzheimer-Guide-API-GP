<?php

namespace App\Repositories;

use App\Models\TaskScheduler;
use App\Repositories\Interfaces\TaskSchedulerRepositoryInterface;

class TaskSchedulerRepository implements TaskSchedulerRepositoryInterface
{
    public function __construct(private TaskScheduler $task)
    {
    }
    public function addTask(array $data)
    {
        return $this->task->create($data);
    }
    public function getTask($task_id)
    {
        return $this->task->find($task_id);
    }
    public function getPatientTasks($patient_id)
    {
        return $this->task->where('patient_id', $patient_id)->get();
    }
    public function getToDayTasks($patient_id)
    {
        $today = date('Y-m-d');
        return $this->task->where('patient_id', $patient_id)
            ->active()->whereRaw('"' . $today . '" between `start_date` and `end_date`')
            ->doesntHave('customRepeats')->get();
    }
    public function deleteTask($task_id)
    {
        return $this->task->destroy($task_id);
    }
    public function updateTask($task_id, array $data)
    {
        return $this->task->whereId($task_id)->update($data);
    }
}
