<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use function PHPUnit\Framework\isNull;

class PatientRequest extends FormRequest
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
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'Stage' => 'required|integer',
            'address' => 'required|string',
            'birth_date' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'password' => 'required|string|confirmed|min:6'

        ];
        if (is_null($this->route('patient_id'))) {
            $rule['caregiver_id'] = 'required|exists:App\Models\Caregiver,id';
        } else {
            $id = $this->route('patient_id');
            $patient = Patient::find($id);
            if (!$patient) {
                throw new HttpResponseException(responseJson(401, '', 'this Pateint_id not found'));
            }
            $rule['email'] = 'required|string|email|max:100|unique:users,email,' . $patient->user->id;
        }
        return $rule;
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseJson(401, "", $validator->errors()));
    }
}
