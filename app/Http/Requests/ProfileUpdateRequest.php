<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['max:255', 'required'],
            'about' => [],
            'address' => [],
            'phone' => ['between:9,15', 'required'],
            'jsonspec' => ['json'],
            'speciality_one_id' => ['numeric'],
            'speciality_two_id' => ['numeric'],
            'speciality_three_id' => ['numeric'],            
            'lawyer' => ['boolean'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }

    public function messages(): array
{
    return [
        'phone.required' => 'Телефон обязателен',
        'phone.between' => 'Телефон не больше 15 цифр и не меньше 9', 
    ];
}
}
