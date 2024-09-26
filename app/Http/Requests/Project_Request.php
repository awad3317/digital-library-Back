<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class Project_Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'number'=>[
                'required',
                'unique:projects,number'
            ],
            'title' =>[
                'required',
                'max:100',
                'string',
                'min:2',
                'unique:projects,title'
            ],
            'level'=>[
                'required',
            ],
            'image'=>[
                'nullable',
                File::image()->types(['jpeg','bmp','png','jpg'])
                ->max(2048),
            ],
            'file_path'=>[
                'required',
                File::types(['pdf'])
            ],
            'supervisor'=>[
                'required',
                'max:100',
                'min:2',
                'string'
            ],
            'description'=>[
                'nullable',
                'max:1000'
            ],
            'department_id'=>[
                'required',
                Rule::exists('departments', 'id')
            ],
            'academic_year_id'=>[
                'required',
                Rule::exists('academic_years', 'id')
            ]
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
