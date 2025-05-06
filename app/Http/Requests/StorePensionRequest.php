<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePensionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'gender' => 'required',
            'stagh2002' => 'required',
            'stagh1991' => 'required',
        ];
    }
    
    public function messages()
    {
        return [
            'gender.required' => 'Выбрать пол обязательно',
            'stagh2002.required' => 'Выбрать стаж обязательно',
            'stagh1991.required' => 'Выбрать стаж обязательно',
        ];
    }
    
}