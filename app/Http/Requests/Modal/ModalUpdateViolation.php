<?php

namespace App\Http\Requests\Modal;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\Violation;
use App\Models\ViolationStatus;

class ModalUpdateViolation extends FormRequest
{

    public function __construct()
    {
        parent::__construct();
        request()->request->add(request()->route()->parameters());
    }

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
     * @return array
     */
    public function rules()
    {
        return [
            'violation' => ['required', function ($attribute, $value, $fail) {
                if (!Violation::find($value)) {
                    return $fail('Нарушения с ID: <strong>' . request('violation') . '</strong> не существует');
                }
            }],
            'violationStatus' => [function ($attribute, $value, $fail) {
                if (!ViolationStatus::find($value)) {
                    return $fail('Статус нарушения с ID: <strong>' . request('violation') . '</strong> не существует');
                }
            }],
            'check' => ['required'],
            'blocked' => [],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'check.required' => 'Поле <strong>ID проверки</strong> обязательно для заполнения',
            'violation.required' => 'Поле <strong>ID нарушения</strong> обязательно для заполнения',
            'violationStatus.required' => 'Поле <strong>ID статуса нарушения</strong> обязательно для заполнения',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()]));
    }
}
