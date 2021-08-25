<?php

namespace App\Http\Requests\Server;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\Server;

class ServerUpdate extends FormRequest
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
      'server' => ['numeric', function ($attribute, $value, $fail) {
        if ((int)$value && !Server::find($value)) {
          $fail('Сервер с ID: <strong>' . request('server') . '</strong> не существует');
        }
      }],
      'server_id' => ['required', 'numeric', 'unique:servers,server_id,' . request('server') . ',server_id'],
      'password' => [],
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
      'server_id.required' => 'Поле <strong>Токен ID</strong> обязательно для заполнения',
      'server_id.unique' => 'Сервер с таким <strong>ID</strong> уже существует',
      'server_id.numeric' => 'Поле <strong>Сервер ID</strong> должно быть числом',
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
