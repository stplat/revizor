<?php

namespace App\Http\Requests\Check;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use League\Csv\Reader;

class CheckUploadIntermediateVote extends FormRequest
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
            'check_id' => ['required'],
            'file' => ['required', 'file', 'mimetypes:text/csv,text/plain,application/csv', function ($attribute, $value, $fail) {

                // load the CSV document from a file path
                $csv = Reader::createFromPath($value, 'r');
                $csv->setDelimiter(';');
                $csv->setHeaderOffset(0);

                $headers = $csv->getHeader(); //returns the CSV header record
                $records = iterator_to_array($csv->getRecords()); //returns all the CSV records as an Iterator object

                $requiredFields = [
                    'region_number',
                    'station_number',
                    'voters_voted_in_ballot_box',
                    'date',
                ];

                $intTypeFields = [
                    'region_number',
                    'station_number',
                    'voters_voted_in_ballot_box',
                ];

                $dateTypeFields = [
                    'date',
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

                        if (array_key_exists($field, $record) && !$encoding) {
                            return $fail('Кодировка файла должна быть: <strong>UTF-8</strong>');
                        }
                    }

                    foreach ($dateTypeFields as $field) {
                        $dateType = preg_match('/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$/', $record[$field]);

                        if (array_key_exists($field, $record) && !$dateType) {
                            return $fail("В CSV-файле столбец <strong>$field</strong> должен быть в формате: YYYY-MM-DD. Строка - $key");
                        }
                    }

                    /*  TODO: А КАК ПРОВЕРИТЬ СТРОКИ CSV НА ЦЕЛОЕ ЧИСЛО? Являются ли целыми числами поля */
//                    foreach ($intTypeFields as $field) {
//                        if (array_key_exists($field, $record) && !is_int($record[$field])) {
//                            return $fail("В столбце <strong>$field</strong> значения должны быть целочисленными. Строка - $key");
//                        }
//                    }
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
            'check_id.required' => 'Поле <strong>ID проверки</strong> обязательно для заполнения',
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
