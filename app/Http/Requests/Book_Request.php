<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;

class Book_Request extends FormRequest
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
                'max:100',
                'string',
                'min:2',
                'unique:Books,name'
            ],
            'image'=>[
                'required',
                File::image()->types(['jpeg','bmp','png','jpg'])
                ->max(2048),
            ],
            'file_path'=>[
                'required',
                File::types(['pdf']),
                'max:307200'
            ],
            'Publisher_id'=>[
                'nullable',
            ],
            'edition'=>[
                'string'
            ],
            'description'=>[
                'nullable',
                'max:1000'
            ],
            'category_id'=>[
                'required',
            ],
            'book_audio' =>[
                'nullable',
                File::types(['mp3'])
            ]
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
