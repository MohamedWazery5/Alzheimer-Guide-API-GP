<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\TaskHistory;
use App\Models\TaskScheduler;
use App\Traits\ManageFileTrait;
use DateTime;
use Illuminate\Http\Request;

class TaskHistoryController extends Controller
{
    use ManageFileTrait;

    public function confirmTask(Request $request)
    {
        $task = TaskScheduler::find($request->task_id);
        if ($task) {
            $photo = $this->uploadFile($request, 'photo', 'Task History Photos');
            $history = TaskHistory::create([
                'photo' => $photo,
                'task_id' => $request->task_id
            ]);
            if ($history) {
                return responseJson(201, historyData($history), "task confirmed");
            }
            return responseJson(401, "", "An Error Occuerd ");
        }
        return responseJson(401, "", "this TaskId not found");
    }

    public function getPatientHistroy($patient_id)
    {
        $patient = Patient::find($patient_id);
        $data = [];
        if ($patient) {
            if ($patient->taskScheduler->count() > 0) {
                $patient_tasks = $patient->taskScheduler;
                foreach ($patient_tasks as $task) {
                    if ($task->taskHistory->count() > 0) {
                        $history = $task->taskHistory;
                        foreach ($history as $his) {
                            $data[] = historyData($his);
                        }
                    }
                }
                return responseJson(201, $data, "patient history");
            }
            return responseJson(401, '', "this patient  doesn't have  tasks ");
        }
        return responseJson(401, '', 'this patient_id not found');
    }
    public function getTaskHistory($task_id)
    {
        $task = TaskScheduler::find($task_id);
        $data = [];
        if ($task) {
            if ($task->taskHistory->count() > 0) {
                $history = $task->taskHistory;
                foreach ($history as $his) {
                    $data[] = historyData($his);
                }
                return responseJson(201, $data, "Task history");
            }
            return responseJson(401, '', "this task  doesn't have  history yet ");
        }
        return responseJson(401, '', 'this Task_id not found');
    }

    public function getTasksHistroyByDate($patient_id, $date)
    {
        if ($this->validateDate($date)) {
            $patient = Patient::find($patient_id);
            $data = [];
            if ($patient) {
                if ($patient->taskScheduler->count() > 0) {
                    $patient_tasks = $patient->taskScheduler;
                    foreach ($patient_tasks as $task) {
                        $history = TaskHistory::where('task_id', $task->id)->where('created_at', $date)->get();
                        if ($history->count() > 0) {
                            foreach ($history as $his) {
                                $data[] = historyData($his);
                            }
                        }
                    }
                    return responseJson(201, $data, "patient history for this " . $date);
                }
                return responseJson(401, '', "this patient  doesn't have  tasks ");
            }
            return responseJson(401, '', 'this patient_id not found');
        }
        return responseJson(401, '', 'Date Not Correct');
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    public  function getHistoryImage($history_id)
    {
        $history = TaskHistory::find($history_id);
        if ($history) {
            return $this->getFile($history->photo);
        }
        return responseJson(401, '', 'this History_id not found');
    }
}
