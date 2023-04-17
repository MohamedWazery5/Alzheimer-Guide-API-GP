<?php

namespace App\Repositories\Interfaces;


interface TaskSchedulerRepositoryInterface
{
    public function addTask(array $data);
    public function getTask($task_id);
    public function getPatientTasks($patient_id);
    public function getToDayTasks($patient_id);
    public function deleteTask($task_id);
    public function updateTask($task_id,array $data);


}
