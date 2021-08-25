<?php

namespace App\Http\Requests\Modal;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\Camera;

class ModalShowVideo extends FormRequest
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
            'dateFrom' => ['required', 'date'],
            'dateTo' => ['required', 'date'],
            'camera' => ['required'],
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
            'dateFrom.required' => 'Поле <strong>ID камеры</strong> обязательно для заполнения',
            'dateTo.required' => 'Поле <strong>ID камеры</strong> обязательно для заполнения',
            'camera.required' => 'Поле <strong>Camera</strong> обязательно для заполнения',
            'dateFrom.date' => 'Поле <strong>dateFrom</strong> должно быть датой',
            'dateTo.date' => 'Поле <strong>dateTo</strong> должно быть датой',
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
