<?php

namespace App\Http\Requests\Check;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckFilterUiks extends FormRequest
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
      'id' => ['required'],
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
      'id.required' => 'Поле <strong>ID проверки</strong> обязательно для заполнения',
      'name.unique' => 'Такой <strong>Заявитель</strong> уже существует',
      'organization.required' => 'Поле <strong>Организация</strong> обязательно для заполнения',
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
