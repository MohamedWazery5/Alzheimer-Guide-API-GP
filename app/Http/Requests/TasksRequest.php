<?php

namespace App\Http\Requests;

use App\Services\TaskSchedulerService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rule = [
            'name' => 'required',
            'time' => 'required|date_format:H:i:s',
            'repeats_per_day' => 'required',
            'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date',
            'end_date' => 'required|date_format:Y-m-d',
            'repeat_typeID' => 'required|integer|exists:App\Models\RepeatType,id',
        ];
        if ($this->repeat_typeID == 3) {
            if (!$this->days) {
                throw new HttpResponseException(responseJson(401, '', ['days'=>'you should add custom days']));
            }
            $rule['days'] = "required|array|min:1";
            $rule['days.*'] = 'required';
            $date = TaskSchedulerService::checkCustomDays($this->days, $this->strat_date);
            $rule['start_date'] = 'required|date_format:Y-m-d|before_or_equal:end_date|before_or_equal:' . min($date);
            $rule['end_date'] = 'required|date_format:Y-m-d|after_or_equal:' . date('l', strtotime(max($date))) . "  " . max($date);
        }
        if (is_null($this->route('task_id'))) {
            $rule['patient_id'] = 'required|integer|exists:App\Models\Patient,id';
        }
        else{
            $rule['status'] = 'required';
        }
        return $rule;
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseJson(401, "", $validator->errors()));
    }
}
