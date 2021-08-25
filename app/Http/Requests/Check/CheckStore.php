<?php

namespace App\Http\Requests\Check;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use League\Csv\Reader;

class CheckStore extends FormRequest
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
            'name' => ['required', 'unique:check_table,check_name'],
            'check_type' => ['required'],
            'date_start' => ['required', function ($attribute, $value, $fail) {
                if (strtotime($this->date_end) < strtotime($value)) {
                    return $fail('<strong>День начала голосования</strong> не может быть позже дня завершения');
                }
            }],
            'date_end' => ['required'],
            'auth_link' => ['required'],
            'auth_type' => ['required_with:auth_needed'],
            'auth_login' => ['required_with:auth_needed', 'max:64'],
            'auth_password' => ['required_with:auth_needed', 'max:64'],
            'csv' => ['required', 'file', 'mimetypes:text/csv,text/plain,application/csv', function ($attribute, $value, $fail) {

                // load the CSV document from a file path
                $csv = Reader::createFromPath($value, 'r');
                $csv->setDelimiter(';');
                $csv->setHeaderOffset(0);

                $headers = $csv->getHeader(); //returns the CSV header record
                $records = iterator_to_array($csv->getRecords()); //returns all the CSV records as an Iterator object

                $requiredFields = [
                    'region',
                    'region_number',
                    'station_address',
                    'station_number',
                    'timezone_offset_minutes',
                    'latitude',
                    'longitude',
                    'camera_id'
                ];

                $doubleTypeFields = [
                    'latitude',
                    'longitude',
                ];

                $intTypeFields = [
                    'region_number',
                    'station_number',
                    'timezone_offset_minutes',
                    'voters_registered'
                ];

                foreach ($requiredFields as $field) {
                    if (!in_array($field, $headers)) {
                        return $fail("В заголовке CSV-файла отсутствует столбец - <strong>$field</strong>");
                    }
                }

                foreach ($records as $key => $record) {
                    /* Заполнены ли обязательные поля и проверяем их кодировку */
                    foreach ($requiredFields as $field) {
                        if (array_key_exists($field, $record) && $record[$field] == "") {
                            return $fail("В CSV-файле столбец <strong>$field</strong> не может быть пустым. Строка - $key");
                        }

                        $encoding = mb_detect_encoding($record[$field], 'UTF-8', TRUE);

                        if (!$encoding) {
                            return $fail('Кодировка файла должна быть: <strong>UTF-8</strong>');
                        }
                    }

                    /* Не являются ли координаты целым числом и разделитель не "," запятая */
                    foreach ($doubleTypeFields as $field) {
                        if (strpos($record[$field], ',')) {
                            return $fail("В качестве разделителя целой и дробной частей для поля <strong>$field</strong> должна быть точка, а не запятая. Строка - $key");
                        }

                        if (is_int($record[$field] * 1)) {
                            return $fail("Поле <strong>$field</strong> не может быть целым числом. Строка - $key");
                        }
                    }

                    /*  TODO: А КАК ПРОВЕРИТЬ СТРОКИ CSV НА ЦЕЛОЕ ЧИСЛО? Являются ли целыми числами поля */
                    //                    foreach ($intTypeFields as $field) {
                    //                        if (array_key_exists($field, $record) && !is_int($record[$field])) {
                    //                            return $fail("В столбце <strong>$field</strong> значения должны быть целочисленными. Строка - $key");
                    //                        }
                    //                    }
                }
            }]
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
            'name.required' => 'Поле <strong>Название проверки</strong> обязательно для заполнения',
            'name.unique' => 'Проверка с таким именем уже существует',
            'check_type.required' => 'Поле <strong>Тип проверки</strong> обязательно для заполнения',
            'date_start.required' => 'Поле <strong>Дата начала проверки</strong> обязательно для заполнения',
            'date_end.required' => 'Поле <strong>Дата окончания проверки</strong> обязательно для заполнения',
            'auth_link.required' => 'Поле <strong>Ссылка(и) на каталоги видео</strong> обязательно для заполнения',
            'auth_type.required_with' => 'Поле <strong>Вид авторизации</strong> обязательно для заполнения',
            'auth_login.required_with' => 'Поле <strong>Логин</strong> обязательно для заполнения',
            'auth_login.max' => 'Поле <strong>Логин</strong> не должно превышать 64 символа',
            'auth_password.required_with' => 'Поле <strong>Пароль</strong> обязательно для заполнения',
            'auth_password.max' => 'Поле <strong>Пароль</strong> не должно превышать 64 символа',
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
