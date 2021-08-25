<?php

namespace App\Http\Requests\ApiToken;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiTokenStore extends FormRequest
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
      'token_id' => ['required', 'numeric', 'unique:api_tokens,token_id'],
      'server_id' => ['required', 'numeric'],
      'token' => ['required'],
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
      'token_id.required' => 'Поле <strong>Токен ID</strong> обязательно для заполнения',
      'token_id.unique' => 'Такой <strong>Токен ID</strong> уже существует',
      'token_id.numeric' => 'Поле <strong>Токен ID</strong> должно быть числом',
      'server_id.required' => 'Поле <strong>Сервер ID</strong> обязательно для заполнения',
      'server_id.numeric' => 'Поле <strong>Сервер ID</strong> должно быть числом',
      'token.required' => 'Поле <strong>Токен</strong> обязательно для заполнения',
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
