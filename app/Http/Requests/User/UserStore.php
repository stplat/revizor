<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStore extends FormRequest
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
            'user_quantity' => ['required'],
            'role_id' => ['required'],
            'permissions' => ['required'],
            'checks' => [function ($attribute, $value, $fail) {
                if ($this->role_id == 3 && !$value) {
                    return $fail('Поле <strong>Доступные проверки</strong> обязательно для заполнения');
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
            'user_login.required' => 'Поле <strong>Логин</strong> обязательно для заполнения',
            'user_login.unique' => 'Пользователь с таким <strong>Логином</strong> уже существует',
            'user_pass.required' => 'Поле <strong>Пароль</strong> обязательно для заполнения',
            'role_id.required' => 'Поле <strong>Роль</strong> обязательно для заполнения',
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
