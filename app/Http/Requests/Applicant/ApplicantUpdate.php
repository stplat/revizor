<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Models\Applicant;

class ApplicantUpdate extends FormRequest
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
      'applicant' => ['numeric', function ($attribute, $value, $fail) {
        if ((int)$value && !Applicant::find($value)) {
          $fail('Пользователя с ID: <strong>' . $value . '</strong> не существует');
        }
      }],
      'name' => ['required', 'string', 'unique:violation_applicant_name,name,' . $this->applicant . ',applicant_id'],
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
      'applicant.numeric' => 'Поле <strong>ID</strong> должно быть числом',
      'name.required' => 'Поле <strong>Логин</strong> обязательно для заполнения',
      'name.unique' => 'Такой <strong>Логин</strong> уже существует',
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
