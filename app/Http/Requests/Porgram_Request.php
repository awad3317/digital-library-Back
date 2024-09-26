<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class Porgram_Request extends FormRequest
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
            'name' =>[
                'required',
                'max:30',
                'string',
                'min:2',
                'unique:Programs,name'
            ] ,
            'image'=>[
                'required',
                File::image()->types(['jpeg','bmp','png','jpg'])
                ->max(2048),
            ],
            'file_path'=>[
                'required',
                File::types(['zip','rar']),
                'max:307200'
            ],
            'category_id'=>[
                'required',
                Rule::exists('categories', 'id')
            ],
            'description'=>[
                'nullable',
                'max:1000'
            ],
            'Version'=>[
                'nullable',
            ]

        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
