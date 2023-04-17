<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MemoryRequest extends FormRequest
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
            'description' => 'required|string',
            'type' => 'required|integer',
        ];
        
        if (is_null($this->route('memory_id'))) {
            $rule['patient_id'] = 'required|integer|exists:App\Models\Patient,id';
        }
        return $rule;
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseJson(401, "", $validator->errors()));
    }
}
