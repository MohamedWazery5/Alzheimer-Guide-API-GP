<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\TaskScheduler;
use App\Models\Patient;
use App\Models\TaskHistory;
use App\Repositories\Interfaces\TaskSchedulerRepositoryInterface;
use App\Services\TaskSchedulerService;
use App\Traits\ManageFileTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskSchedulerController extends Controller
{
    use ManageFileTrait;
    public function __construct(
        private TaskSchedulerRepositoryInterface $taskRepository,
        private TaskSchedulerService $taskService
    ) {
    }
    public function createTask(TasksRequest $request)
    {
        try {
            DB::beginTransaction();
            $task = $this->taskRepository->addTask([
                'name' => $request->name,
                'details' => $request->details,
                'time' => $request->time,
                'status' => true,
                'repeats_per_day' => $request->repeats_per_day,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'repeat_typeID' => $request->repeat_typeID,
                'patient_id' => $request->patient_id
            ]);
            $this->taskService->addCustomRepeats($request->repeat_typeID, $request->days, $request->start_date, $task->id);
            DB::commit();
            return responseJson(201, taskData($task), "Task Inserted ");
        } catch (Exception $e) {
            DB::rollBack();
            return responseJson(401, '', $e);
        }
    }

    public function getTask($id)
    {
        $task = $this->taskRepository->getTask($id);
        if ($task) {
            return responseJson(201, taskData($task), "done");
        }
        return responseJson(401, "", "this TaskId not found");
    }

    public function getAllTasks($patient_id)
    {
        $tasks = $this->taskRepository->getPatientTasks($patient_id);
        if ($tasks->count() > 0) {
            foreach ($tasks as $task) {
                $data[] = $task;
            }
            $data = $this->taskService->checkRepeatsPerDays($data);
            return responseJson(201, $data, 'task Scheduler data');
        }
        return responseJson(401, '', 'this Patient Not have Any task Scheduler');
    }

    public function getToDayTasks($patient_id)
    {
        $tasks = $this->taskRepository->getToDayTasks($patient_id);
        $data = $this->taskService->getTodayCustomTasks($patient_id);
        foreach ($tasks as $task) {
            $data[] = $task;
        }
        $data = $this->taskService->checkRepeatsPerDays($data);
        return responseJson(201, $data, "today tasks for this patient");
    }

    public function deleteTask($id)
    {
        $task = $this->taskRepository->getTask($id);
        if ($task) {
            $taskhistory = $task->taskHistory;
            foreach ($taskhistory as $history) {
                if ($history->photo !== "Null") {
                    $this->deleteFile($history->photo);
                }
            }
            $this->taskRepository->deleteTask($id);
            return responseJson(201, "", " task Deleted ");
        }
        return responseJson(401, "", "this TaskId not found");
    }


    public function updateTask($id, TasksRequest $request)
    {
        try {
            DB::beginTransaction();
            $task = $this->taskRepository->getTask($id);
            if ($task) {
                $this->taskRepository->updateTask($id, [
                    'name' => $request->name,
                    'details' => $request->details,
                    'time' => $request->time,
                    'status' => $request->status,
                    'repeats_per_day' => $request->repeats_per_day,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'repeat_typeID' => $request->repeat_typeID,
                ]);
                $this->taskService->updateCustomRepeats($request->repeat_typeID, $request->days, $request->start_date, $task->id);
                DB::commit();
                return responseJson(201, taskData($task), 'task updated ');
            }
            return responseJson(401, "", "this TaskId not found");
        } catch (Exception $e) {
            DB::rollBack();
            return responseJson(401, 'jfdsnsd', $e);
        }
    }
}
