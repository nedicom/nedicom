<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'created_at' => 'required',
            'fio' => 'required|min:5',
            'description' => 'required|min:15',
            'mainusl_id'=> 'numeric',
            'usl_id' => 'numeric',
        ];
    }

    public function messages(): array
{
    return [
        'created_at.required' => 'Дата',
        'fio.required' => 'Имя для отзыва обязательно',
        'description.required' => 'Описание самого отзыва обязательно',
        'mainusl_id.numeric' => 'Выберите главную услугу (выше на странице, потом нажмите обновить услугу (не отзыв))',
        'fio.min' => 'Не короче 5 символов',
        'description.min' => 'Не короче 5 символов',
    ];
}
}
