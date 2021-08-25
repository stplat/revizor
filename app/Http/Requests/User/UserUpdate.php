<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\User;

class UserUpdate extends FormRequest
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
            'user' => ['required', function ($attribute, $value, $fail) {
                if ((int)$value && !User::find($value)) {
                    $fail('Пользователя с ID: <strong>' . request('user') . '</strong> не существует');
                }
            }],
            'username' => ['required'],
            'login' => ['required', 'string', 'unique:users,user_login,' . $this->user . ',user_id'],
            'role_id' => ['required'],
            'permissions' => ['required'],
            'checks' => [function ($attribute, $value, $fail) {
                if ($this->role_id == 3 && !$value) {
                    $fail('Поле <strong>Доступные проверки</strong> обязательно для заполнения');
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
            'user.required' => 'Поле <strong>ID</strong> обязательно для заполнения',
            'username.required' => 'Поле <strong>Юзернейм</strong> обязательно для заполнения',
            'user_login.required' => 'Поле <strong>Логин</strong> обязательно для заполнения',
            'user_login.unique' => 'Такой <strong>Логин</strong> уже существует',
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
