<?php

namespace App\Http\Requests\Modal;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModalStoreClaim extends FormRequest
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
            // 'applicant' => ['required'],
            'content' => ['required'],
            // 'decree' => ['required'],
            'violation' => ['required'],
            'violationType' => ['required'],
            'file' => ['nullable', 'file', 'mimetypes:application/pdf'],
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
            'applicant.required' => 'Поле <strong>ID заявителя</strong> обязательно для заполнения',
            'content.required' => 'Поле <strong>Содержание</strong> обязательно для заполнения',
            'decree.required' => 'Поле <strong>ID постановления</strong> обязательно для заполнения',
            'violation.required' => 'Поле <strong>ID нарушения</strong> обязательно для заполнения',
            'violationType.required' => 'Поле <strong>ID статуса нарушения</strong> обязательно для заполнения',
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
