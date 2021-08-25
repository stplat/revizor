<?php

namespace App\Http\Requests\Tip;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\Uik;

class TipShowUik extends FormRequest
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
            'uik' => ['required', function ($attribute, $value, $fail) {
                if (!Uik::find($value)) {
                    return $fail('Участка с ID: <strong>' . request('uik') . '</strong> не существует');
                }
            }],
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
            'uik.required' => 'Поле <strong>ID участка</strong> обязательно для заполнения',
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
